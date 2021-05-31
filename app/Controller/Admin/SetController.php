<?php


namespace App\Controller\Admin;

use App\Model\AdminSet;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;

/**
 * @Controller()
 */
class SetController extends AbstractController
{
    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/set/index", methods="get")
     */
    public function index(AdminSet $adminSet)
    {
        $list = $adminSet->getAll();
        return $this->base->view('admin.set.index',['list'=>$list]);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/set/handler", methods="post")
     */
    public function handler()
    {
       $data = $this->request->all();
       foreach ($data as $k=>$v){
            $set = AdminSet::query()->where('key','=',$k)->first();
            if($set){
                $set->val=$v;
                $set->save();
            }
       }
       return $this->helper->getSuccess();
    }
}