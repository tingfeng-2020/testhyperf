<?php


namespace App\Listener;


use App\Event\LoginRegistered;
use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * 通过注解注册监听
 * @Listener
 */
class UserRegisteredListener implements ListenerInterface
{

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        // TODO: Implement listen() method.
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            UserRegistered::class,
            LoginRegistered::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event)
    {
        // TODO: Implement process() method.
        // 事件触发后该监听器要执行的代码写在这里，比如该示例下的发送用户注册成功短信等
        // 直接访问 $event 的 user 属性获得事件触发时传递的参数值
        // $event->user;
        echo "<pre>";
        print_r($event);
        echo "</pre>";
        echo '注册成功';
        echo '发送短信';
        echo '通知邮件';
//        return 345435;
    }
}