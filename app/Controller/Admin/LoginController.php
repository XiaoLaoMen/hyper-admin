<?php

namespace App\Controller\Admin;

use App\Constants\Code;
use App\Constants\RedisCode;
use App\Model\Admin;
use App\Vendor\Redis;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use App\Vendor\Code\Annotation\CodeAnnotation;
use App\Vendor\Code\VerifyCode;
use Hyperf\HttpMessage\Cookie\Cookie;
use App\Vendor\Encrypt;
use App\Request\Admin\LoginRequest;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Event\AdminLogin;
use Hyperf\Di\Annotation\Inject;

/**
 * @Controller()
 */
class LoginController extends AbstractController
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @RequestMapping(path="/admin/login/index", methods="get")
     */
    public function index(Encrypt $encrypt)
    {
        $cookie = $this->request->cookie('uuid', null);
        var_dump($cookie);
        if($cookie){
            $cookie = $this->request->getCookieParams()['uuid'];
            $uuid = $encrypt->decrypt($cookie);
            $this->session->set('uuid',$uuid);
            return $this->response->redirect('/admin/index/layout');
        }
        return $this->base->view('admin.login.index');
    }

    /**
     * @RequestMapping(path="/admin/login/captcha", methods="get")
     * @CodeAnnotation()
     */
    public function captcha(VerifyCode $code)
    {
        $image = $code->DrawCode();
        $val = $image->getImageCode();

        $str = uniqid();

        $cookie = new Cookie('captcha',$str,time()+5*60);

        Redis::set(RedisCode::ADMIN_LOGIN_CAPTCHA.$str,$val,300);

        return $this->response
            ->withCookie($cookie)
            ->withAddedHeader('content-type', 'image/png')
            ->withContent($image->getImageByte());
    }

    /**
     * @RequestMapping(path="/admin/login/index", methods="post")
     */
    public function login(LoginRequest $request,Encrypt $encrypt,Admin $admin)
    {
        $email = $request->input('emailormob');
        $passwd = $request->input('password');
        $newCap = strtoupper($request->input('captcha'));
        $remember = $request->input('remember','off');

        //验证码验证
        $captcha = $request->cookie('captcha');

        if(!$captcha){
            return $this->helper->getError('验证码错误',Code::UNAUTH);
        }

        $cookie = Redis::get(RedisCode::ADMIN_LOGIN_CAPTCHA.$captcha);

        Redis::del(RedisCode::ADMIN_LOGIN_CAPTCHA.$captcha);

        if(!$cookie || strtolower($cookie) != strtolower($newCap)){
            return $this->helper->getError('验证码错误',Code::UNAUTH);
        }


        //邮箱
        $where[]=['email','=',$email];
        $where[]=['status','=','1'];
        $result = $admin->getOne($where);

        if(!$result || !password_verify($passwd, $result->password)){
            return $this->helper->getError('用户不存在或已禁用');
        }

        //验证通过,记住账号
        $time=0;
        if('on' === strtolower($remember)){
            $time = time()+7*24*60*60;
        }

        $cookie = new Cookie('uuid',$encrypt->encrypt($result->id),$time);
        $this->session->set('uuid',$result->id);

        $this->eventDispatcher->dispatch(new AdminLogin($result->id));

        return $this->response
            ->withCookie($cookie)
            ->withContent(json_encode($this->helper->getSuccess([],'登录成功,正在跳转请稍后...')));
    }

    /**
     * @RequestMapping(path="/admin/login/signout", methods="post")
     */
    public function signout()
    {
        $this->session->clear();
        $cookie = new Cookie('uuid','',time()-1);
        return $this->response
            ->withCookie($cookie)
            ->withContent(json_encode($this->helper->getSuccess([],'退出成功,正在跳转请稍后...')));

    }
}
