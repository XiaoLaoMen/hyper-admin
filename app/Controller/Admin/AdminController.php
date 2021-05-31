<?php


namespace App\Controller\Admin;

use App\Model\Menu;
use App\Model\RoleAuth;
use App\Model\AdminAuth;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\IsLoginMiddleware;
use App\Model\Admin;
use App\Model\Role;

/**
 * @Controller()
 */
class AdminController  extends AbstractController
{
    /**
     * @Inject
     * @var Admin
     */
    protected $admin;

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/index", methods="get")
     */
    public function index()
    {
        return $this->base->view('admin.admin.index');
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/list", methods="get")
     */
    public function list()
    {
        $page = $this->request->input('page','1');
        $limit = $this->request->input('limit','15');
        $list = $this->admin->getPaginatorAndCount($page,$limit);
        return $this->helper->getSuccess($list);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/page/{id}", methods="get")
     */
    public function page($id)
    {
        $info=array();
        $names='';
        if('0' != $id){
            $info=Admin::query()->find($id);
            if($info){
                $ids = explode(',',$info->role_id);
                $role_list = Role::query()->whereIn('id',$ids)->get();
                $new_arr=array();
                foreach ($role_list as $item) {
                    $new_arr[]=$item->role_name;
                }
                $names = implode(',',$new_arr);
            }
        }

        return $this->base->view('admin.admin.page',['info'=>$info,'names'=>$names]);
    }


    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/handler", methods="post")
     */
    public function handler()
    {
        $id = $this->request->input('id');
        $name = $this->request->input('name','');
        $email = $this->request->input('email',null);
        $role_id = $this->request->input('roles','');
        $passwd = $this->request->input('passwd',null);
        $passwd_confirmation = $this->request->input('passwd_confirmation',null);

        if(!$email){
            return $this->helper->getError('邮箱必须存在一');
        }

        if($passwd !== $passwd_confirmation){
            return $this->helper->getError('两次密码不一致,请重新输入');
        }

        $emailExist = Admin::query()->where('email',$email)->first();

        if('0' == $id){

            if($emailExist){
                return $this->helper->getError('邮箱已经存在');
            }

            if(!$passwd || '' == $passwd){
                return $this->helper->getError('密码不能为空');
            }

            $admin = new Admin();
            $admin->password=password_hash($passwd,PASSWORD_DEFAULT);
        }else{

            $admin = Admin::query()->find($id);

            if($emailExist){
                if($id != $emailExist->id){
                    return $this->helper->getError('邮箱已经存在');
                }
            }

            if($passwd && '' != $passwd){
                $admin->password=password_hash($passwd,PASSWORD_DEFAULT);
            }
        }

        $admin->name = $name;
        $admin->email = $email;
        $admin->role_id = $role_id;
        $admin->status=1;
        $admin->save();

        return $this->helper->getSuccess();
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/del", methods="post")
     */
    public function del()
    {
        $id = $this->request->input('id',null);

        if(!$id){
            return $this->helper->getError();
        }

        Admin::destroy($id);
        return $this->helper->getSuccess();

    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/login", methods="post")
     */
    public function login()
    {
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        $admin = Admin::query()->find($id);
        $admin->status=$status;
        $admin->save();

        return $this->helper->getSuccess();
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/auth/{id}", methods="get")
     */
    public function auth($id)
    {
        return $this->base->view('admin.admin.auth',['id'=>$id]);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/getauth/{id}", methods="get")
     */
    public function getAuth($id,Menu $menus)
    {

        $auths = AdminAuth::query()
            ->where('admin_id','=',$id)
            ->get();
        $auth=array();
        foreach ($auths as $v){
            $auth[]=(string)$v->menu_id;
        }

        $menu = $menus->getLevel();
        $list=array();
        foreach ($menu as $k=>$v){
            $list[$k]['id']=$v->id;
            $list[$k]['label']=$v->name;
            foreach ($v->child as $ik=>$item){
                $list[$k]['children'][$ik]['id']=$item->id;
                $list[$k]['children'][$ik]['label']=$item->name;
                foreach ($item->child as $vk=>$val){
                    $list[$k]['children'][$ik]['children'][$vk]['id']=$val->id;
                    $list[$k]['children'][$ik]['children'][$vk]['label']=$val->name;
                    if(in_array($val->id,$auth)){
                        $list[$k]['children'][$ik]['children'][$vk]['checked']='checked';
                    }
                    foreach ($val->child as $vkl=>$value){
                        $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['id']=$value->id;
                        $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['label']=$value->name;
                        if(in_array($value->id,$auth)){
                            $list[$k]['children'][$ik]['children'][$vk]['children'][$vkl]['checked']='checked';
                        }
                    }
                }
            }
        }

        return $this->helper->getSuccess($list);
    }

    /**
     * @Middleware(IsLoginMiddleware::class)
     * @RequestMapping(path="/admin/admin/info", methods="get,post")
     */
    public function info()
    {
        $method = $this->request->getMethod();
        $id = $this->session->get('uuid');

        $info = Admin::query()->find($id);

        if('GET' == strtoupper($method)){
            return $this->base->view('admin.admin.info',['info'=>$info]);
        }

        $name = $this->request->input('name','');
        $email = $this->request->input('email',null);
        $passwd = $this->request->input('passwd',null);
        $passwd_confirmation = $this->request->input('passwd_confirmation',null);

        if(!$email){
            return $this->helper->getError('邮箱必须存在');
        }

        if($passwd !== $passwd_confirmation){
            return $this->helper->getError('两次密码不一致,请重新输入');
        }

        $emailExist = Admin::query()->where('email',$email)->first();


        if($emailExist){
            if($id != $emailExist->id){
                return $this->helper->getError('邮箱已经存在');
            }
        }

        if($passwd && '' != $passwd){
            $info->password=password_hash($passwd,PASSWORD_DEFAULT);
        }


        $info->name =$name;
        $info->email = $email;
        $info->save();

        return $this->helper->getSuccess();
    }
}
