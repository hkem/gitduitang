<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/8
 * Time: 13:56
 */

namespace app\api\model;


use think\Model;

class PrizeRecord extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public static function add($uid,$prize_id){
        $date["uid"] = $uid;
        $date["prize_id"] = $prize_id;
        return self::create($date,true);
    }

}