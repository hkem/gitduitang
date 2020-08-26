<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/3
 * Time: 16:38
 */

namespace app\system\model;


use think\Model;

class Search extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function add_search($sort,$name,$type){
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["type"] = $type;
        return self::create($data,true);
    }

    public static function update_search($sort,$name,$type,$id){
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["type"] = $type;
        $Goods = new Search();
        return $Goods->save($data,['id' => $id]);
    }

    public static function delete_search($id){
        $user = self::get($id);
        return $user->delete();
    }

    public static function sort_search($id,$sort){
        $user = self::get($id);
        $user->sort = $sort;
        return $user->save();
    }
}