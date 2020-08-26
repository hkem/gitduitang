<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/7
 * Time: 10:48
 */

namespace app\api\model;


use think\Db;
use think\Model;

class SugarRecord extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    //
    public static function setsign_count($uid){
        $time = strtotime(date("Y-m-d")) - 24 * 60 * 60;
        $list = self::where(["uid"=>$uid,"type"=>1,"time"=>$time])->find();
        $jintime = strtotime(date("Y-m-d"));
        $jinlist = self::where(["uid"=>$uid,"type"=>1,"time"=>$jintime])->find();
        if(!$list){
            //判断今天是否签
            if(!$jinlist){
                User::where("id",$uid)->update(["sign_count"=>0]);
            }
            return false;
        }
        //签到满七天
        $user = User::where("id",$uid)->find();
        if($user["sign_count"] >= 7 && !$jinlist){
            User::where("id",$uid)->update(["sign_count"=>0]);
            return false;
        }
        return true;
    }

    public static function is_qd($uid){
        $time = strtotime(date("Y-m-d"));
        $list = self::where(["uid"=>$uid,"type"=>1,"time"=>$time])->find();
        if($list){
            return true;
        }
        return false;
    }

    //获取签到第几天
    public static function getsignday($uid){
        $time = strtotime(date("Y-m-d",time())) - 24 * 60 * 60;
        $list = self::where(["uid"=>$uid,"type"=>1,"time"=>$time])->find();
        $jintime = strtotime(date("Y-m-d"));
        $jinlist = self::where(["uid"=>$uid,"type"=>1,"time"=>$jintime])->find();
        if(!$list){

            if(!$jinlist){
                User::where("id",$uid)->update(["sign_count"=>0]);
            }
            return 1;
        }
        //签到满七天
        $user = User::where("id",$uid)->find();
        if($user["sign_count"] >= 7 && !$jinlist){
            User::where("id",$uid)->update(["sign_count"=>0]);
            return 1;
        }
        return $user["sign_count"] + 1;
    }
    //$leixing = 1 加，2减
    public static function setsighr($uid,$type,$leixing,$munber){
        $suee3 = true;
        $suee2 = true;
        $time = strtotime(date("Y-m-d",time()));
        Db::startTrans();
        if($type == 1){
            //签到
            $qdday = self::getsignday($uid);
            $arr = array("1"=>"10","2"=>"20","3"=>"30","4"=>"40","5"=>"50","6"=>"60","7"=>"70");
            $munber = $arr[$qdday];
            $centre = $qdday == 1 ? "每日签到" : "连续签到".$qdday."次";
            $suee3 = User::where("id",$uid)->setInc("sign_count");
        }else if($type == 2){
            //抽奖消耗
            $centre = "参与抽奖消耗";
        }else if($type == 3){
            //抽奖获得
            $centre = "中奖获得";
        }
        if($leixing == 1){
            $suee2 = User::where("id",$uid)->setInc("sugar",$munber);
        }else if($leixing == 2){
            $suee2 = User::where("id",$uid)->setDec("sugar",$munber);
        }
        $data["uid"] = $uid;
        $data["type"] = $type;
        $data["centre"] = $centre;
        $data["munber"] = $munber;
        $data["time"] = $time;
        $suee = self::create($data,true);
        if($suee && $suee2 && $suee3){
            Db::commit();
            return [true,$munber];
        }
        Db::rollback();
        return [false,$munber];

    }

}