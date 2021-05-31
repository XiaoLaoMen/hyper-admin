<?php

namespace App\Helper;

use App\Constants\Code;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\ApplicationContext;

class Helper
{

    public function getError($message=null,$code = 400)
    {
        $message = $message ?? Code::getMessage($code);

        $result = [
            'code'=>$code,
            'message'=>$message,
            'data'=>[],
        ];

        return $result;
    }

    public function getSuccess($data = [],$message=null)
    {
        $count = $data['count'] ?? '0';
        $list = $data['list'] ?? $data;
        $result = [
            'count'=>$count,
            'code'=>0,
            'message'=>$message ?? Code::getMessage('0'),
            'data'=>$list,
        ];

        return $result;
    }

    public function getContainer()
    {
        $container = ApplicationContext::getContainer();
        return $container;
    }

    public function getIdGenerator()
    {
        $container = $this->getContainer();
        $generator = $container->get(IdGeneratorInterface::class);
        return $generator->generate();
    }

    public function getRandStr()
    {
        $generator = (string)$this->getIdGenerator();
        return md5($generator);
    }

}

