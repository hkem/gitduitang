<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/28
 * Time: 16:13
 */

namespace app\api\model;


use think\Model;

class Collection extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function add($uid,$id){

        $list = self::where(["uid"=>$uid,"col_id"=>$id])->find();
        if($list){
            $user = self::get($list["id"]);
            $suee = $user->delete();
            $suee2 = Article::where("id",$id)->setDec("collection");
            if($suee && $suee2){
                return [true,1];
            }
            return [false,1];
        }else{
            $data["col_id"] = $id;
            $data["uid"] = $uid;
            $suee = self::create($data,true);
            $suee2 = Article::where("id",$id)->setInc("collection");
            if($suee && $suee2){
                return [true,2];
            }
            return [false,2];
        }


    }

}