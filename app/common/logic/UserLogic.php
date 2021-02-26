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
        if (isset($params['password'])) 
        {
            $params['password'] = password_hash($params['password'],PASSWORD_DEFAULT);
        }
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
        $where['accid'] = $params['accid'];
        $where['c_id'] = $companyInfo['id'];
        $result = $userModel->getUserInfo($where);
        if ($result) {
            return_json(array('code'=>'3002','msg'=>'该accid标识已存在'));
        }
    }

    /**
     * 用户登录
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @param [type] $where
     * accid
     * password
     * appid
     * @return void
     */
    public function userLogin($check_param,$companyInfo)
    {
        $queryUserWhere['accid'] = $check_param['accid'];
        $queryUserWhere['appid'] = $companyInfo['appid'];
        
        $userModel = new userModel();
        $result = $userModel->getUserInfo($queryUserWhere);

        if (!$result) {
            return_json(array('code'=>3001,'msg'=>'账号错误'));
        } 
        if ($result['password'] != password_hash($check_param['password'],PASSWORD_DEFAULT)) {
            return_json(array('code'=>3001,'msg'=>'密码错误'));
        }

        return $result['token'];
        
    }

}