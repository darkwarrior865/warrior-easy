<?php
namespace darkwarrior\easy\email;

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
     * [$subject description]
     * @var [type]
     */
    private $subject;

    /**
     * [$body description]
     * @var [type]
     */
    private $body;

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
     * [setSubject description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:43:59+0800
     * @param    string                   $value [description]
     */
    public function setSubject(string $value = ''): void
    {
        $this->subject = $value;
    }

    /**
     * [getSubject description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:44:02+0800
     * @return   [type]                   [description]
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * [setBody description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:44:14+0800
     * @param    string                   $value [description]
     */
    public function setBody(string $value = ''): void
    {
        $this->body = $value;
    }

    /**
     * [getBody description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:44:16+0800
     * @return   [type]                   [description]
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
