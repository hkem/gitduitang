<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/21
 * Time: 0:37
 */

namespace app\api\model;


use app\api\api\ApiCommon;
use think\Model;

class Classify extends Model
{
    public static function get_recursion($array,$parent_id=0){
        $new=[];
        foreach($array as $k=>$v){
            if($v['pid']==$parent_id){
                $v['children']=self::get_recursion($array,$v['id']);
                if (!$v['children']) {
                    unset($v['children']);
                }
                $new[] = $v;
            }
        }
        return $new;
    }

    public static function get_cateall()
    {
        $list = self::select();
        return self::get_recursion($list, 0);
    }


    public static function get_cate_list(){
        $list = self::get_cateall();
        foreach ($list as $key=>$value){
            $list[$key]["text"] = $value["name"];
            $list[$key]["isselect"] = 0;
            if(isset($value["children"])){
                $list2 = self::sort_list($value["children"]);
                foreach ($list2 as $kk=>$vv) {
                    if (isset($vv["children"])) {
                        $list2[$kk]["text"] = $vv["name"];
                        $list3 = self::sort_list($vv["children"]);
                        foreach ($list3 as $kkk => $vvv) {
                            $list3[$kkk]["text"] = $vvv["name"];
                            $list3[$kkk]["image"] = ApiCommon::image_url($vvv["image"]);
                        }
                        $list2[$kk]["children"] = $list3;
                    } else {
                        $list2[$kk]["children"] = [];
                    }
                }
                $list[$key]["children"] = $list2;
            }else{
                $list[$key]["children"] = [];
            }

        }
        return self::sort_list($list);
    }


    //排序
    public static function sort_list($list){
        $arr = [];
        $ad = [];
        foreach ($list as $key=>$value){
            $arr[$value["id"]] = $value["sort"];
            $ad[$value["id"]] = $value;
        }
        asort($arr);
        $newarr = [];
        foreach ($arr as $key=>$value) {
            array_push($newarr,$ad[$key]);
        }
        return $newarr;
    }




}