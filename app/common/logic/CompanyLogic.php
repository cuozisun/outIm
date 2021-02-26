<?php
namespace app\common\logic;

use app\common\model\Company as CompanyModel;
use app\common\model\JoinPlan as JoinPlanModel;


class CompanyLogic
{
    /**
     * 生成插入公司表数据
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * @param [type] $insertData
     * @return void
     */
    public function makeRegisteCompany($insertData)
    {
        //生成公用部分
        $insertData['appid'] = make_token();
        $insertData['secret'] = make_token();
        $insertData['add_time'] = date('Y-m-d h:i:s',time());
        //根据类型判断套餐产生对应数据
        $insertData = $this->joinPlant($insertData);
        return $insertData;
    }

    /**
     * 根据套餐生成过期时间,允许人数
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * @param [type] $type
     * @return void
     */
    public function joinPlant($insertData)
    {
        $JoinPlanModel = new JoinPlanModel();
        $planData = $JoinPlanModel->find($insertData['type']);
        if ($planData) {
            if ($planData['effective_day'] === 0) {
                $insertData['fire_time'] = 0;
            } else {
                $insertData['fire_time'] = time()+$planData['effective_day']*86400;
            }
            $insertData['access_num'] = $planData['access_num'];
            unset($insertData['type']);
            return $insertData;
        } else {
            return_json(array('code'=>'3001','无改类型套餐'));
        }
    }


    /**
     * 检查公司是否还可以注册
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-24
     * @param [type] $companyInfo
     * @return void
     */
    public function checkCompanyCanRegiste($companyInfo)
    {
        if ($companyInfo['c_is_close']==1) {
            return_json(array('code'=>'5001','账号已关闭'));
        } elseif ($companyInfo['fire_time']!==0 && time()>$companyInfo['fire_time']) {
            return_json(array('code'=>'5001','账号已过期'));
        } elseif($companyInfo['access_num']!==0 && ($companyInfo['access_num']<=$companyInfo['now_num'])) {
            return_json(array('code'=>'5001','注册人数已达到上限'));
        }
    }


    /**
     * Undocumented function
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @param [type] $check_param
     * @return void
     */
    public function getCompanyInfo($check_param)
    {
        $queryCompanyWhere['appid'] = $check_param['appid'];
        $companyModel = new companyModel();
        $companyInfo = $companyModel->getCompanyInfo($check_param);
        if (!$companyInfo) {
            return_json(array('code'=>'3001','msg'=>'无与appid匹配公司'));
        } else {
            return $companyInfo;
        }
    }



}