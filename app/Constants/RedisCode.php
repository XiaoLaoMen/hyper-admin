<?php


namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * redis中key
 * Class RedisCode
 * @package App\Constants
 */
class RedisCode extends AbstractConstants
{
    /**
     * @Message("后台登录验证码")
     */
    const ADMIN_LOGIN_CAPTCHA = 'admin:login:captcha:';
}
