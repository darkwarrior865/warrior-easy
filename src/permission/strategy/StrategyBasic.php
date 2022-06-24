<?php
namespace warrior\easy\permission\strategy;

use warrior\easy\permission\Config;

/**
 * class StrategyBasic
 * 框架策略基类
 * @author 黑暗中的武者
 */
class StrategyBasic
{
    /**
     * [$config 配置对象]
     * @var [type]
     */
    protected $config;

    /**
     * [__construct 构造]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-22T17:21:19+0800
     * @param    [type]                   $config [description]
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}
