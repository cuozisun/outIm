<?php
namespace app\common\model;

use think\Model;

class User extends Model
{

    /**
     * 通过accid和c_id获取用户信息
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-25
     * @return void
     */
    public function getUserByAccidAndCid($accid, $c_id)
    {
        return $this-> where('accid',$accid) -> where('c_id',$c_id) -> find();
    }
}