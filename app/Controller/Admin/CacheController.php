<?php


namespace App\Controller\Admin;

use App\Vendor\Redis;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;

/**
 * @Controller()
 */
class CacheController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/cache/clear", methods="get")
     */
    public function list()
    {
        Redis::flushdb();
        return $this->helper->getSuccess();
    }
}
