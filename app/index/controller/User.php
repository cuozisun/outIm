<?php
namespace app\index\controller;

use think\Request;
use app\BaseController;
use app\common\logic\UserLogic;
use app\common\logic\CompanyLogic;
use app\common\model\User as UserMdel;
use app\common\model\Company as companyModel;


class User extends BaseController
{
    public function index()
    {
        echo 'user';
        // return true;
    }

    
    /**
     * 注册用户
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * 
     * 必须参数:accid账号
     * nick_name:昵称
     * appid:公司对应appid
     * 
     * 非必须参数:
     * age
     * sex
     * password:如果是类似qq微信直接使用im功能  需要使用密码
     * @return token:为公司c_id与accid拼接加密得到
     */
    public function registeUser(Request $request)
    {
        $check_param = array('accid','nick_name','appid');
        check_must_param($request,$check_param);
        $params = $request->param();


        //匹配与appid,secret相对的公司
        $CompanyLogic = new CompanyLogic();
        $companyInfo = $CompanyLogic->getCompanyInfo($check_param);
        
        //检查公司是否可注册
        $CompanyLogic->checkCompanyCanRegiste($companyInfo);

        //查询用户是否重复
        $UserLogic = new UserLogic();
        $UserLogic->checkIsRepeat($params,$companyInfo);

        //生成注册数据
        $params = $UserLogic->makeRegisteUser($params,$companyInfo);

        //插入数据库
        $UserMdel = new UserMdel();
        $result = $UserMdel->save($params);
        if ($result) {
            return_json(array('code'=>'1001','msg'=>'用户创建成功','token'=>$params['token']));
        } else {
            return_json(array('code'=>'4001','msg'=>'用户创建失败'));
        }
    }

    /**
     * 纯粹im软件使用登录功能
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @return void
     * 
     * 必须参数
     * accid
     * password
     * appid
     * secret
     */
    public function userLogin(Request $request)
    {
        $check_param = array('accid', 'appid', 'password');
        check_must_param($request, $check_param);
        $params = $request->param();

        //匹配与appid相对的公司
        $CompanyLogic = new CompanyLogic();
        $companyInfo = $CompanyLogic->getCompanyInfo($params);

        //匹配账号
        $UserLogic = new UserLogic();
        $result = $UserLogic->userLogin($params,$companyInfo);
        
        if (!$result) {
            return_json(array('code'=>'3001','登录失败'));
        } 

        //查询存储信息,好友管理,聊天记录,群记录,消息请求
        
        return_json(array('code'=>'1001','登录成功','data'=>$result));
        
    }

}
