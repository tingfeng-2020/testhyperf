<?php


namespace App\Controller\Admin;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Phper666\JWTAuth\JWT;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     *
     * @Inject()
     * @var JWT
     */
    protected $jwt;

    /**
     * 登陆接口
     * @param JWT $jwt
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        if ($username && $password) {
//            todo 查询users表，验证账号密码是否正确
            $userData = [
                'uid' => 1, // 如果使用单点登录，必须存在配置文件中的sso_key的值，一般设置为用户的id
                'username' => 'admin',
            ];
            // 使用默认场景登录
            $token = $this->jwt->setScene('default')->getToken($userData);
            $data = [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'token' => $token,
                    'exp' => $this->jwt->getTTL(),
                ]
            ];
            return $this->response->json($data);
        }
        return $this->response->json(['code' => 0, 'msg' => '登录失败', 'data' => []]);
    }

    # http头部必须携带token才能访问的路由
    public function getData()
    {
        return $this->response->json(['code' => 0, 'msg' => 'success', 'data' => ['a' => 1]]);
    }

    /**
     * @PutMapping(path="refresh")
     * @Middleware(JWTAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refreshToken()
    {
        $token = $this->jwt->refreshToken();
        $data = [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
            ]
        ];
        return $this->response->json($data);
    }

    /**
     * 退出
     * @DeleteMapping(path="logout")
     * @Middleware(JWTAuthMiddleware::class)
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout()
    {
        return $this->jwt->logout();
    }

    /**
     * 获取token数据
     * 只能使用default场景值生成的token访问
     * @GetMapping(path="list")
     * @Middleware(JWTAuthSceneDefaultMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDefaultData(RequestInterface $request)
    {
        $username = $request->all();
        var_dump($username);
//        $ss = $this->jwt->getParserData();
//        return $ss['uid'];
        $data = [
            'code' => 0,
            'msg' => 'success',
            'data' => $this->jwt->getParserData()
        ];
        return $this->response->json($data);
    }
}