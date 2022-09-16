<?php
namespace darkwarrior\easy\juchen;

/**
 * class Config
 * 配置
 * @author 黑暗中的武者
 */
class Config
{
    /**
     * [$apiUrl description]
     * @var string
     */
    private $apiUrl = 'http://www.qybor.com:8500/shortMessage';
    
    /**
     * [$username description]
     * @var [type]
     */
    private $username;

    /**
     * [$password description]
     * @var [type]
     */
    private $password;

    /**
     * [setApiUrl description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:54:03+0800
     * @param    string                   $value [description]
     */
    public function setApiUrl(string $value = ''): void
    {
        $this->apiUrl = $value;
    }

    /**
     * [getApiUrl description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:54:06+0800
     * @return   [type]                   [description]
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }
    
    /**
     * [setUsername description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:46:22+0800
     * @param    string                   $value [description]
     */
    public function setUsername(string $value = ''): void
    {
        $this->username = $value;
    }

    /**
     * [getUsername description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:46:39+0800
     * @return   [type]                   [description]
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * [setPassword description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:48:32+0800
     * @param    string                   $value [description]
     */
    public function setPassword(string $value = ''): void
    {
        $this->password = $value;
    }

    /**
     * [getPassword description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T17:46:39+0800
     * @return   [type]                   [description]
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
