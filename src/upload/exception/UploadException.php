<?php
namespace warrior\easy\upload\exception;

use think\Exception;

/**
 * class UploadException
 * 用于封装上传时 出现的错误异常
 * @Author   黑暗中的武者
 * @DateTime 2019-06-27T14:24:06+0800
 */
class UploadException extends Exception
{
    /**
     * [__construct 错误异常构造函数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:26:43+0800
     * @param    [type]                   $message [description]
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
