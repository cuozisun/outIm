<?php
namespace app\common\logic;

use think\facade\Cache;
use app\common\model\User as userModel;
use app\common\model\FriendShip as friendShipModel;

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

        return $result['id'];
        
    }

    /**
     * 通过搜索条件联合查询tel和账号
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-03
     * @param [type] $searchinfo
     * @return void
     */
    public function getUserInfo($searchinfo)
    {
        $userModel = new userModel();
        $result = $userModel->unionGetuserInfo($searchinfo);
        if (!$result) {
            return_json(array('code'=>3002,'msg'=>'该用户不存在'));
        }
        return $result;
        
    }


    public function getUserInfoWhere($where)
    {
        $userModel = new userModel();
        $result = $userModel->getUserInfo($where);
        if (!$result) {
            return_json(array('code'=>3002,'msg'=>'该用户不存在'));
        }
        return $result;
    }


    /**
     * 同意好友请求
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-03
     * @param [type] $params
     * @return void
     */
    public function agreeRequest($params)
    {
        $key = $params['uid'].'_'.$params['otheruid'].'_1';
        $status = Cache::store('redis')->get($key);


        $userModel = new userModel();
        $friendShipModel = new friendShipModel();
        
        switch ($status) {
            case '0':
            case '1':
                //删除redis
                Cache::store('redis')->delete($key);
                break;
            default:
                # key的失效已过
                break;
        }
        $insertData = [];
        $insertData['user_id'] = $params['uid'];
        $insertData['friend_id'] = $params['otheruid'];
        $result = $friendShipModel->add($insertData);


        if (!$result) {
            return_json(array('code'=>4001,'msg'=>'写入数据失败'));
        } 
        //查询个人信息
        $where['user_id'] = $params['otheruid'];
        $uinfo['otherinfo'] = $userModel->getUserInfo($where);
        $where['user_id'] = $params['uid'];
        $uinfo['uinfo'] = $userModel->getUserInfo($where);
        return $uinfo;
    }

    /**
     * 拒绝好友请求
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-03
     * @param [type] $params
     * @return void
     */
    public function refuseRequest($params)
    {
        $key = $params['uid'].'_'.$params['otheruid'].'_1';
        $status = Cache::store('redis')->get($key);
        switch ($status) {
            case '0':
            case '1':
                //删除redis
                Cache::store('redis')->delete($key);
                break;
            default:
                # key的失效已过
                break;
        }
        return true;
    }

}