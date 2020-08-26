<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/31
 * Time: 16:24
 */

namespace app\system\model;


use think\Model;

class Article extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function sort_articl($id,$sort){
        $user = self::get($id);
        $user->sort = $sort;
        return $user->save();
    }

    public static function show_articl($id,$show){
        $user = self::get($id);
        $user->show = $show;
        return $user->save();
    }
    public static function delete_articl($id){
        $user = self::get($id);
        return $user->delete();
    }
}