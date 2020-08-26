<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/17
 * Time: 13:16
 */

namespace app\api\api;


use think\Controller;

class Image extends Controller
{
    public function upload_image(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        if(!$file){
            return json(["code"=>-1,"msg"=>"请选择图片","data"=>[]]);
        }
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->rule('md5')->validate(['size'=>1024*10*1024,'ext'=>'jpg,jpeg,png,gif'])->move( './upload/sys/image');
        if($info){
            // 成功上传后 获取上传信息
            $name = "/upload/sys/image/".$info->getSaveName();
            return json(["code"=>1,"msg"=>"成功","data"=>["name"=>$name,"url_name"=>ApiCommon::image_url($name)]]);
        }else{
            // 上传失败获取错误信息
            return json(["code"=>-1,"msg"=>$file->getError(),"data"=>[]]);
        }
    }

}