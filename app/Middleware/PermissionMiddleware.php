<?php


declare(strict_types = 1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Contract\ConfigInterface;
use Donjan\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware implements MiddlewareInterface
{

    /**
     * 路由权限认证
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $method = $request->getMethod();
//        echo 'uri:'.$uri.'--'.PHP_EOL;
//        echo 'path:'.$path.'--'.PHP_EOL;
//        echo 'method:'.$method.'--'.PHP_EOL;

        //拼接路由参数
        $name = strtolower($path.'/'.$method);
        $user = $request->getAttribute('user');
        //是否有权限
        if ($user && $user->can($name)) {
            return $handler->handle($request);
        }
        throw new UnauthorizedException('无权进行该操作/请联系管理人员.', 403);
    }

}