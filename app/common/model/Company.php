<?php
namespace app\common\model;

use think\Model;

class Company extends Model
{
    public function getCompanyInfo($info)
    {
        return $this->where('appid',$info['appid']) -> where('secret',$info['secret']) -> find();
    }
}