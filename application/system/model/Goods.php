<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/14
 * Time: 14:28
 */

namespace app\system\model;


use think\Model;

class Goods extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getCtimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public static function add_goods($url,$sort,$image,$name,$price,$original_price,$shop,$shop_name,$discount,$reason,$out_time,$classify_id,$sold,$province){
        $data["image"] = $image;
        $data["url"] = $url;
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["price"] = $price;
        $data["original_price"] = $original_price;
        $data["shop"] = $shop;
        $data["shop_name"] = $shop_name;
        $data["discount"] = $discount;
        $data["reason"] = htmlspecialchars_decode($reason);
        $data["out_time"] = strtotime($out_time);
        $data["classify_id"] = $classify_id;
        $data["sold"] = $sold;
        $data["province"] = $province;
        return self::create($data,true);
    }

    public static function update_goods($url,$sort,$image,$name,$price,$original_price,$shop,$shop_name,$discount,$reason,$out_time,$classify_id,$sold,$province,$id){
        $data["image"] = $image;
        $data["url"] = $url;
        $data["sort"] = $sort;
        $data["name"] = $name;
        $data["price"] = $price;
        $data["original_price"] = $original_price;
        $data["shop"] = $shop;
        $data["shop_name"] = $shop_name;
        $data["discount"] = $discount;
        $data["reason"] = htmlspecialchars_decode($reason);
        $data["out_time"] = strtotime($out_time);
        $data["classify_id"] = $classify_id;
        $data["sold"] = $sold;
        $data["province"] = $province;
        $Goods = new Goods();
        return $Goods->save($data,['id' => $id]);
    }

    public static function delete_goods($id){
        $user = Goods::get($id);
        return $user->delete();
    }

    public static function sort_goods($id,$sort){
        $user = Goods::get($id);
        $user->sort = $sort;
        return $user->save();
    }

}