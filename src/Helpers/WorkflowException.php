<?php

namespace Huoban\Helpers;



/**
 * 自定义一个异常处理类
 */
class WorkflowException
{
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
    }

    public static function setException()
    {
        // 自定义的代码
        set_exception_handler(function ($error) {
            // 500 服务器错误
            header('HTTP/1.1 500 Internal Server Error');
            header("Content-type:application/json");
            echo json_encode(['message' => $error->getMessage(), 'code' => $error->getCode()], JSON_UNESCAPED_UNICODE);
        });
    }
    public static function exception()
    {
    }
}
