<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Aspect\FooAspect;
use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Exception\BusinessException;
use App\Exception\FooException;
use App\Service\DemoService;
use App\Service\UserService;
use App\Service\UserServiceEvent;
use App\Service\UserServiceFactory;
use App\Service\UserServiceInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Parallel;
use Phper666\JWTAuth\JWT;


class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var UserServiceInterface
     */
    private $userService;
    public function index()
    {
//        go();
//        co();

        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
//        defer();
//        new Parallel();
    }

    public function writeGroup()
    {
//        $wg = new \Hyperf\Utils\WaitGroup();
//        // 计数器加二
//        $wg->add(2);
//        // 创建协程 A
//        co(function () use ($wg) {
//        // some code
//        // 计数器减一
//        $wg->done();
//        });
//        // 创建协程 B
//        co(function () use ($wg) {
//        // some code
//        // 计数器减一
//        $wg->done();
//        });
//        // 等待协程 A 和协程 B 运行完成
//         $wg->wait();

        // 传递的数组参数您也可以带上 key 便于区分子协程，返回的结果也会根据 key 返回对应的结果
//        $result = parallel([
//            function () {
//                sleep(1);
//                return Coroutine::id();
//            },
//            function () {
//                sleep(1);
//                return Coroutine::id();
//            }
//        ]);

        $parallel = new Parallel(5);
        for ($i = 0; $i < 20; $i++) {
            $parallel->add(function () {
                sleep(1);
                return Coroutine::id();
            });
        }

        try{
            $results = $parallel->wait();
        } catch(ParallelExecutionException $e){
            // $e->getResults() 获取协程中的返回值。
            // $e->getThrowables() 获取协程中出现的异常。
        }
        return $results;
    }

    public function parallel(){
        return __METHOD__.'121113';
//        $parallel = new Parallel();
//        $parallel->add(function () {
//            sleep(2);
//            return Coroutine::id();
//        });
//        $parallel->add(function () {
//            sleep(2);
//            return Coroutine::id();
//        });
//
//        try {
//            // $results 结果为 [1, 2]
//            $results = $parallel->wait();
//        } catch (ParallelExecutionException $e) {
//            // $e->getResults() 获取协程中的返回值。
//            // $e->getThrowables() 获取协程中出现的异常。
//        }
//        return $results;

    }

    /**
     * 注解抽象对象实现依赖注入 DI
     * @return mixed
     */
    public function get(){
        $id = 2;
        // 直接使用
        return $this->userService->getInfoById($id);
    }

    public function getFactory(){
        $userServer = $this->userService;
        $id = 2;
        // 直接使用
       return  make(UserServiceInterface::class)();
        return $userServer;
    }

    public function getEvent()
    {
        $res = make(UserServiceEvent::class)->register();
        return $res;
    }

    public function getAspect()
    {
        return make(FooAspect::class)->process();
    }

    public function getData()
    {
        return $this->response->json(['code' => 0, 'msg' => 'success', 'data' => ['a' => 1]]);
    }

    # 模拟登录,获取token
    public function login(JWT $jwt)
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        if ($username && $password) {
            //这里应为没有做auth的登录认证系统，为了展示随便写点数据
            $userData = [
                'uid' => 1,
                'username' => 'xx',
            ];
            //获取Token
            $token = (string)$jwt->getToken($userData);
            //返回响应的json数据
            return $this->response->json(['code' => 0, 'msg' => '获取token成功', 'data' => ['token' => $token]]);
        }

        return $this->response->json(['code' => 0, 'msg' => '登录失败', 'data' => []]);
    }

    public function getTtl(JWT $jwt)
    {

    }

    public function getLog(DemoService $demoService)
    {
        $res = $demoService->method();
        return $res;
    }

    public function exception()
    {
//        return 'ok';
        throw new ApiException(1000);
//        throw new BusinessException(200);
    }
}
