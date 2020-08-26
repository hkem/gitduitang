<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/14
 * Time: 12:11
 */

namespace app\system\model;


use think\Model;

class Shopclassify extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    public function getStatusAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }
    public static function add_classify($image,$pid,$sort,$name){
        $data["image"] = $image;
        $data["pid"] = $pid;
        $data["sort"] = $sort;
        $data["name"] = $name;
        return self::create($data,true);
    }
    public static function update_classify($image,$pid,$sort,$name,$id){
        $data["image"] = $image;
        $data["pid"] = $pid;
        $data["sort"] = $sort;
        $data["name"] = $name;
        $classify = new Shopclassify();
        return $classify->save($data,["id"=>$id]);
    }

    public static function delete_classify($id){
        $user = self::get($id);
        return $user->delete();
    }

    public static function sort_classify($id,$sort){
        $user = self::get($id);
        $user->sort = $sort;
        return $user->save();
    }

    public static function recursion($array,$parent_id=0){
        $new=[];
        foreach($array as $k=>$v){
            if($v['pid']==$parent_id){
                $v['list']=self::recursion($array,$v['id']);
//                if (!$v['list']) {
//                    unset($v['list']);
//                }
                $new[] = $v;
            }
        }
        return $new;
    }

    public static function get_all(){
        $list = self::select();
        return self::recursion($list,0);
//        $arr = [];
//        foreach ($list as $key=>$value){
//            if($value["pid"] == 0){
//                $value["list"] = [];
//                $value["type"] = 1;
//                $arr[$value["id"]] = $value;
//            }
//        }
//        foreach ($list as $key=>$value){
//            if($value["pid"] !== 0){
//                if($arr[$value["pid"]]){
//                    $awd = $arr[$value["pid"]]["list"];
//                    $value["type"] = 2;
//                    array_push($awd,$value);
//                    $arr[$value["pid"]]["list"] = $awd;
//                }
//            }
//        }
//        return $arr;
    }

    public static function get_pid2($id){
        if($id == 0){
            return 0;
        }
        $class = self::get($id);
        return $class["pid"] == 0 ? 0 : $class["pid"];
    }

    public static function get_list(){
        $list = self::get_all();
        $arr = [];
        foreach ($list as $key=>$value){
            $value["type"] = 1;
            array_push($arr,$value);
            if(isset($value["list"])){
                $list2 = $value["list"];
                foreach ($list2 as $kk=>$vv){
                    if(isset($vv["list"])) {
                        $vv["type"] = 2;
                        array_push($arr,$vv);
                        $list3 = $vv["list"];
                        foreach ($list3 as $kkk=>$vvv){
                            $vvv["type"] = 3;
                            array_push($arr,$vvv);
                        }
                    }

                }
            }


        }
        return $arr;
    }

}