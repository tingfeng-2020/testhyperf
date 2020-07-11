<?php


namespace App\Service;


use App\Event\LoginRegistered;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Event\UserRegistered;

class UserServiceEvent
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * 注册服务
     * @return string
     */
    public  function register()
    {
        // 我们假设存在 User 这个实体
        $user = ['test'=>123];

        // 完成账号注册的逻辑
        // 这里 dispatch(object $event) 会逐个运行监听该事件的监听器
        $this->eventDispatcher->dispatch(new UserRegistered($user),new LoginRegistered($user));
        return 'ok';
    }
}