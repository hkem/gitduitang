<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/23
 * Time: 17:51
 */

namespace app\system\model;


use think\Model;

class Grass extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function add_grass($sort,$image,$name,$centre,$readq,$collection,$uid,$type){
        $data["image"] = $image;
        $data["centre"] = htmlspecialchars_decode($centre);
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["readq"] = $readq;
        $data["collection"] = $collection;
        $data["uid"] = $uid;
        $data["type"] = $type;
        return self::create($data,true);
    }

    public static function update_grass($sort,$image,$name,$centre,$readq,$collection,$uid,$type,$id){
        $data["image"] = $image;
        $data["centre"] = htmlspecialchars_decode($centre);
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["readq"] = $readq;
        $data["collection"] = $collection;
        $data["uid"] = $uid;
        $data["type"] = $type;
        $Goods = new Grass();
        return $Goods->save($data,['id' => $id]);
    }

    public static function delete_grass($id){
        $user = Grass::get($id);
        return $user->delete();
    }

    public static function sort_grass($id,$sort){
        $user = Grass::get($id);
        $user->sort = $sort;
        return $user->save();
    }
    public static function recommend_grass($id,$val){
        $user = Grass::get($id);
        $user->recommend = $val;
        return $user->save();
    }

}