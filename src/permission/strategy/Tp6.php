<?php
namespace darkwarrior\easy\permission\strategy;

use darkwarrior\easy\permission\strategy\StrategyInterface;
use darkwarrior\easy\permission\strategy\StrategyBasic;
use think\facade\Db;

/**
 * class Tp6
 * tp6框架 获取数据
 * @author 黑暗中的武者
 */
class Tp6 extends StrategyBasic implements StrategyInterface
{
    /**
     * [findUser description]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:48:56+0800
     * @param    int|integer              $userId [description]
     * @return   [type]                           [description]
     */
    public function findUser(int $userId = 0): array
    {
        if (empty($userId)) {
            return [];
        }
        return Db::name($this->config->tableUser)->where('id', $userId)->field('rules,group_ids')->find();
    }

    /**
     * [selectGroupList description]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:48:59+0800
     * @param    string                   $groupIds [description]
     * @return   [type]                             [description]
     */
    public function selectGroupList(string $groupIds = ''): array
    {
        if (empty($groupIds)) {
            return [];
        }
        return Db::name($this->config->tableGroup)->whereIn('id', $groupIds)->where('status', 1)->field('rules')->select()->toArray();
    }

    /**
     * [selectAuthList description]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:49:02+0800
     * @param    array                    $ruleIdArray [description]
     * @return   [type]                                [description]
     */
    public function selectAuthList(array $ruleIdArray = []): array
    {
        if (empty($ruleIdArray)) {
            return [];
        }
        return Db::name($this->config->tableRule)->whereIn('id', $ruleIdArray)->where('status', 1)->order('sort ASC,id DESC')->select()->toArray();
    }
}
