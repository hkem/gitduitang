<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/23
 * Time: 19:49
 */

namespace app\system\model;


use think\Model;

class User extends Model
{
    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

}