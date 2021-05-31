<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;

/**
 * @Controller()
 */
class IndexController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/index/layout", methods="get")
     */
    public function layout()
    {
        return $this->base->view('admin.layout.index');
    }
}
