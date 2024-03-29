<?php
namespace darkwarrior\easy\juchen;

/**
 * class SendBody
 * 发送信息体
 * @author 黑暗中的武者
 */
class SendBody
{
    /**
     * [$receiver description]
     * @var [type]
     */
    private $receiver;

    /**
     * [$message description]
     * @var [type]
     */
    private $message;

    /**
     * [setReceiver description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:30+0800
     * @param    string                   $value [description]
     */
    public function setReceiver($value = ''): void
    {
        $this->receiver = $value;
    }

    /**
     * [getReceiver description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:37+0800
     * @return   [type]                   [description]
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * [setMessage description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:48+0800
     * @param    string                   $value [description]
     */
    public function setMessage(string $value = ''): void
    {
        $this->message = $value;
    }

    /**
     * [getMessage description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:50+0800
     * @return   [type]                   [description]
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
