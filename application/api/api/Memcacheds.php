<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/23
 * Time: 12:39
 */

namespace app\api\api;


use think\cache\driver\Memcached;
use think\Controller;

class Memcacheds extends Controller
{
    //声明静态成员变量
    private static $m = null;
    public function __construct() {
        try{
            self::$m = new \think\cache\driver\Memcache();             //创建一个memcache对象         //创建一个memcache对象
            self::$m->connect('localhost', 11211) or die ("Could not connect"); //连接Memcached服务器
            return self::$m;
        }catch (\Exception $e){

        }

    }

//    //缓存数据
//    public static function getset($key,$val,$time){
//        $key = md5($key);
//        if(self::$m != null) {
//            //判断是否存在
//            if (!self::$m->add($key,$val,$time)) {
//                //存在返回
//                return self::$m->get($key);
//            }else{
//                //不存在返回
//
//            }
//        }
//    }

    public static function addMen($key){
        if(self::$m != null){
            if(self::$m->add(md5($key),80) != 1){
                return true;
            };
            return false;
        }
        return false;
    }


    /*
     * 加入缓存数据
     * @param string $key 获取数据唯一key
     * @param String||Array $value 缓存数据
     * @param $time memcache生存周期(秒)
     */    public static function setMen($key,$value,$time){
                if(self::$m != null){
                    self::$m->set(md5($key),json_encode($value),$time);
                }
            }
    /*
     * 获取缓存数据
     * @param string $key
     * @return     */
    public static function getMen($key){
        if(self::$m != null){
            return self::$m->get(md5($key));
        }
    }
    /*
     * 删除相应缓存数据
     * @param string $key
     * @return     */
    public static function delMen($key){
        if(self::$m != null) {
            self::$m->delete($key);
        }
    }
    /*
     * 删除全部缓存数据
     */
    public static function delAllMen(){
        if(self::$m != null) {
            self::$m->flush();
        }
    }

    /*
     * 删除全部缓存数据
     */
    public static function menStatus(){
        if(self::$m != null) {
            return self::$m->getStats();
        }
    }

}