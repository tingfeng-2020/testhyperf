<?php
namespace App\Exception\Handler;

use App\Exception\ApiException;
use App\Log;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use App\Exception\FooException;
use Throwable;

class FooExceptionHandler extends  ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if (
            $throwable instanceof FooException ||
            $throwable instanceof ApiException) {
            // 格式化输出
            $data = json_encode([
                'code' => 1001,
                'message' => '服务器错误',
                'data' => [],
            ], JSON_UNESCAPED_UNICODE);

            // 阻止异常冒泡
            $this->stopPropagation();

            $response->withStatus(500);

        } else {
            // 格式化输出
            $data = json_encode([
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
                'data' => [],
            ], JSON_UNESCAPED_UNICODE);

            Log::get()->error('['.$throwable->getFile().']'.' in '.$throwable->getCode().' :'.$throwable->getMessage());
            $response->withStatus(200);
        }


        // 交给下一个异常处理器
        return $response->withBody(new SwooleStream($data));

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
