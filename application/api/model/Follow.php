<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/28
 * Time: 14:47
 */

namespace app\api\model;


use think\Model;

class Follow extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function add($uid,$id){

        $list = self::where(["uid"=>$uid,"gz_uid"=>$id])->find();
        if($list){
            $user = self::get($list["id"]);
            $suee = $user->delete();
            if($suee){
                return [true,1];
            }
            return [false,1];
        }else{
            $data["gz_uid"] = $id;
            $data["uid"] = $uid;
            $suee = self::create($data,true);
            if($suee){
                return [true,2];
            }
            return [false,2];
        }


    }
}