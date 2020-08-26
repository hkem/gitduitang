<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/21
 * Time: 20:24
 */

namespace app\api\model;


use think\Model;

class Article extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function add($uid,$name,$image,$centre,$type,$classify_id){
        $data["uid"] = $uid;
        $data["name"] = $name;
        $data["image"] = $image;
        $data["centre"] = htmlspecialchars_decode($centre);
        $data["type"] = $type;
        $data["classify_id"] = $classify_id;
        $user = User::get($uid);
        if($user["is_own"] == 1){
            $data["state"] = 1;
        }
        return self::create($data,true);
    }

}