<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/8
 * Time: 15:01
 */

namespace app\api\api;


use app\api\model\User;
use app\api\model\UserToken;
use think\Controller;
use think\Db;
use think\facade\Validate;


class Token extends Controller
{
    //注册
    public function register(){
        $post = $this->request->param();
        $rule = [
            'username'  => 'require',
            'phone' => 'require|mobile',
            'password' => 'require'
        ];
        $msg = [
            'username.require' => '请填写昵称',
            'phone.require'     => '请填写手机号码',
            'phone.mobile'     => '手机号码格式错误',
            'password.require'   => '请填写密码'
        ];

        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $user = User::where("phone",$post["phone"])->find();
        if($user){
            return json(["code"=>-1,"msg"=>"该手机号码已注册","data"=>[]]);
        }
        $ip = $this->request->ip();
        $suee = User::UserAdd($post["username"],$post["phone"],$post["password"],$ip);
        if($suee){
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }

    //登录
    public function signin(){
        $post = $this->request->param();
        $rule = [
            'phone' => 'require|mobile',
            'password' => 'require'
        ];
        $msg = [
            'phone.require'     => '请填写手机号码',
            'phone.mobile'     => '手机号码格式错误',
            'password.require'   => '请填写密码'
        ];

        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $user = User::where("phone",$post["phone"])->find();
        if(!$user){
            return json(["code"=>-1,"msg"=>"该手机号码未注册","data"=>[]]);
        }
        if($user["pass"] == ApiCommon::encryption_pass($post["password"])){
            return json(["code"=>-1,"msg"=>"密码错误","data"=>[]]);
        }
        $ip = $this->request->ip();
        Db::startTrans();
        $id = $user["id"];
        User::update_ip($ip,$id);
        $token = ApiCommon::generate_token($id);
        $suee = UserToken::update_token($id,$token);
        if($suee){
            Db::commit();
            return json(["code"=>1,"msg"=>"成功","data"=>["token"=>$token]]);
        }
        Db::rollback();
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }

    //判断是否过期
    public function inspect_token(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require'
        ];
        $msg = [
            'token.require'     => 'token为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $where = [['token','=',$post["token"]],["outtime",">",time()]];
        $token = UserToken::where($where)->find();
        if(!$token){
            return json(["code"=>-2,"msg"=>"token过期","data"=>[]]);
        }
        //更新时间
        UserToken::update_token_time($token["id"]);
        return json(["code"=>1,"msg"=>"成功","data"=>[]]);
    }

    //退出
    public function out_token(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require'
        ];
        $msg = [
            'token.require'     => 'token为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $token = UserToken::where("token",$post["token"])->find();
        if(!$token){
            return json(["code"=>-1,"msg"=>"推出失败","data"=>[]]);
        }
        $suee = UserToken::where("id",$token["id"])->delete();
        if($suee){
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
    }

    public function get_data(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require'
        ];
        $msg = [
            'token.require'     => 'token为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = User::field("id,nickname,head,introduction,gender,region,birthday,cover")->get($uid);
        $list["head"] = ApiCommon::image_url($list["head"]);
        $list["cover"] = ApiCommon::image_url($list["cover"]);
        $list["birthday"] = date("Y-m-d",$list["birthday"]);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list]]);
    }

    //更新
    public function update_data(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'type'=>'require',
            'val'=>'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
            'type.require'     => 'type为空',
            'val.require' => 'val为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        if(!in_array($post["type"],[1,2,3,4,5,6,7])){
            return json(["code"=>-1,"msg"=>"type类型错误","data"=>[]]);
        }
        $suee = User::update_information($uid,$post["type"],$post["val"]);
        if($suee){
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }
}