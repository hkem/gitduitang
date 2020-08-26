<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/8
 * Time: 15:26
 */

namespace app\api\model;


use app\api\api\ApiCommon;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    public static function UserAdd($nickname,$phone,$pass,$ip){
        $data["head"] = "/upload/duitang/image/head.png";
        $data["nickname"] = $nickname;
        $data["introduction"] = "开心每一天";
        $data["gender"] = 1;
        $data["region"] = "";
        $data["birthday"] = strtotime(date('Y-m-d',time()));
        $data["phone"] = $phone;
        $data["pass"] = ApiCommon::encryption_pass($pass);
        $data["ip"] = $ip;
        $data["sign_time"] = time();
        $data["cover"] = "/upload/duitang/image/cover.jpg";
        return self::create($data,true);
    }


    //更新用户ip   更新时间
    public static function update_ip($ip,$id){
        $user = User::get($id);
        $user->ip = $ip;
        $user->sign_time  = time();
        $user->save();
    }

    public static function update_information($id,$type,$val){
        $user = User::get($id);
        if($type == 1){
            $user->nickname = $val;
        }else if($type == 2){
            $user->introduction = $val;
        }else if($type == 3){
            $user->gender = $val;
        }else if($type == 4){
            $user->region = $val;
        }else if($type == 5){
            $user->birthday = strtotime($val);
        }else if($type == 6){
            $user->head = $val;
        }else if($type == 7){
            $user->cover = $val;
        }
        return $user->save();
    }

}