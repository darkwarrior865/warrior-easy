<?php
namespace darkwarrior\easy\permission;

/**
 * class Config
 * 权限验证配置
 * @author 黑暗中的武者
 */
class Config
{
    /**
     * [$authOn 开关]
     * @var boolean
     */
    private $authOn   = true;

    /**
     * [$authType 1--实时 2-登录 session]
     * @var integer
     */
    private $authType = 1;

    /**
     * [$tableGroup 权限组 表名，必要字段 id name rules status]
     * @var string
     */
    private $tableGroup = 'auth_group';

    /**
     * [$tableRule 权限规则 表名，必要字段 id name app rule rule_alias status]
     * @var string
     */
    private $tableRule = 'auth_rule';

    /**
     * [$tableUser 权限用户 表名，必要字段 id username password group_ids rules status]
     * @var string
     */
    private $tableUser = 'administrator';

    /**
     * [$strategy 框架策略]
     * @var string
     */
    private $strategy = 'Tp6';

    /**
     * [__set description]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-24T08:39:06+0800
     * @param    [type]                   $name  [description]
     * @param    [type]                   $value [description]
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * [__get description]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-24T08:39:10+0800
     * @param    [type]                   $name [description]
     * @return   [type]                         [description]
     */
    public function __get($name)
    {
        return $this->$name;
    }
}
