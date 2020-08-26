<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/8
 * Time: 20:37
 */

namespace app\api\model;


use think\Model;

class UserToken extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public static function update_token($id,$token){
        $usertoke  = self::where("uid",$id)->find();
        if($usertoke){
            return self::update_token_time($usertoke["id"]);
        }
        $data["uid"] = $id;
        $data["token"] = $token;
        $data["outtime"] = time() + 365*24*60*60;
        return self::create($data,true);
    }

    //更新token时间
    public static function update_token_time($id){
        $user = self::get($id);
        $user->outtime = time() + 365*24*60*60;
        return $user->save();
    }
}