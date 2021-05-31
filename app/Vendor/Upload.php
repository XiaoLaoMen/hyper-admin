<?php

declare(strict_types=1);

namespace App\Vendor;

use Hyperf\Utils\Context;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\ApplicationContext;

class Upload
{


    private $filesystem='filesystem';

    private $params='params';

    private $savepath = array(
        'image'=>'/upload/image',
        'video'=>'/upload/video',
        'file'=>'/upload/file'
    );

    private $filepath = '/upload/tmp';

    private $basepath = 'public';

    /**
     * 初始化数据
     * @return bool
     */
    public function __construct($request,$filesystem,$type)
    {

        $upload['isChunked'] = $request->input('isChunked', false); //是否开启分片

        if($upload['isChunked']=='true'){
            $upload['chunks'] = $request->input('chunks', 0); //总分片数量
            $upload['size'] = $request->input('size', 0);  //文件大小
            $upload['chunk'] = $request->input('chunk', 0); //当前分片
            $upload['fileName'] = md5($request->input('name', '0')); //分片唯一名称
            $upload['md5Value'] = $request->input('md5Value', 0); //文件md5
        }

        if(!is_array($request->getUploadedFiles())){
            return false;
        }

        $uploadFile = key($request->getUploadedFiles());

        if(is_array($request->getUploadedFiles()[$uploadFile])){
            return false;
        }

        $this->uploadVaild($request,$uploadFile);

        $file = $request->file($uploadFile);

        $this->getError($file);

        $extension = $file->getExtension();

        $upload['file'] = $file;
        $upload['extension'] = $extension;

        Context::set($this->params, $upload);
        Context::set($this->filesystem, $filesystem);
        Context::set('type', $type);
    }

    public function upload()
    {
        $request = Context::get($this->params);
        $filesystem = Context::get($this->filesystem);
        return $this->moveFile($request,$filesystem);
    }

    /**
     * 移动文件
     * @param $file
     * @param $data
     * @return bool
     */
    protected function moveFile($data,$filesystem)
    {

        $file = $data['file'];

        if($data['isChunked']=='true'){

            $this->createFolders($this->filepath);

            $namePrefix = $data['fileName'].'__'.$data['chunk'];

            $name = $this->filepath.'/'.$namePrefix.'.'.$data['extension'];

            $exists = $filesystem->has($name);

            if($exists){
                $chunksmMd5 = $this->getMd5Chunk($file,$name);
                if(!$chunksmMd5){
                    $filesystem->delete($name);
                    $exists=false;
                }
            }

            if(!$exists){
                $this->saveFile($file,$filesystem,$name);
            }
            return $this->fileMerge($data,$filesystem);
        }

        $name = $this->getDir().$this->gertName().'.'.$data['extension'];

        $result = $this->saveFile($file,$filesystem,$name);

        if(!$result){
            return false;
        }

        return $name;

    }

    /**
     * 判断是否是最后一片，如果是则进行文件合成并且删除文件分片
     * @param $data
     * @param $filesystem
     */
    private function fileMerge($data,$filesystem){

        $name=true;

        if($data['chunk']+1 == $data['chunks']){

            $stramFile = $this->filepath.'/'. $data['fileName'].'__';

            $name = $this->getDir().$this->gertName().'.'.$data['extension'];

            $content = '';

            for($i=0; $i<$data['chunks']; $i++){

                $content .= $filesystem->read($stramFile.$i.'.'.$data['extension']);
            }

            $result = $filesystem->write($name, $content);

            if(!$result){
                return false;
            }

            $this->deleteFileBlob($data,$stramFile,$filesystem);

            $md5Info = $this->getMd5File($data,$name);

            if(!$md5Info){
                $filesystem->delete($name);
                return false;
            }

        }

        return $name;
    }

    /**
     * 删除文件
     * @param $data
     * @param $stramFile
     */
    private function deleteFileBlob($data,$stramFile,$filesystem){
        for($i=0; $i< $data['chunks']; $i++){
            $filesystem->delete($stramFile.$i.'.'.$data['extension']);
        }

    }

    /**
     *
     * 验证是否有上传文件以及上传是否有效
     * @return bool
     */
    protected function uploadVaild($request,$uploadFile)
    {
        $fileIsValid = $request->file($uploadFile)->isValid();

        if(!$uploadFile || !$fileIsValid){
            return false;
        }


        return true;
    }

    /**
     * 是否出错
     * @param $file
     * @return bool
     */
    protected function getError($file)
    {
        switch ($file->getError()) {
            case 1:
            case 2:
                return false;
            case 3:
                return false;
            case 4:
                return false;
            case 6:
            case 7:
                return false;
        }
        return true;
    }

    /**
     * 递归创建目录
     * @param $dir
     * @return bool
     */
    protected function createFolders($dir)
    {
        return is_dir($dir) or ($this->createFolders(dirname($dir)) and mkdir($dir,0777,true));
    }

    /**
     * 获取保存目录
     * @return string
     */
    protected function getDir()
    {
        $getType = Context::get('type');

        $savePath = '/'.trim($this->savepath[$getType],'/').'/'.date('Y-m-d',time()).'/';

        $this->createFolders($savePath);

        return $savePath;
    }

    /**
     * 保存文件
     * @param $file
     * @param $filesystem
     * @param $name
     * @return mixed
     */
    protected function saveFile($file,$filesystem,$name)
    {

        $stream = fopen($file->getRealPath(), 'r+');

        $result = $filesystem->writeStream($name,$stream);

        fclose($stream);

        if(!$result){
            return false;
        }

        return $name;
    }

    /**
     * 获取文件名
     * @return string
     */
    protected function gertName()
    {
        $container = ApplicationContext::getContainer();

        $generator = $container->get(IdGeneratorInterface::class);

        return md5((string)$generator->generate());
    }

    /**
     * 断点续传
     * @param $file
     * @param $name
     * @return bool
     */
    protected function getMd5Chunk($file,$name)
    {
        $newMd5 = md5_file($file->getRealPath());

        $oldMd5 = md5_file($this->basepath.$name);
        if($newMd5 != $oldMd5)
        {
            return false;
        }
        return true;
    }

    /**
     * 上传文成后的md5验证
     * @param $data
     * @param $name
     * @return bool
     */
    protected function getMd5File($data,$name)
    {
        $newMd5 = md5_file($this->basepath.$name);
        if($data['md5Value'] != $newMd5)
        {
            return false;
        }
        return true;
    }
}