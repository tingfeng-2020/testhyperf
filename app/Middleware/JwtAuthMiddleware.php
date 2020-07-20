<?php
declare(strict_types = 1);

namespace App\Middleware;

use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Context;
use Phper666\JWTAuth\Exception\TokenValidException;
use Phper666\JWTAuth\JWT;
use Phper666\JWTAuth\Util\JWTUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtAuthMiddleware implements MiddlewareInterface
{

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * 登陆token验证
     * @Inject
     * @var JWT
     */
    protected $jwt;


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            if ($this->jwt->checkToken()) {
                $userData = $this->jwt->getParserData();
                $userId = $userData['uid'];
                $user = User::where('user_id', $userId)->where('status', User::STATUS_ENABLE)->first();
                if (!$user) {
                    throw new TokenValidException('Token未验证通过', 401);
                }
                $request = $request->withAttribute('user', $user);
                Context::set(ServerRequestInterface::class, $request);
            }
        } catch (\Exception $e) {
            throw new TokenValidException('Token未验证通过', 401);
        }
        return $handler->handle($request);
    }

}