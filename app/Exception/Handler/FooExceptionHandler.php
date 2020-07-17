<?php
namespace App\Exception\Handler;

use App\Exception\ApiException;
use App\Log;
use Donjan\Permission\Exceptions\UnauthorizedException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Phper666\JWTAuth\Exception\JWTException;
use Phper666\JWTAuth\Exception\TokenValidException;
use Psr\Http\Message\ResponseInterface;
use App\Exception\FooException;
use Throwable;

class FooExceptionHandler extends  ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if (!$throwable instanceof FooException and
            !$throwable instanceof ApiException and
            !$throwable instanceof JWTException and
            !$throwable instanceof ValidationException and
            !$throwable instanceof UnauthorizedException and
            !$throwable instanceof TokenValidException) {
            // 格式化输出
            $data = json_encode([
                'code' => 1001,
                'message' => '服务器错误',
                'data' => [],
            ], JSON_UNESCAPED_UNICODE);

            // 阻止异常冒泡
            $this->stopPropagation();

            Log::get()->error('['.$throwable->getFile().']'.' in '.$throwable->getCode().' :'.$throwable->getMessage());

            return $response->withStatus(500)->withBody(new SwooleStream($data));

        } else {
            // 格式化输出
            $data = [
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
                'data' => [],
            ];

            //表单验证抛出的错误
            if ($throwable instanceof ValidationException) {
//                $this->stopPropagation();
                $body = $throwable->validator->errors()->first();
                $data['message'] = $body;
            }

        }







        // 交给下一个异常处理器
        $res = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $response->withStatus($data['code'])->withBody(new SwooleStream($res));

        // 或者不做处理直接屏蔽异常
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
