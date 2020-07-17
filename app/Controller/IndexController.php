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
use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use App\Request\FooRequest;
use App\Service\DemoService;
use App\Service\UserService;
use App\Service\UserServiceEvent;
use App\Service\UserServiceFactory;
use App\Service\UserServiceInterface;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Parallel;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
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


    /**
     * 获取分页
     * @param RequestInterface $request
     * @return Paginator
     */
    public function getPage()
    {
        $currentPage = (int) $this->request->input('page', 1);
        $perPage = (int) $this->request->input('per_page', 1);

        // 这里根据 $currentPage 和 $perPage 进行数据查询，以下使用 Collection 代替
        $collection = new Collection([
            ['id' => 1, 'name' => 'Tom'],
            ['id' => 2, 'name' => 'Sam'],
            ['id' => 3, 'name' => 'Tim'],
            ['id' => 4, 'name' => 'Joe'],
        ]);

        $users = array_values($collection->forPage($currentPage, $perPage)->toArray());

        $paginator = new Paginator($users, $perPage, $currentPage);

        return $paginator->total();
    }

    /**
     * @Inject()
     * @var TranslatorInterface
     */
    private $translator;
    public function translator()
    {
        return trans('error.1000',['name'=>'yes']);
        return $this->translator->trans('error.1000', [], 'zh_CN');
    }

    /**
     * 验证器
     * @param FooRequest $request
     * @return array
     */
    public function validate(FooRequest $request)
    {
        // 传入的请求通过验证...
//throw new ApiException(401);
        // 获取通过验证的数据...
        $validated = $request->messages();
        return $validated;
    }

    /**
     * 手动注册验证器
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    public function validateSecond(RequestInterface $request)
    {
        $validator = $this->validationFactory->make(
            $request->all(),
            [
                'foo' => 'required',
                'bar' => 'required',
            ],
            [
                'foo.required' => ':attribute 是必须的～',
                'bar.required'  => 'bar is required',
            ],
            [
                'foo' => 'foo:',
            ]
        );

        //验证后的钩子
        $validator->after(function ($validator) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
        });

        if ($validator->fails()){
            // Handle exception
//            $errorMessage = $validator->errors()->first();
            $errorMessage = $validator->errors()->all();
            return $errorMessage;
        }
        // Do something
    }

    public function seeds()
    {
        //创建一个角色
//        $role = Role::create(['name' => '管理员','description'=>'']);
        //创建权限
//        $permission = Permission::create(['name' => 'user-center/user/get','display_name'=>'用户管理','url'=>'user-center/user']);
//        $permission = Permission::create(['name' => 'user-center/user/post','display_name'=>'创建用户','parent_id'=>2]);
        //为角色分配一个权限
//        $role->givePermissionTo($permission);
//        $role->syncPermissions($permissions);//多个
//        $role->syncPermissions([1]);
        //权限添加到一个角色
//        $permission->assignRole($role);
//        $permission->syncRoles($roles);//多个
//        $permission->syncRoles([1]);
          //删除权限
//        $role->revokePermissionTo($permission);
//        $permission->removeRole($role);
        //为用户直接分配权限
//        $user = new User();
//         $user->save([
//            'username' => 'admin',
//            'password' => '123456',
//            'nick_name' => '超级管理员',
//            'real_name' => '超级管理员'
//        ]);
        $user = User::query()->where('user_id',1)->first();
//        return $user->givePermissionTo('user-center/user/get','user-center/user/post');
////为用户分配角色
//        return $user->assignRole('管理员');
//        return $user->assignRole(2);
//        $user->assignRole($role);
//        $user->syncRoles(['管理员', '普通用户']);
//        $user->syncRoles([1,2,3]);
////删除角色
//        $user->removeRole('产品');
//        $user->removeRole(2);
////获取用户集合
//        $permission->users;
//        $role->users;
////获取角色集合
//        return $user->getRoleNames();
//        $permission->roles;
////获取所有权限
//       return $user->getAllPermissions();
//        $role->permissions;
////获取树形菜单
//       return $user->getMenu();
////验证
//       return $user->can('user-center/user/gets');
//        $user->can($permission->id);
//        $user->can($permission);
//        $user->hasAnyPermission([$permission1,$permission2]);
//     return   $user->hasAnyPermission(['user-center/user/get','user-center/user/post']);
//        $user->hasAnyPermission([1,2]);
//        $user->hasRole('管理员');
//        $user->hasRole(['管理员','普通用户']);
//        $user->hasRole($role);
//        $user->hasRole([$role1,$role2]);
    }
}
