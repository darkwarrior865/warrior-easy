<?php
namespace warrior\easy\permission;

use think\facade\Db;
use warrior\easy\permission\exception\PermissionException;
use warrior\easy\permission\Config;

/**
 * class Permission
 * 权限控制
 * @author 黑暗中的武者
 */
class Permission
{
    /**
     * [$config 配置对象]
     * @var [type]
     */
    private $config;

    /**
     * [$source 框架策略对象]
     * @var [type]
     */
    private $source;

    /**
     * [$ruleList 用户拥有的权限列表]
     * @var array
     */
    private $ruleList   = [];

    /**
     * [$ruleArray 用户拥有的权限规则数组]
     * @var array
     */
    private $ruleArray  = [];

    /**
     * [$aliasArray 用户拥有的权限别名数组]
     * @var array
     */
    private $aliasArray = [];

    /**
     * [__construct description]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-26T08:16:18+0800
     * @param    [type]                   $config [description]
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        // 创建框架策略对象
        $this->source = $this->createStrategy($this->config->strategy);
    }

    /**
     * [createStrategy 创建框架策略]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-26T09:16:41+0800
     * @param    string                   $strategy [description]
     * @return   [type]                             [description]
     */
    private function createStrategy(string $strategy = '')
    {
        $class = '\\warrior\\easy\\permission\\strategy\\' . $strategy;
        if (!class_exists($class)) {
            throw new PermissionException('查询框架策略类未定义！');
        }

        $strategyEntity = new $class($this->config);

        // 是否实现了StrategyInterface接口
        if (!($strategyEntity instanceof \warrior\easy\permission\strategy\StrategyInterface)) {
            throw new PermissionException('查询框架策略类异常！');
        }
        // 是否继承了StrategyBasic
        if (!($strategyEntity instanceof \warrior\easy\permission\strategy\StrategyBasic)) {
            throw new PermissionException('查询框架策略类异常！');
        }

        return $strategyEntity;
    }

    /**
     * [check description]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-22T17:48:22+0800
     * @param    [type]                   $checkRule  [需要验证的规则列表，支持逗号分隔的权限规则或索引数组]
     * @param    int|integer              $user_id    [认证用户的id]
     * @param    string                   $relation   [如果为 'or' 表示满足任一条规则即通过验证，如果为 'and' 则表示需满足所有规则才能通过验证]
     * @return   [boolean]                            [description]
     */
    public function check($checkRule, int $user_id = 0, array $requestParams = [], string $relation = 'or'): bool
    {
        if (!isset($this->ruleArray[$user_id])) {
            $this->setRuleData($user_id);
        }

        // 是否开启了验证
        if (!$this->config->authOn) {
            return true;
        }

        // 获取用户的所有有效规则列表
        $rule_array = $this->ruleArray[$user_id];

        // 需要验证的规则 转成数组
        if (is_string($checkRule)) {
            $checkRule = strtolower($checkRule);
            if (strpos($checkRule, ',') !== false) {
                $checkRule = explode(',', $checkRule);
            } else {
                $checkRule = [$checkRule];
            }
        }

        // 保存验证通过的规则名
        $list = [];
        // 获取请求参数 都转成小写
        $request_params = unserialize(strtolower(serialize($requestParams)));

        foreach ($rule_array as $rule) {
            // 去除规则中 ? 前的字符，例：/user/index?a=b&c=d 变成 a=b&c=d
            $query = preg_replace('/^.+\?/U', '', $rule); 
            // 如果 $query != $rule 说明规则中带有参数（参数携带方式需为 ?a=b&c=d）
            if ($query != $rule) { 
                // 将a=b&c=d格式字符串解析为数组 存到param
                parse_str($query, $param);
                // 获取请求参数数组和解析参数数组的交集
                $intersect = array_intersect_assoc($request_params, $param); 
                // 去除规则中 ? 后的字符，例：/user/index?a=b&c=d 变成 user/index
                $rule = preg_replace('/\?.*$/U', '', $rule);
                // 如果规则相符且参数相符 则路径验证通过
                if (in_array($rule, $checkRule) && $intersect == $param) {
                    $list[] = $rule;
                }
            } else if (in_array($rule, $checkRule)) {
                $list[] = $rule;
            }
        }

        // 如果判断条件为or，则存在验证通过的规则即验证通过
        if ($relation == 'or' and !empty($list)) {
            return true;
        }

        // 需验证的规则和通过的规则取差集，为空代表没有差集 即所有规则验证通过
        $diff = array_diff($checkRule, $list);
        // 如果判断条件为and，则所有规则验证通过即验证通过
        if ($relation == 'and' and empty($diff)) {
            return true;
        }

        return false;
    }

