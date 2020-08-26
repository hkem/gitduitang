<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/7
 * Time: 19:33
 */

namespace app\system\model;


use think\Model;

class Prize extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function add_prize($sort,$image,$name,$rate,$mun,$muber){
        $data["image"] = $image;
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["rate"] = $rate;
        $data["mun"] = $mun;
        $data["muber"] = $muber;
        return self::create($data,true);
    }

    public static function update_prize($sort,$image,$name,$rate,$mun,$muber,$id){
        $data["image"] = $image;
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["rate"] = $rate;
        $data["mun"] = $mun;
        $data["muber"] = $muber;
        $Goods = new Prize();
        return $Goods->save($data,['id' => $id]);
    }

    public static function delete_prize($id){
        $user = Prize::get($id);
        return $user->delete();
    }

    public static function sort_prize($id,$sort){
        $user = Prize::get($id);
        $user->sort = $sort;
        return $user->save();
    }
    public static function rate_prize($id,$val){
        $user = Prize::get($id);
        $user->rate = $val;
        return $user->save();
    }
    public static function mun_prize($id,$val){
        $user = Prize::get($id);
        $user->mun = $val;
        return $user->save();
    }
}