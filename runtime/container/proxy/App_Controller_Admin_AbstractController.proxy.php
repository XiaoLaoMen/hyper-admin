<?php

declare (strict_types=1);
namespace App\Controller\Admin;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use App\Service\Base;
use Hyperf\Contract\SessionInterface;
use App\Helper\Helper;
abstract class AbstractController
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @Inject
     * @var Base
     */
    protected $base;
    /**
     * @Inject
     * @var SessionInterface
     */
    protected $session;
    /**
     * @Inject
     * @var Helper
     */
    protected $helper;
    public function __construct(RequestInterface $request, ResponseInterface $response, Base $base, SessionInterface $session, Helper $helper)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->request = $request;
        $this->response = $response;
        $this->session = $session;
        $this->base = $base;
        $this->helper = $helper;
    }
}