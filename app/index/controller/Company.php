<?php
namespace app\index\controller;


use think\Request;
use app\BaseController;
use app\common\logic\CompanyLogic;
use app\common\model\Company as CompanyModel;


class Company extends BaseController
{

    public function __construct()
    {
        $this->CompanyLogic = new CompanyLogic();
        $this->CompanyModel = new CompanyModel();
    }


    public function index()
    {
        echo 'Company';
        // return true;
    }


    /**
     * 注册公司
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * @param Request $request
     * @return appid 公司对应的appid
     */
    public function registeCompany(Request $request)
    {
        $check_param = array('name','type');
        check_must_param($request,$check_param);
        $insertData = $request->param();
        
        //生成数据
        $insertData = $this->CompanyLogic->makeRegisteCompany($insertData);

        $result = $this->CompanyModel->save($insertData);
        
        if ($result) {
            //生成返回信息
            $return['appid'] = $insertData['appid'];
            return_json(array('code'=>'1001','注册公司成功','data'=>$return));
        } else {
            return_json(array('code'=>'4001','注册公司失败,请联系管理员'));
        }
        
    }
}
