<?php
namespace app\common\model;

use think\Model;

class User extends Model
{

    /**
     * 根据where条件查询单个用户信息
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @param [type] $where
     * @return void
     */
    public function getUserInfo($where)
    {
        return $this-> where($where)  -> find();
    }
}