<?php

declare(strict_types=1);
/**
 * 路由权限表 permissions => name字段关联路由验证：验证规则（url+请求方法 Method）url 字段：路由访问url
 * 赋予权限：1.通过默认的权限接口方法增删权限，2.通过脚本自动添加...dev
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

$middleware = [
    App\Middleware\JwtAuthMiddleware::class,
    App\Middleware\PermissionMiddleware::class,
];


# 登录
Router::post('/login', 'App\Controller\Admin\UserController@login');

# jwt登陆后才可以访问
Router::addGroup('/v1', function () {
    Router::get('/refresh-token', 'App\Controller\Admin\UserController@refreshToken');
    Router::delete('/logout', 'App\Controller\Admin\UserController@logout');
    Router::get('/data', 'App\Controller\Admin\UserController@getData');
    Router::post('/list', 'App\Controller\Admin\UserController@getDefaultData');
}, ['middleware' => [\Phper666\JWTAuth\Middleware\JWTAuthMiddleware::class]]);


//Router::addGroup('/v2',function (){
//    Router::get('/users', 'App\Controller\Admin\TestUserController@index');
//    Router::post('/users', 'App\Controller\TestUserController@store');
//    Router::put('/users/{id:\d+}', 'App\Controller\TestUserController@update');
//    Router::get('/users/{id:\d+}', 'App\Controller\TestUserController@show');
//    Router::delete('/users/{id:\d+}', 'App\Controller\TestUserController@delete');
//    Router::put('/users/{id:\d+}/roles', 'App\Controller\TestUserController@roles');
//}, ['middleware' => $middleware]);



//User
Router::get('/users', 'App\Controller\Admin\TestUserController@index', ['middleware' => $middleware]);
Router::post('/users', 'App\Controller\UserController@store', ['middleware' => $middleware]);
Router::put('/users/{id:\d+}', 'App\Controller\UserController@update', ['middleware' => $middleware]);
Router::get('/users/{id:\d+}', 'App\Controller\UserController@show', ['middleware' => $middleware]);
Router::delete('/users/{id:\d+}', 'App\Controller\UserController@delete', ['middleware' => $middleware]);
Router::put('/users/{id:\d+}/roles', 'App\Controller\UserController@roles', ['middleware' => $middleware]);



Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

// 设置一个 GET 请求的路由，绑定访问地址 '/get' 到 App\Controller\IndexController 的 get 方法
//Router::get('/get', 'App\Controller\IndexController::get');
Router::addRoute(['GET', 'POST', 'HEAD'],'/get', 'App\Controller\IndexController@get');
Router::addRoute(['GET', 'POST', 'HEAD'],'/parallel', 'App\Controller\IndexController@parallel');
Router::addRoute(['GET', 'POST', 'HEAD'],'/writeGroup', 'App\Controller\IndexController@writeGroup');
Router::addRoute(['GET', 'POST', 'HEAD'],'/getFactory', 'App\Controller\IndexController@getFactory');
Router::addRoute(['GET', 'POST', 'HEAD'],'/getEvent', 'App\Controller\IndexController@getEvent');
Router::addRoute(['GET', 'POST', 'HEAD'],'/getAspect', 'App\Controller\IndexController@getAspect');
Router::addRoute(['GET', 'POST', 'HEAD'],'/page', 'App\Controller\IndexController@getPage');
Router::addRoute(['GET', 'POST', 'HEAD'],'/translator', 'App\Controller\IndexController@translator');
Router::addRoute(['GET', 'POST', 'HEAD'],'/validate', 'App\Controller\IndexController@validate');
Router::addRoute(['GET', 'POST', 'HEAD'],'/validateSecond', 'App\Controller\IndexController@validateSecond');
Router::addRoute(['GET', 'POST', 'HEAD'],'/seeds', 'App\Controller\IndexController@seeds');



Router::get('/log','App\Controller\IndexController@getLog');
Router::get('/exception','App\Controller\IndexController@exception');

Router::addGroup('/user',function (){
    Router::get('/info/[{id}]','App\Controller\UserController::info');
});
//Router::get('/user/{id}', 'App\Controller\UserController@info');


//Router::get('/get', [\App\Controller\IndexController::class, 'get']);

// 设置一个 POST 请求的路由，绑定访问地址 '/post' 到 App\Controller\IndexController 的 post 方法
//Router::post('/post', 'App\Controller\IndexController::post');
//Router::post('/post', 'App\Controller\IndexController@post');
//Router::post('/post', [\App\Controller\IndexController::class, 'post']);

// 设置一个允许 GET、POST 和 HEAD 请求的路由，绑定访问地址 '/multi' 到 App\Controller\IndexController 的 multi 方法
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', 'App\Controller\IndexController::multi');
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', 'App\Controller\IndexController@multi');
//Router::addRoute(['GET', 'POST', 'HEAD'], '/multi', [\App\Controller\IndexController::class, 'multi']);