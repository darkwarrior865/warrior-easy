<?php
namespace darkwarrior\easy\email;

/**
 * class Config
 * 配置
 * @author 黑暗中的武者
 */
class Config
{
    /**
     * [$host description]
     * @var [type]
     */
    private $host;

    /**
     * [$port description]
     * @var [type]
     */
    private $port;

    /**
     * [$user description]
     * @var [type]
     */
    private $user;

    /**
     * [$pass description]
     * @var [type]
     */
    private $pass;

    /**
     * [$fromEmail description]
     * @var [type]
     */
    private $fromEmail;

    /**
     * [$fromName description]
     * @var [type]
     */
    private $fromName;

    /**
     * [$replyEmail description]
     * @var [type]
     */
    private $replyEmail;

    /**
     * [$replyName description]
     * @var [type]
     */
    private $replyName;

    /**
     * [setHost description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:36:11+0800
     * @param    string                   $value [description]
     */
    public function setHost(string $value = ''): void
    {
        $this->host = $value;
    }

    /**
     * [getHost description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:37:15+0800
     * @return   [type]                   [description]
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * [setPort description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:09+0800
     * @param    string                   $value [description]
     */
    public function setPort(string $value = ''): void
    {
        $this->port = $value;
    }

    /**
     * [getPort description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:13+0800
     * @return   [type]                   [description]
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * [setUser description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:18+0800
     * @param    string                   $value [description]
     */
    public function setUser(string $value = ''): void
    {
        $this->user = $value;
    }

    /**
     * [getUser description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:22+0800
     * @return   [type]                   [description]
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * [setPass description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:25+0800
     * @param    string                   $value [description]
     */
    public function setPass(string $value = ''): void
    {
        $this->pass = $value;
    }

    /**
     * [getPass description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:28+0800
     * @return   [type]                   [description]
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * [setFromEmail description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:34+0800
     * @param    string                   $value [description]
     */
    public function setFromEmail(string $value = ''): void
    {
        $this->fromEmail = $value;
    }

    /**
     * [getFromEmail description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:46+0800
     * @return   [type]                   [description]
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * [setFromName description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:49+0800
     * @param    string                   $value [description]
     */
    public function setFromName(string $value = ''): void
    {
        $this->fromName = $value;
    }

    /**
     * [getFromName description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:40:54+0800
     * @return   [type]                   [description]
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * [setReplyEmail description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:41:03+0800
     * @param    string                   $value [description]
     */
    public function setReplyEmail(string $value = ''): void
    {
        $this->replyEmail = $value;
    }

    /**
     * [getReplyEmail description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:41:16+0800
     * @return   [type]                   [description]
     */
    public function getReplyEmail(): string
    {
        return $this->replyEmail;
    }

    /**
     * [setReplyName description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:41:30+0800
     * @param    string                   $value [description]
     */
    public function setReplyName(string $value = ''): void
    {
        $this->replyName = $value;
    }

    /**
     * [getReplyName description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-12T09:41:33+0800
     * @return   [type]                   [description]
     */
    public function getReplyName(): string
    {
        return $this->replyName;
    }
}
