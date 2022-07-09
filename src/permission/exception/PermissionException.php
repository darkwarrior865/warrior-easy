<?php
namespace darkwarrior\easy\permission\exception;

use think\Exception;

/**
 * 
 */
class PermissionException extends Exception
{
    /**
     * [__construct 错误异常构造函数]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-23T11:22:27+0800
     * @param    [type]                   $message [description]
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
