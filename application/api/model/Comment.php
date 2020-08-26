<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/28
 * Time: 13:40
 */

namespace app\api\model;


use think\Db;
use think\Model;

class Comment extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function add($uid,$id,$centre){
        $data["article_id"] = $id;
        $data["uid"] = $uid;
        $data["centre"] = $centre;
        Db::startTrans();
        $suee2 = Article::where("id",$id)->setInc("comment");
       $suee = self::create($data,true);
       if($suee2 && $suee){
           Db::commit();
           return true;
       }
       Db::rollback();
       return false;
    }

}