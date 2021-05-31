<?php


namespace App\Controller\Admin;

use App\Model\AdminSet;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use App\Model\Menu;
use App\Service\Base;

/**
 * @Controller()
 */
class InitController extends AbstractController
{
    /**
     * @RequestMapping(path="/admin/init/list", methods="get")
     * @Middleware(IsLoginMiddleware::class)
     */
    public function list(Base $base,Menu $menus)
    {
        $allAuth = array_unique($base->getAdminAuth());

        $menu =$menus->getLevel();
        $k1=0;
        $k2=0;
        $k3=0;
        $list=array();
        foreach ($menu as $k=>$v) {
            if($v->status===1 && in_array($v->id,$allAuth)){
                $list[$k1]['title'] = $v->name;
                $list[$k1]['icon'] = $v->icon;
                $list[$k1]['href'] = "";
                $list[$k1]['target'] = "_self";
                foreach ($v->child as $ik => $item) {
                    if($item->status===1 && in_array($item->id,$allAuth)){
                        $list[$k1]['child'][$k2]['title'] = $item->name;
                        $list[$k1]['child'][$k2]['icon'] = $item->icon;
                        $list[$k1]['child'][$k2]['href'] = '';
                        $list[$k1]['child'][$k2]['target'] = '_self';
                        foreach ($item->child as $vk => $val) {
                            if($val->status===1 && in_array($val->id,$allAuth)) {
                                $list[$k1]['child'][$k2]['child'][$k3]['title'] = $val->name;
                                $list[$k1]['child'][$k2]['child'][$k3]['icon'] = $val->icon;
                                $list[$k1]['child'][$k2]['child'][$k3]['href'] = $val->url;
                                $list[$k1]['child'][$k2]['child'][$k3]['target'] = $val->_self;
                                $k3++;
                            }
                        }
                        $k2++;
                    }
                }
                $k1++;
                $k2=0;
                $k3=0;
            }
        }
        $set = AdminSet::query()->where('key','=','logo')->first();

        $init = array(
            'homeInfo'=>[
                'title'=>'首页',
                'href'=>'/aaaa'
            ],
            'logoInfo'=>[
                'title'=>'管理系统',
                'image'=>$set->val ?? '',
                'href'=>'/admin/index/layout'
            ],
            'menuInfo'=>$list
        );
        return $this->helper->getSuccess($init);


    }

}
