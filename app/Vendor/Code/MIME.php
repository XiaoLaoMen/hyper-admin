<?php


namespace App\Vendor\Code;

class MIME
{
    const JPG = 'image/jpeg';
    const PNG = 'image/png';
    const GIF = 'image/gif';

    /**
     * 获取对应Mime类型的后缀名称
     * @param $Mime
     * @return mixed
     */
    static function getExtensionName($Mime)
    {
        $extension = [MIME::JPG => 'jpeg', MIME::PNG => 'png', MIME::GIF => 'gif'];
        return $extension[$Mime];
    }
}
