<?php
namespace darkwarrior\easy\permission\strategy;

/**
 * class StrategyInterface
 * 框架策略的接口类，用于统一获取数据的方法，以便于切换策略
 * @author 黑暗中的武者
 */
interface StrategyInterface
{
    /**
     * [findUser 获取用户信息]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:47:42+0800
     * @param    int                      $userId [description]
     * @return   [type]                           [description]
     */
    public function findUser(int $userId = 0): array;

    /**
     * [selectGroupList 获取分组列表]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:47:45+0800
     * @param    string                   $groupIds [description]
     * @return   [type]                             [description]
     */
    public function selectGroupList(string $groupIds = ''): array;

    /**
     * [selectAuthList 获取权限列表]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:47:48+0800
     * @param    array                    $ruleIdArray [description]
     * @return   [type]                                [description]
     */
    public function selectAuthList(array $ruleIdArray = []): array;
}
