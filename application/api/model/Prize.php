<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/7
 * Time: 20:08
 */

namespace app\api\model;


use think\Db;
use think\Model;

class Prize extends Model
{
    public static function getprizeid(){
        $prizes = self::where("mun",">",0)->order("sort")->limit(8)->lock(true)->select();
        $data = [];
        foreach ($prizes as $prize) {
            if (($prize['mun']) > 0) {
                $data[$prize['id']] = $prize['rate'];
            }
        };
        //获取所有奖品的总概率
        $sum = array_sum($data);
        $result = 0;
        //概率数组总精度
        //概率数组循环
        foreach ($data as $key => $value) {
            $randNum = mt_rand(1, $sum);
            if ($randNum <= $value) {
                $result = $key;
                break;
            } else {
                $sum -= $value;
            }
        }
        return $result;//抽到的ID值,0表示没有抽到奖品
    }

    public static function cchou($uid){

        $prize_id = self::getprizeid();
        //先减
        Db::startTrans();
        //锁死表 数量为0
        $prize = Prize::where("id",$prize_id)->lock(true)->find();
        if($prize["mun"] <= 0){
            Db::rollback();
            return [false,$prize_id];
        }
        //添加奖品记录
        $suee = PrizeRecord::add($uid,$prize_id);
        //添加用户记录
        $suee2 = SugarRecord::setsighr($uid,2,2,10);//消耗记录
        //奖品减
        $suee4 = Prize::where("id",$prize_id)->setDec("mun");
        $suee5 = Prize::where("id",$prize_id)->setInc("prize_num");
        $suee3 = SugarRecord::setsighr($uid,3,1,$prize["muber"]);//中奖记录
        $prize2 = Prize::where("id",$prize_id)->lock(true)->find();
        if($prize2["mun"] <= 0){
            Db::rollback();
            return [false,$prize_id];
        }
        if($suee && $suee2 && $suee3 && $suee4 && $suee5){
            Db::commit();
            return [true,$prize_id];
        }
        Db::rollback();
        return [false,$prize_id];
    }

}