<?php


namespace App\Controller\Admin;

use App\Model\AdminSet;
use App\Model\Menu;
use App\Model\RoleAuth;
use App\Model\AdminAuth;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use App\Constants\Code;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;

/**
 * @Controller()
 */
class AuthController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/auth/role", methods="post")
     *
     */
    public function role()
    {
        $ids = $this->request->input('ids');
        $ids = $this->defaultAuth($ids);
        $id = $this->request->input('id');
        $where[]=['role_id','=',$id];
        RoleAuth::where($where)->delete();
        foreach ($ids as $v){
            $auth = new RoleAuth();
            $auth->role_id=$id;
            $auth->menu_id=$v;
            $auth->save();
        }

        return $this->helper->getSuccess();

    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/auth/admin", methods="post")
     *
     */
    public function admin()
    {
        $ids = $this->request->input('ids');
        $ids = $this->defaultAuth($ids);
        $id = $this->request->input('id');
        $where[]=['admin_id','=',$id];
        AdminAuth::where($where)->delete();
        foreach ($ids as $v){
            $auth = new AdminAuth();
            $auth->admin_id=$id;
            $auth->menu_id=$v;
            $auth->save();
        }

        return $this->helper->getSuccess();

    }

    protected function defaultAuth($ids)
    {
        $list = Menu::query()->where('is_default','=','1')->get();
        foreach ($list as $v){
            $ids[]=$v->id;
        }

        return array_unique($ids);
    }
}