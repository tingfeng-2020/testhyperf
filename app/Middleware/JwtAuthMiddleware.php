<?php
declare(strict_types = 1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Context;
use Phper666\JWTAuth\Exception\TokenValidException;
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


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $token = $request->getHeaderLine('Authorization') ?? '';
            $token = JWTUtil::getParserData($token);
            var_dump($token);
//            if ($this->jwt->checkToken()) {
//                $userId = $token->getClaim('user_id');
//                $user = User::where('user_id', $userId)->where('status', User::STATUS_ENABLE)->first();
//                if (!$user) {
//                    throw new TokenValidException('Token未验证通过', 401);
//                }
//                $request = $request->withAttribute('user', $user);
                Context::set(ServerRequestInterface::class, $request);
//            }
        } catch (\Exception $e) {
            $token = JWTUtil::getParserData($token);
            throw new TokenValidException('Token未验证通过', 401);
        }
        return $handler->handle($request);
    }

}