    /**
     * [setRuleData 设置权限数据]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-25T09:07:28+0800
     * @param    int|integer              $userId [description]
     * @return   [type]                           [description]
     */
    public function setRuleData(int $userId = 0): void
    {
        // 如果是session中已存在
        if ($this->config->authType == 2 && !empty(session('rule_array_user'.$userId))) {
            $this->ruleArray[$userId] = session('rule_array_user'.$userId);
        }

        // 获取用户所拥有的权限列表
        $rule_list = $this->getRuleList($userId);
        // 权限列表赋值
        $this->ruleList[$userId] = $rule_list;

        foreach ($rule_list as $rule) {
            // 权限规则数组赋值
            $this->ruleArray[$userId][] = $rule['app'] . strtolower($rule['rule']);
            // 权限别名数组赋值
            if (!empty($rule['rule_alias'])) {
                $this->aliasArray[$userId][] = $rule['rule_alias'];
            }
        }
        // 如果类型为2
        if ($this->config->authType == 2) {
            // 规则数组保存到session
            session('rule_array_user'.$userId, $this->ruleArray[$userId]);
        }
    }

    /**
     * [getAliasArray 获取权限别名数组]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:54:40+0800
     * @param    int|integer              $userId [description]
     * @return   [type]                           [description]
     */
    public function getAliasArray(int $userId = 0): array
    {
        return $this->aliasArray[$userId];
    }

    /**
     * [getRuleIdArray 获取用户所拥有的权限ID数组]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:37:51+0800
     * @param    int                      $userId [description]
     * @return   [type]                           [description]
     */
    public function getRuleIdArray(int $userId = 0): array
    {
        if (empty($userId)) {
            return [];
        }
        // 保存用户所有权限id
        $rule_id_array = [];
        // 获取用户独立的权限和所属分组
        $user = $this->source->findUser($userId);
        // 如果有独立权限 则返回独立权限
        if (!empty($user['rules'])) {
            $rule_id_array = explode(',', trim($user['rules'], ','));
        } else {
            // 获取用户所属分组信息列表
            $group_list = $this->source->selectGroupList(trim($user['group_ids'], ','));
            // 合并所有分组拥有的权限ID
            foreach ($group_list as $group) {
                $rule_id_array = array_merge($rule_id_array, explode(',', trim($group['rules'], ',')));
            }
        }
        // 去重并返回
        return array_unique($rule_id_array);
    }

    /**
     * [getRuleList 获取用户所拥有的权限列表]
     * @Author   黑暗中的武者
     * @DateTime 2022-05-22T17:20:39+0800
     * @param    int                      $userId [description]
     * @return   [type]                           [description]
     */
    public function getRuleList(int $userId = 0): array
    {
        if (empty($userId)) {
            return [];
        }
        $rule_id_array = $this->getRuleIdArray($userId);
        if (empty($rule_id_array)) {
            return [];
        }
        return $this->source->selectAuthList($rule_id_array);
    }

    /**
     * [getMenu 获取用户所拥有的菜单]
     * @Author   黑暗中的武者
     * @DateTime 2022-06-03T16:58:12+0800
     * @param    string                   $visit  [description]
     * @param    int|integer              $userId [description]
     * @return   [type]                           [description]
     */
    public function getMenu(string $visit = '', int $userId = 0)
    {
        if (empty($userId)) {
            return [];
        }
        if (!isset($this->ruleList[$userId])) {
            $this->setRuleData($userId);
        }
        // 获取权限列表
        $rule_list  = $this->ruleList[$userId];
        // 当前访问的权限ID
        $current_id = 0;
        // 菜单
        $menus      = [];
        // 此循环 获取当前访问路径对应的权限ID
        foreach ($rule_list as $value) {
            // 获取当前访问路径对应的权限ID
            if (false !== strpos($value['rule'], $visit)) {
                $current_id = $value['id'];
            }
            // 获取所有菜单类型的权限
            if ($value['type'] == 'menu') {
                $menus[] = $value;
            }
        }
        // 获取当前访问路径的向上族谱
        $genealogy = genealogy($rule_list, $current_id);
        // 焦点菜单ID
        $active    = 0;
        // 获取焦点菜单ID
        foreach ($genealogy as $value) {
            if ($value['type'] == 'menu') {
                $active = $value['id'];
                break;
            }
        }
        // 给焦点菜单设置为选中
        foreach ($menus as &$menu) {
            if ($menu['id'] == $active) {
                $menu['is_active'] = 1;
                break;
            }
        }

        return [
            'menus'       => list2tree($menus),
            'breadcrumbs' => $genealogy
        ];
    }
}
