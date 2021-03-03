<?php
namespace app\common\model;

use think\Model;

class FriendShip extends Model
{
    public function add($insertData)
    {
        return $this->save($insertData);
    }
}