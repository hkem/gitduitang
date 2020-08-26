<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/12
 * Time: 22:47
 */

namespace app\system\model;


use think\Model;

class Banner extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function add_banner($url,$sort,$image,$type){
        $data["image"] = $image;
        $data["url"] = $url;
        $data["sort"] = $sort;
        $data["type"] = $type;
        return self::create($data,true);
    }

    public static function update_banner($url,$sort,$image,$type,$id){
        $data["image"] = $image;
        $data["url"] = $url;
        $data["sort"] = $sort;
        $data["type"] = $type;
        $Banner = new Banner();
        return $Banner->save($data,['id' => $id]);
    }

    public static function delete_banner($id){
        $user = Banner::get($id);
        return $user->delete();
    }

    public static function sort_banner($id,$sort){
        $user = Banner::get($id);
        $user->sort = $sort;
        return $user->save();
    }

}