<?php

use App\Vendor\Code\MIME;

return [
    'default' => [
            'charset'=>'1234567890AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz',
            'useCurve'=>false,      //混淆曲线
            'useNoise'=>false,      //随机噪点
            'useFont'=>null,        //指定字体
            'fontColor'=>null,      //字体颜色
            'backColor'=>null,      //背景颜色
            'imageL'=>null,         //图片宽度
            'imageH'=>null,         //图片高度
            'fonts'=>[],            //额外字体
            'fontSize'=>25,         //字体大小
            'length'=>4,            //生成位数
            'mime'=>MIME::PNG,      //设置类型
            'temp'=>'/tmp',        //设置缓存目录
    ],
];
