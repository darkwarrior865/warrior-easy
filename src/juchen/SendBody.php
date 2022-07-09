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
     * [$phone description]
     * @var [type]
     */
    private $receiver;

    /**
     * [$msg description]
     * @var [type]
     */
    private $content;

    /**
     * [setReceiver description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:30+0800
     * @param    string                   $value [description]
     */
    public function setReceiver(string $value = ''): void
    {
        $this->receiver = $value;
    }

    /**
     * [getReceiver description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:37+0800
     * @return   [type]                   [description]
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * [setContent description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:48+0800
     * @param    string                   $value [description]
     */
    public function setContent(string $value = ''): void
    {
        $this->content = $value;
    }

    /**
     * [getContent description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-09T09:02:50+0800
     * @return   [type]                   [description]
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
