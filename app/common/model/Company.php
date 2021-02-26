<?php
namespace app\common\model;

use think\Model;

class Company extends Model
{

    /**
     * 根据appid,secret查询公司信息
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @param [type] $info
     * @return void
     */
    public function getCompanyInfo($where)
    {
        return $this->where($where) -> find();
    }


    
}