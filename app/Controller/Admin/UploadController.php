<?php


namespace App\Controller\Admin;

use App\Vendor\Upload;
use League\Flysystem\Filesystem;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use App\Model\AdminSet;

/**
 * @Controller()
 */
class UploadController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/upload/layui", methods="post")
     */
    public function layuiImg(Filesystem $filesystem)
    {
        $uploadFile = key($this->request->getUploadedFiles());
        if(!$uploadFile){
            return ['success'=>'0','message'=>'图片上传错误','url'=>''];
        }

        $file = $this->request->file($uploadFile);
        if(!in_array(strtolower($file->getExtension()), $this->getUploadExt())){
            return ['success'=>'0','message'=>'文件格式错误','url'=>''];
        }
        if(!in_array(strtolower($file->getMimeType()), $this->getUploadMime())){
            return ['success'=>'0','message'=>'文件格式错误','url'=>''];
        }

        $allowSieze = $this->getUploadImageSize();

        if($file->getSize()>$allowSieze){
            return ['success'=>'0','message'=>'图片过大','url'=>''];
        }

        $upload = new Upload($this->request,$filesystem,'image');

        $result = $upload->upload();

        if(!is_bool($result)){
            return ['success'=>'1','message'=>'ok','url'=>$result];
        }

        return ['success'=>'0','message'=>'上传错误,请重试','url'=>''];
    }

    protected function getUploadExt()
    {
        $upload_ext = AdminSet::where('key','upload_ext')->first();
        if(!$upload_ext){
            return [];
        }

        return explode(',',$upload_ext->val);
    }

    protected function getUploadMime()
    {
        $upload_mime = AdminSet::where('key','upload_mime')->first();
        if(!$upload_mime){
            return [];
        }

        return explode(',',$upload_mime->val);

    }

    protected function getUploadImageSize()
    {
        $upload_image_size = AdminSet::where('key','upload_image_size')->first();
        if('' == $upload_image_size->val || !is_numeric($upload_image_size->val)){
            return 0;
        }

        return $upload_image_size->val;
    }

}