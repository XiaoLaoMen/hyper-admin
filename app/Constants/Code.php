<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class Code extends AbstractConstants
{

    /**
     * @Message("ok")
     */
    const SUCCESS = 0;

    /**
     * @Message("Bad Request")
     */
    const BAD_REQUEST = 400;

    /**
     * @Message("Unauthorized")
     */
    const UNAUTH = 401;


    /**
     * @Message("NOT FOUND")
     */
    const NOT_FOUND = 404;

    /**
     * @Message("Internal Server Error")
     */
    const SERVER_ERROR = 500;

}
