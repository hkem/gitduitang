<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/28
 * Time: 15:42
 */

namespace app\api\model;


use think\Model;

class Fabulous extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function add($uid,$id){
        $list = self::where(["uid"=>$uid,"fab_id"=>$id,"type"=>1])->find();
        if($list){
            $user = self::get($list["id"]);
            $suee = $user->delete();
            $suee2 = Article::where("id",$id)->setDec("fabulous");
            if($suee && $suee2){
                return [true,1];
            }
            return [false,1];
        }else{
            $data["fab_id"] = $id;
            $data["uid"] = $uid;
            $data["type"] = 1;
            $suee = self::create($data,true);
            $suee2 = Article::where("id",$id)->setInc("fabulous");
            if($suee && $suee2){
                return [true,2];
            }
            return [false,2];
        }
    }

    public static function comment_add($uid,$id){
        $list = self::where(["uid"=>$uid,"fab_id"=>$id,"type"=>2])->find();
        if($list){
            $user = self::get($list["id"]);
            $suee = $user->delete();
            $suee2 = Comment::where("id",$id)->setDec("fabulous");
            if($suee && $suee2){
                return [true,1];
            }
            return [false,1];
        }else{
            $data["fab_id"] = $id;
            $data["uid"] = $uid;
            $data["type"] = 2;
            $suee = self::create($data,true);
            $suee2 = Comment::where("id",$id)->setInc("fabulous");
            if($suee && $suee2){
                return [true,2];
            }
            return [false,2];
        }
    }

}