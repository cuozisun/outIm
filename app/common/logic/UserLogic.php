<?php
namespace app\common\logic;

use app\common\model\User as userModel;

class UserLogic
{
    /**
     * 生成注册用户数据
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * @param [type] $params
     * @param [type] $companyInfo
     * @return void
     */
    public function makeRegisteUser($params,$companyInfo)
    {
        $params['c_id'] = $companyInfo['id'];
        $params['accid'] = strtolower($params['accid']);
        $params['token'] = password_hash($params['c_id'].$params['accid'],PASSWORD_DEFAULT);
        unset($params['appid']);
        unset($params['secret']);
        return $params;
    }   


    /**
     * 查询注册账号是否一重复
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-25
     * @param [type] $params
     * @return void
     */
    public function checkIsRepeat($params,$companyInfo) 
    {
        $userModel = new userModel();
        $result = $userModel->getUserByAccidAndCid($params['accid'],$companyInfo['id']);
        if (!$result) {
            return_json(array('code'=>'3002','msg'=>'该accid标识已存在'));
        }
    }

}