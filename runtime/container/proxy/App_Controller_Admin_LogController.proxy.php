<?php

namespace App\Controller\Admin;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use App\Model\AdminLoginLog;
/**
 * @Controller()
 */
class LogController extends AbstractController
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct(\Hyperf\HttpServer\Contract\RequestInterface $request, \Hyperf\HttpServer\Contract\ResponseInterface $response, \App\Service\Base $base, \Hyperf\Contract\SessionInterface $session, \App\Helper\Helper $helper)
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct(...func_get_args());
        }
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var AdminLoginLog
     */
    protected $log;
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/log/index", methods="get")
     */
    public function index()
    {
        return $this->base->view('admin.log.index');
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/log/list", methods="get")
     */
    public function list()
    {
        $page = $this->request->input('page', '1');
        $limit = $this->request->input('limit', '15');
        $list = $this->log->getPaginatorAndCount($page, $limit);
        return $this->helper->getSuccess($list);
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/log/del", methods="post")
     */
    public function del()
    {
        $id = $this->request->input('id', null);
        if (!$id) {
            return $this->helper->getError();
        }
        AdminLoginLog::destroy($id);
        return $this->helper->getSuccess();
    }
}