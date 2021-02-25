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
     * @return void
     */
    public function registeUser(Request $request)
    {
        $check_param = array('accid','nick_name','appid','secret');
        check_must_param($request,$check_param);
        $params = $request->param();

        //匹配公司
        $companyModel = new companyModel();
        $companyInfo = $companyModel->getCompanyInfo($params);
        if (!$companyInfo) {
            return_json(array('code'=>'3001','msg'=>'无与appid匹配公司'));
        }
        
        //检查公司是否可注册
        $CompanyLogic = new CompanyLogic();
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
}
