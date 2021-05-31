<?php

namespace App\Service;

use App\Model\AdminSet;
use function Hyperf\ViewEngine\view;
use App\Model\Menu;
use App\Model\Admin;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use App\Model\RoleAuth;
use App\Model\AdminAuth;
use App\Model\Role;
class Base
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var Admin
     */
    protected $admin;
    /**
     * @Inject
     * @var SessionInterface
     */
    protected $session;
    protected function getUrlParent()
    {
        $list = Menu::with('child')->where('pid', '0')->orderBy('sort', 'desc')->get();
        $array = array();
        foreach ($list as $v) {
            foreach ($v->child as $item) {
                foreach ($item->child as $val) {
                    if (isset($val->child)) {
                        foreach ($val->child as $value) {
                            $str = $v->name . '/' . $item->name . '/' . $val->name . '/' . $value->name;
                            $array[$value->url] = $str;
                        }
                    }
                }
            }
        }
        return $array;
    }
    public function getAdminInfo()
    {
        $id = $this->session->get('uuid');
        $where[] = ['id', '=', $id];
        return $this->admin->getOne($where);
    }
    public function getAdminAuth()
    {
        $id = $this->session->get('uuid');
        $auth = array();
        if ($id == '1') {
            $list = Menu::query()->get();
            foreach ($list as $v) {
                $auth[] = $v->id;
            }
        } else {
            $info = Admin::query()->find($id);
            $userWhere[] = ['admin_id', '=', $id];
            $userAuth = AdminAuth::query()->where($userWhere)->get();
            //获取角色
            $ids = explode(',', $info->role_id);
            $role = Role::query()->whereIn('id', $ids)->get();
            $role_ids = array();
            foreach ($role as $v) {
                $role_ids[] = $v->id;
            }
            $roleAuth = RoleAuth::query()->whereIn('role_id', $role_ids)->get();
            foreach ($userAuth as $v) {
                $auth[] = $v->menu_id;
            }
            foreach ($roleAuth as $v) {
                $auth[] = $v->menu_id;
            }
        }
        return $auth;
    }
    public function view($view = null, $data = [], $mergeData = [])
    {
        $icon = AdminSet::query()->where('key', '=', 'icon')->first();
        $par = $this->getUrlParent();
        $data['admin_menu_url'] = $par;
        $data['admin_info'] = $this->getAdminInfo();
        $data['admin_set_icon'] = $icon->val ?? '';
        return view($view, $data, $mergeData);
    }
}