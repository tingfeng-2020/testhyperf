<?php
namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class ApiException extends ServerException
{
    public function __construct(int $code = 0, string $message = null, $replace=[],Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }
        if($replace) {
            is_array($replace) or $replace = (array)$replace;
            $message = sprintf($message,...$replace);
        }

        parent::__construct($message, $code, $previous);
    }
}
