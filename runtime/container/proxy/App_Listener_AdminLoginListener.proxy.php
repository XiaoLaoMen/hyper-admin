<?php

namespace App\Listener;

use App\Event\AdminLogin;
use App\Model\AdminLoginLog;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Di\Annotation\Inject;
use itbdw\Ip\IpLocation;
/**
 * @Listener
 */
class AdminLoginListener implements ListenerInterface
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;
    public function listen() : array
    {
        return [AdminLogin::class];
    }
    /**
     * @param AdminLogin $event
     */
    public function process(object $event)
    {
        $id = $event->adminId;
        $ip = $this->request->getHeader('x-forwarded-for')['0'] ?? '';
        $qqwry_filepath = BASE_PATH . '/public/qqwry.dat';
        $ipInfo = IpLocation::getLocation($ip, $qqwry_filepath);
        $log = new AdminLoginLog();
        $log->admin_id = $id;
        $log->ip = $ip;
        $log->addr = $ipInfo['province'] . '--' . $ipInfo['city'] . '--' . $ipInfo['county'] . '--' . $ipInfo['area'];
        $log->save();
    }
}