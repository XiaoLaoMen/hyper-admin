<?php


namespace App\Controller\Admin;

use App\Model\Admin\Menu;
use App\Model\Core\TemplateSet;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;

/**
 * @Controller()
 */
class InitController extends AbstractController
{
    /**
     * @RequestMapping(path="/admin/init/list", methods="get")
     * @Middleware(IsLoginMiddleware::class)
     */
    public function list()
    {
        $allAuth = $this->helper->getAdminAuth();

        $menu = Menu::with('child')
            ->where('status','1')
            ->where('pid','0')
            ->whereIn('id',$allAuth)
            ->get();
        $list=array();
        foreach ($menu as $k=>$v){
            $list[$k]['title']=$v->name;
            $list[$k]['icon']=$v->icon;
            $list[$k]['href']="";
            $list[$k]['target']="_self";
            foreach ($v->child as $ik=>$item){
                $list[$k]['child'][$ik]['title']=$item->name;
                $list[$k]['child'][$ik]['icon']=$item->icon;
                $list[$k]['child'][$ik]['href']='';
                $list[$k]['child'][$ik]['target']='_self';
                foreach ($item->child as $vk=>$val){
                    $list[$k]['child'][$ik]['child'][$vk]['title']=$val->name;
                    $list[$k]['child'][$ik]['child'][$vk]['icon']=$val->icon;
                    $list[$k]['child'][$ik]['child'][$vk]['href']=$val->url;
                    $list[$k]['child'][$ik]['child'][$vk]['target']=$val->_self;
                }
            }
        }
        $setList = TemplateSet::query()->where('key','=','basic')->first();
        $set = json_decode($setList->val);

        $init = array(
            'homeInfo'=>[
                'title'=>'首页',
                'href'=>'page/welcome-1.html?t=1'
            ],
            'logoInfo'=>[
                'title'=>'',
                'image'=>$set->logo,
                'href'=>''
            ],
            'menuInfo'=>$list
        );
        return $this->helper->getSuccess($init);


    }

}
