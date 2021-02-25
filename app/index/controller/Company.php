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
     * @return void
     */
    public function registeCompany(Request $request)
    {
        if (!$request->has('name') || !$request->has('type')) {
            return_json(array('code'=>'2001','msg'=>'缺少必要参数'));
        }
        $insertData = $request->param();
        //生成数据
        $insertData = $this->CompanyLogic->makeRegisteCompany($insertData);
        $result = $this->CompanyModel->save($insertData);
        if ($result) {
            return_json(array('code'=>'1001','注册公司成功'));
        } else {
            return_json(array('code'=>'4001','注册公司失败,请联系管理员'));
        }
        
    }
}
