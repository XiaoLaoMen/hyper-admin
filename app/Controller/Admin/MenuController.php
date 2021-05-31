<?php


namespace App\Controller\Admin;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use App\Model\Menu;
use App\Request\Admin\MenuRequest;

/**
 * @Controller()
 */
class MenuController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/menu/index", methods="get")
     */
    public function index()
    {
        return $this->base->view('admin.menu.index');
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/menu/list", methods="get")
     */
    public function list(Menu $menu)
    {
        $list = $menu->getAll();
        return $this->helper->getSuccess($list);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/menu/page/{id}", methods="get")
     */
    public function page($id,Menu $menu)
    {
        $info=array();
        if('0' != $id){
            $info=Menu::query()->find($id);
        }
        $list =$menu->getLevel();
        return $this->base->view('admin.menu.page',['list'=>$list,'info'=>$info]);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/menu/handler", methods="post")
     */
    public function handler(MenuRequest $request,Menu $menus)
    {
        $sta = $request->input('status','0');
        $status = $sta=='on' ? '1' : '0';

        $id = $request->input('id');
        $pid = $request->input('pid');
        $sort = $request->input('sort');

        $default = $request->input('is_default','0');
        $is_default = $default=='on' ? '1' : '0';

        if('0' == $id){
            $menu = new Menu();
        }else{
            $where[]=['id','=',$id];
            $menu = $menus->getOne($where);
            if($pid==$id){
                return $this->helper->getError('上级不能为自己');
            }
        }
        $menu->pid = $pid;
        $menu->name = $request->input('name');
        $menu->url = $request->input('url');
        $menu->icon = $request->input('icon');
        $menu->status = $status;
        $menu->sort = $sort;
        $menu->is_default = $is_default;
        $menu->save();

        return $this->helper->getSuccess();
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/menu/del", methods="post")
     */
    public function del(Menu $menu)
    {
        $id = $this->request->input('id',null);

        if(!$id){
            return $this->helper->getError();
        }
        $where[]=['pid','=',$id];
        $child = $menu->getOne($where);
        if($child){
            return $this->helper->getError('存在子级不能删除');
        }

        Menu::destroy($id);
        return $this->helper->getSuccess();

    }

};