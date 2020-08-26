<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/8
 * Time: 15:39
 */

namespace app\api\api;


use app\api\model\Collection;
use app\api\model\Fabulous;
use app\api\model\Follow;
use app\api\model\News;
use app\api\model\Shopclassify;
use app\api\model\User;
use app\api\model\UserToken;
use think\Controller;

class ApiCommon extends Controller
{
    //密码加密
    public static function encryption_pass($val){
        return md5("789564".$val);
    }

    //根据ip获取地区
    /**
     * 根据用户IP获取用户地理位置
     * $ip  用户ip
     */
    public static function get_position($ip){
        if(empty($ip)){
            return  '缺少用户ip';
        }
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
        $ipContent = file_get_contents($url);
        $ipContent = json_decode($ipContent,true);
        return $ipContent;
    }

    //更新token
    public static function generate_token($id){
       return md5("98756425645".$id);
    }


    //检查是否token过期
    public static function overdue_token($token){
        $list = UserToken::where("token",$token)->find();
        if(!$list){
            return ["code"=>-2,"msg"=>"token过期","data"=>[]];
        }
        if($list["outtime"] < time()){
            return ["code"=>-2,"msg"=>"token过期","data"=>[]];
        }
        return $list["uid"];
    }

    public static function image_url($val){
        return str_replace("\\",'/',"http://localhost:8087".$val);
    }

    //获取页数
    public static function page_count($count,$pagesize){
        $awdaw = $count/$pagesize;
        $p = intval($awdaw);
        if(!is_int($awdaw)){
            $p = $p + 1;
        }
        return $p;
    }

    //获取页数
    public static function day_count($val){
        $awdaw = ($val - time())/(24*60*60);
        $p = intval($awdaw);
        if(!is_int($awdaw)){
            $p = $p + 1;
        }
        return $p;
    }

    //分割图片
    public static function listimage($list){
        $arr = [];
        $list = explode(",",$list);
        foreach ($list as $key=>$value){
            $list[$key] = self::image_url($value);
        }
        return $list;
    }


    //分割图片
    public static function listimage_jiu($list){
        $arr = [];
//        $list = explode(",",$list);
        for ($i = 0;$i<count($list);$i++){
            if($i < 9){
                array_push($arr,$list[$i]);
            }
        }
//        foreach ($list as $key=>$value){
//            if($key < 9){
//
//            }
//        }
        return $arr;
    }

    public static function user_follow($uid,$id){
        $list = Follow::where(["uid"=>$uid,"gz_uid"=>$id])->find();
        if($list){
            return 1;
        }
        return 0;
    }

    public static function article_fabulous($uid,$id){
        $list = Fabulous::where(["uid"=>$uid,"fab_id"=>$id])->find();
        if($list){
            return 1;
        }
        return 0;
    }

    public static function article_collection($uid,$id){
        $list = Collection::where(["uid"=>$uid,"col_id"=>$id])->find();
        if($list){
            return 1;
        }
        return 0;
    }

    public static function all_allcomment($uid){
        $arr = [];
        $list = Fabulous::where(["type"=>2,"uid"=>$uid])->select();
        foreach ($list as $key=>$value){
            array_push($arr,$value["fab_id"]);
        }
        return $arr;
    }

    public static function allcate($id){
       $list = Shopclassify::where("pid",$id)->select();
       $arr = [];
       foreach ($list as $key=>$value){
           array_push($arr,$value["id"]);
       }
//       $list2 = Shopclassify::where("pid","in",$arr)->select();
//       $newarr = [];
//       foreach ($list2 as $key=>$value){
//           array_push($newarr,$value["id"]);
//       }
       return $arr;
    }

    public static function allfollow($id){
        $arr = [];
        $list = Follow::where("uid",$id)->select();
        foreach ($list as $key=>$value){
            array_push($arr,$value["gz_uid"]);
        }
        return $arr;
    }

    public static function setnews($uid,$type,$n_uid,$art_id,$val){
        $data["uid"] = $uid;
        $data["type"] = $type;
        $user = User::where("id",$n_uid)->find();
        if($type == 1){
            $centre = "用户“".$user["nickname"]."”关注了您！";
        }else  if($type == 2){
            $centre = "用户“".$user["nickname"]."”评论了您的文章！内容：".$val;
        }
        $data["centre"] = $centre;
        $data["art_id"] = $art_id;
        $data["n_uid"] = $n_uid;
        News::create($data,true);
    }


    //缓存设计
    public static function getmemcache($name){

    }
}