<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Code;
use App\Vendor\Encrypt;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Contract\SessionInterface;
use App\Model\Menu;
use App\Service\Base;
use App\Helper\Helper;

class IsLoginMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var Encrypt
     */
    protected $encrypt;

    /**
     * @var Base
     */
    protected $base;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var SessionInterface
     */
    protected $session;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request,Encrypt $encrypt,Base $base,SessionInterface $session)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        $this->encrypt = $encrypt;
        $this->base = $base;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookie = $this->request->getCookieParams()['uuid'] ?? false;
        if(!$cookie){
            $method = strtolower($this->request->getMethod());
            if($method=='get'){
                return $this->response->redirect('/admin/login/index');
            }
            return $this->response->json($this->helper->getError());

        }else{
            $uuid = $this->session->get('uuid');
            if(!$uuid && $cookie){
                $data = $this->encrypt->decrypt($cookie);
                $this->session->set('uuid',$data);
            }
        }

        $allAuth = $this->base->getAdminAuth();
        $path = explode('/',trim($this->request->getUri()->getPath(),'/'));
        $path_arr = array_slice($path,0,3);
        $url = '/'.implode('/',$path_arr);
        $where[]=['url','=',$url];
        $where[]=['url','!=',''];
        $result = Menu::query()->where($where)->first();

        if(!$result || !in_array($result->id,$allAuth)){
            throw new \InvalidArgumentException('没有权限',Code::UNAUTH);
        }

        return $handler->handle($request);
    }
}

