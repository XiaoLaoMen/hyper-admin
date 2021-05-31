<?php

namespace App\Controller\Admin;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use App\Model\Role;
use App\Model\RoleAuth;
use App\Model\Menu;
/**
 * @Controller()
 */
class RoleController extends AbstractController
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
     * @var Role
     */
    protected $role;
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/index", methods="get")
     */
    public function index()
    {
        return $this->base->view('admin.role.index');
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/list", methods="get")
     */
    public function list()
    {
        $where = array();
        $role_name = $this->request->input('role_name', false);
        if ($role_name) {
            $where[] = ['role_name', 'like', '%' . $role_name . '%'];
        }
        $page = $this->request->input('page', '1');
        $limit = $this->request->input('limit', '15');
        $list = $this->role->getPaginatorAndCount($page, $limit, $where);
        return $this->helper->getSuccess($list);
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/page/{id}", methods="get")
     */
    public function page($id)
    {
        $info = array();
        if ('0' != $id) {
            $info = Role::query()->find($id);
        }
        return $this->base->view('admin.role.page', ['info' => $info]);
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/handler", methods="post")
     */
    public function handler()
    {
        $id = $this->request->input('id');
        if ('0' == $id) {
            $role = new Role();
        } else {
            $role = Role::query()->find($id);
        }
        $role->role_name = $this->request->input('role_name', '');
        $role->save();
        return $this->helper->getSuccess();
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/del", methods="post")
     */
    public function del()
    {
        $id = $this->request->input('id', null);
        if (!$id) {
            return $this->helper->getError();
        }
        Role::destroy($id);
        return $this->helper->getSuccess();
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/auth/{id}", methods="get")
     */
    public function auth($id)
    {
        return $this->base->view('admin.role.auth', ['id' => $id]);
    }
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/role/getauth/{id}", methods="get")
     */
    public function getAuth($id, Menu $menus)
    {
        $auths = RoleAuth::query()->where('role_id', '=', $id)->get();
        $auth = array();
        foreach ($auths as $v) {
            $auth[] = (string) $v->menu_id;
        }
        $menu = $menus->getLevel();
        $list = array();
        foreach ($menu as $k => $v) {
            $list[$k]['id'] = $v->id;
            $list[$k]['label'] = $v->name;
            foreach ($v->child as $ik => $item) {
                $list[$k]['children'][$ik]['id'] = $item->id;
                $list[$k]['children'][$ik]['label'] = $item->name;
                foreach ($item->child as $vk => $val) {
                    $list[$k]['children'][$ik]['children'][$vk]['id'] = $val->id;
                    $list[$k]['children'][$ik]['children'][$vk]['label'] = $val->name;
                    if (in_array($val->id, $auth)) {
                        $list[$k]['children'][$ik]['children'][$vk]['checked'] = 'checked';
                    }
                    foreach ($val->child as $vkl => $value) {
                        $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['id'] = $value->id;
                        $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['label'] = $value->name;
                        if (in_array($value->id, $auth)) {
                            $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['checked'] = 'checked';
                        }
                    }
                }
            }
        }
        return $this->helper->getSuccess($list);
    }
}