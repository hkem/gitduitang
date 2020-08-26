<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/6
 * Time: 22:24
 */

namespace app\api\api;


use app\api\model\Article;
use app\api\model\Banner;
use app\api\model\Classify;
use app\api\model\Collection;
use app\api\model\Comment;
use app\api\model\Fabulous;
use app\api\model\Follow;
use app\api\model\Goods;
use app\api\model\Grass;
use app\api\model\News;
use app\api\model\Prize;
use app\api\model\PrizeRecord;
use app\api\model\Search;
use app\api\model\Setup;
use app\api\model\Shopclassify;
use app\api\model\SugarRecord;
use app\api\model\User;
use think\cache\driver\Memcached;
use think\cache\driver\Redis;
use think\Controller;
use think\Db;
use think\facade\Validate;
use think\session\driver\Memcache;

class Index extends Controller
{

    public function index(){
        $ip = "120.85.78.219";
        $ip = '219.134.104.255';
                 $durl = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
          // 初始化
         $curl = curl_init();
         // 设置url路径
         curl_setopt($curl, CURLOPT_URL, $durl);
         // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
         // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
         curl_setopt($curl, CURLOPT_BINARYTRANSFER, true) ;
         // 执行
         $data = curl_exec($curl);
        // 关闭连接
         curl_close($curl);



        return json(["code"=>1,"msg"=>"成功","data"=>$data]);
    }
    public function get_cate(){
        $data = Classify::get_cate_list();
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$data]]);
    }

    public function translate(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'name'=>'require',
            'image'=>'require',
            'classify_id'=>'require'
        ];
        $msg = [
            'token.require'     => 'token为空',
            'name.require'     => '标题为空',
            'image.require'     => '图片为空',
            'classify_id.require'     => '分类为空',
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
        $suee = Article::add($uid,$post["name"],$post["image"],"",1,$post["classify_id"]);
        if($suee){
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }
    public function translate_articele(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'name'=>'require',
            'image'=>'require',
            'classify_id'=>'require',
            'centre'=>'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
            'name.require'     => '标题为空',
            'image.require'     => '图片为空',
            'classify_id.require'     => '分类为空',
            'centre.require'     => '内容为空',
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
        $suee = Article::add($uid,$post["name"],$post["image"],$post["centre"],2,$post["classify_id"]);
        if($suee){
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }

    public function find(){
        //轮播
        $banner = Banner::where("type",2)->order("sort")->select();
        foreach ($banner as $key=>$value){
            $banner[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        //分类
        $data = Classify::get_cate_list();
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$data,"banner"=>$banner]]);
    }


    //值得 获取分类 种草
    public function get_worth(){
        //轮播
        $banner = Banner::where("type",3)->order("sort")->select();
        foreach ($banner as $key=>$value){
            $banner[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        $data = Grass::where("recommend",1)->alias("G")->join('dt_user C','G.uid = C.id')->field("G.id id,G.name name,G.image image,G.readq readq,G.collection collection,C.head head,C.nickname nickname")
            ->limit(5)->select();
        foreach ($data as $key=>$value){
            $data[$key]["image"] = ApiCommon::image_url($value["image"]);
            $data[$key]["head"] = ApiCommon::image_url($value["head"]);
        }
        //分类
        $cate = Shopclassify::where("pid",0)->order("sort")->select();
        foreach ($cate as $key=>$value){
            $cate[$key]["isselect"] = 0;
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$data,"banner"=>$banner,"cate"=>$cate]]);
    }

    public function get_goods(){
        $post = $this->request->param();
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $list = Goods::where("out_time",">=",time())->page($p,$page)->field("id,name,image,price,original_price,shop,discount")->select();
        $listall = Goods::where("")->count();
        $pagecount = ApiCommon::page_count($listall,$page);
        foreach ($list as $key=>$value){
            $list[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    public function get_recommend_banner(){
        //轮播
        $banner = Banner::where("type",1)->order("sort")->cache("Bannerrecommend",3600)->select();
        foreach ($banner as $key=>$value){
            $banner[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["banner"=>$banner]]);
    }

    public function get_article(){
        $post = $this->request->param();
        $rule = [
            'type' => 'require',
            'id'=>'require'
        ];
        $msg = [
            'type.require'     => 'type为空',
            'id.require'     => 'id为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        if(!in_array($post["type"],[1,2,3,0,4,5])){
            return json(["code"=>-1,"msg"=>"type错误","data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] !== 0){
                $p = $post["p"];
            }
        }
        $where = [];
        if($post["type"] == 4){
            $Classify = Classify::where("id",$post["id"])->cache("Classify",3600)->find();
            if(!$Classify){
                return json(["code"=>-1,"msg"=>"id错误","data"=>[]]);
            }
            $where[] = ["classify_id","=",$post["id"]];
        }else if($post["type"] == 5){
           if(!$this->request->has("val")){
               return json(["code"=>-1,"msg"=>"val错误","data"=>[]]);
           }
            $where[] = ["name","like","%".$post["val"]."%"];
        }else{
            $where[] = ["recommend","=",$post["type"]];
        }
        $where[] = ["show","=",1];
        $where[] = ["state","=",1];

        $memcached  = new Memcacheds();
        if($memcached::addMen("Article_".$post["type"]."_".$p)){
            $list = $memcached::getMen("Article_".$post["type"]."_".$p);
            $list = json_decode($list,true);
            var_dump($list);
        }else{
            $list = Article::where($where)->alias("A")->join('dt_user C','A.uid = C.id')->page($p,$page)
                ->field("A.id id,A.name name,A.image image,A.type type,A.fabulous fabulous,A.comment comment,C.head head,C.nickname nickname")
                ->order("A.ctime desc")->select();//cache("Article_".$post["type"]."_".$p,3600)
            $memcached::setMen("Article_".$post["type"]."_".$p,$list,3600);
        }

        $listall = Article::where($where)->count();
        $pagecount = ApiCommon::page_count($listall,$page);
        foreach ($list as $key=>$value){
            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["imgjiu"] = 0;
            $list[$key]["imgjiucount"] = 0;
            $list[$key]["imgcount"] = 0;
            if($value["type"] == 1){
                $count = ApiCommon::listimage($value["image"]);
                if(count($count) > 9){
                    $list[$key]["listimage"] = ApiCommon::listimage_jiu($count);
                }else{
                    $list[$key]["listimage"] = $count;
                }

                $list[$key]["imgjiu"] = count($count)> 9 ? 1 : 0;
                $list[$key]["imgjiucount"] = count($count)> 9 ? count($count) - 9 : 0;
                $list[$key]["imgcount"] = count($count);
            }else{
                $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            }
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount,"post"=>$post]]);
    }

    public function article_dateils(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require'
        ];
        $msg = [
            'id.require'     => 'id为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $list  = Article::where("A.id",$post["id"])->alias("A")->join('dt_user C','A.uid = C.id')
            ->field("A.id id,A.uid uid,A.name name,A.centre centre,A.image image,A.type type,A.collection collection,A.fabulous fabulous,A.comment comment,A.ctime ctime,C.head head,C.nickname nickname")
            ->find();
        $list["imgcount"] = 0;
        $list["is_follow"] = 0;
        $list["is_fabulous"] = 0;
        $list["is_collection"] = 0;
        $list["head"] = ApiCommon::image_url($list["head"]);
        $list["ctime"] = date("Y-m-d H:i:s",$list["ctime"]);
        if($list["type"] == 1){
            $count = ApiCommon::listimage($list["image"]);
            $list["image"] = $count;
            $list["imgcount"] = count($count);
        }else{
            $list["image"] = ApiCommon::image_url($list["image"]);
        }
        $allcomment = [];
        if($this->request->has("token")){
            $uid = ApiCommon::overdue_token($post["token"]);
            if(!is_array($uid)){
                //判断是否已关注
                $list["is_follow"] = ApiCommon::user_follow($uid,$list["uid"]);
                //判断是否收藏
                $list["is_collection"] = ApiCommon::article_collection($uid,$list["id"]);
                //判断是否点赞
                $list["is_fabulous"] = ApiCommon::article_fabulous($uid,$list["id"]);
                //获取所有点赞数
                $allcomment = ApiCommon::all_allcomment($uid);
            }
        }
        //前五条评论
        $comment = Comment::where("article_id",$post["id"])->alias("C")->join('dt_user U','C.uid = U.id')
            ->field("C.id id,C.uid uid,C.centre centre,C.fabulous fabulous,U.head head,U.nickname nickname,C.ctime ctime")->limit(5)->order("C.fabulous desc")->select();
        $commentcount = Comment::where("article_id",$post["id"])->count();

        foreach ($comment as $key=>$value){
            $comment[$key]["head"] = ApiCommon::image_url($value["head"]);
            $comment[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
            $comment[$key]["is_fabulous"] = in_array($value["id"],$allcomment) ? 1 : 0;

        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"comment"=>$comment,"commentcount"=>$commentcount]]);
    }

    public function comment(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'token' => 'require',
            'centre' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'token.require'     => 'token为空',
            'centre.require'     => '内容为空'
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
        $Article = Article::where("id",$post["id"])->find();
        if(!$Article){
            return json(["code"=>-1,"msg"=>"文章不存在","data"=>[]]);
        }
        Db::startTrans();
        $suee = Comment::add($uid,$post["id"],$post["centre"]);
        if($suee){
            ApiCommon::setnews($Article["uid"],2,$uid,$Article["id"],$post["centre"]);
            return json(["code"=>1,"msg"=>"成功","data"=>[]]);
        }
        return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
    }

    //关注
    public function follow(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'token' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'token.require'     => 'token为空',
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
        $user = User::where("id",$post["id"])->find();
        if(!$user){
            return json(["code"=>-1,"msg"=>"用户不存在","data"=>[]]);
        }
        $suee = Follow::add($uid,$post["id"]);
        if($suee[0]){
            if($suee[1] == 1){
                return json(["code"=>1,"msg"=>"取消成功","data"=>[]]);
            }
            if($suee[1] == 2){
                ApiCommon::setnews($post["id"],1,$uid,0,"");
                return json(["code"=>2,"msg"=>"关注成功","data"=>[]]);

            }
        }
        if($suee[1] == 1){
            return json(["code"=>3,"msg"=>"取消失败","data"=>[]]);
        }
        if($suee[1] == 2){
            return json(["code"=>4,"msg"=>"关注失败","data"=>[]]);
        }
    }
    //赞
    public function fabulous(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'token' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'token.require'     => 'token为空',
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
        $Article = Article::where("id",$post["id"])->find();
        if(!$Article){
            return json(["code"=>-1,"msg"=>"文章不存在","data"=>[]]);
        }
        $suee = Fabulous::add($uid,$post["id"]);
        if($suee[0]){
            if($suee[1] == 1){
                return json(["code"=>1,"msg"=>"取消成功","data"=>[]]);
            }
            if($suee[1] == 2){
                return json(["code"=>2,"msg"=>"点赞成功","data"=>[]]);
            }
        }
        if($suee[1] == 1){
            return json(["code"=>3,"msg"=>"取消失败","data"=>[]]);
        }
        if($suee[1] == 2){
            return json(["code"=>4,"msg"=>"点赞失败","data"=>[]]);
        }
    }

    public function collection(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'token' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'token.require'     => 'token为空',
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
        $Article = Article::where("id",$post["id"])->find();
        if(!$Article){
            return json(["code"=>-1,"msg"=>"文章不存在","data"=>[]]);
        }
        $suee = Collection::add($uid,$post["id"]);
        if($suee[0]){
            if($suee[1] == 1){
                return json(["code"=>1,"msg"=>"取消成功","data"=>[]]);
            }
            if($suee[1] == 2){
                return json(["code"=>2,"msg"=>"收藏成功","data"=>[]]);
            }
        }
        if($suee[1] == 1){
            return json(["code"=>3,"msg"=>"取消失败","data"=>[]]);
        }
        if($suee[1] == 2){
            return json(["code"=>4,"msg"=>"收藏失败","data"=>[]]);
        }
    }


    //评论点赞
    public function comment_fabulous(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'token' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'token.require'     => 'token为空',
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
        $Article = Article::where("id",$post["id"])->find();
        if(!$Article){
            return json(["code"=>-1,"msg"=>"文章不存在","data"=>[]]);
        }
        $suee = Fabulous::comment_add($uid,$post["id"]);
        if($suee[0]){
            if($suee[1] == 1){
                return json(["code"=>1,"msg"=>"取消成功","data"=>[]]);
            }
            if($suee[1] == 2){
                return json(["code"=>2,"msg"=>"点赞成功","data"=>[]]);
            }
        }
        if($suee[1] == 1){
            return json(["code"=>3,"msg"=>"取消失败","data"=>[]]);
        }
        if($suee[1] == 2){
            return json(["code"=>4,"msg"=>"点赞失败","data"=>[]]);
        }
    }

    public function allcomment(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $allcomment = [];
        if($this->request->has("token")){
            $uid = ApiCommon::overdue_token($post["token"]);
            if(!is_array($uid)){
                //获取所有点赞数
                $allcomment = ApiCommon::all_allcomment($uid);
            }
        }
        //前五条评论
        $comment = Comment::where("article_id",$post["id"])->alias("C")->join('dt_user U','C.uid = U.id')
            ->field("C.id id,C.uid uid,C.centre centre,C.fabulous fabulous,U.head head,U.nickname nickname,C.ctime ctime")->page($p,$page)->order("C.fabulous desc")->select();
        $listall = Comment::where("article_id",$post["id"])->count();

        foreach ($comment as $key=>$value){
            $comment[$key]["head"] = ApiCommon::image_url($value["head"]);
            $comment[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
            $comment[$key]["is_fabulous"] = in_array($value["id"],$allcomment) ? 1 : 0;

        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$comment,"listcount"=>$pagecount]]);
    }

    public function goodsdateils(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $goods = Goods::where("id",$post["id"])->where("out_time",">=",time())->field("id,name,image,price,original_price,shop,shop_name,discount,reason,out_time,url,sold,province")->find();
        if(!$goods){
            return json(["code"=>-1,"msg"=>"商品不存在","data"=>[]]);
        }
        $goods["image"] = ApiCommon::image_url($goods["image"]);
        //处理时间
        $goods["out_time"] = ApiCommon::day_count($goods["out_time"])."天";
        //推荐
        $yuijian = Goods::where("id","<>",$post["id"])->where("out_time",">=",time())->limit(20)->field("id,name,image,price,shop,discount,sold")->select();
        foreach ($yuijian as $key=>$value){
            $yuijian[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["goods"=>$goods,"list"=>$yuijian]]);
    }

    //分类商品
    public function goodscate(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $clss = Shopclassify::where("id",$post["id"])->find();
        if(!$clss){
            return json(["code"=>-1,"msg"=>"成功","data"=>[]]);
        }
        $shop = Shopclassify::where("pid",$post["id"])->field("id,name,image")->select();
        foreach ($shop as $key=>$value){
            $shop[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["shop"=>$shop]]);
    }

    public function goodslist(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
            'type'=>'require',
            'p'=>'require',
            'val'=>'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
            'type.require'     => 'type为空',
            'p.require'     => 'p为空',
            'val.require'     => 'val为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $where = [];
        $where[] = ["out_time",">=",time()];
        if($post["type"] == 1){
            $shop = Shopclassify::where("id",$post["id"])->find();
            if(!$shop){
                return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
            }
            $allcate = ApiCommon::allcate($post["id"]);

            $where[] = ["classify_id","in",$allcate];
        }else if($post["type"] == 2){
            $where[] = ["classify_id","=",$post["id"]];
        }else if($post["type"] == 3){
            $where[] = ["name","like","%".$post["val"]."%"];
        }
        $goods = Goods::where($where)->page($p,$page)->field("id,name,image,price,original_price,shop,shop_name,discount,reason,out_time,url,sold,province")->select();
        foreach ($goods as $key=>$value){
            $goods[$key]["image"] = ApiCommon::image_url($value["image"]);
        }
        $goodscount =  Goods::where($where)->count();
        $pagecount = ApiCommon::page_count($goodscount,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$goods,"pagecount"=>$pagecount]]);
    }

    //种草列表
    public function grsslist(){
        $post = $this->request->param();
        $rule = [

            'type'=>'require'
        ];
        $msg = [

            'type.require'     => 'type为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $where = [];
        if($post["type"] == 1){
            $where[] = ["type","=","1"];
        }else if($post["type"] == 2){
            $where[] = ["type","=","2"];
        }else if($post["type"] == 3){
            $where[] = ["recommend","=","1"];
        }
        $list = Grass::where($where)->alias("G")->join('dt_user U','G.uid = U.id')->page($p,$page)->field("G.id id,G.image image,G.name name,G.readq readq,U.head head,U.nickname nickname")->order("G.ctime desc")->select();
        foreach ($list as $key=>$value){
            $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
        }
        $grasslist =  Grass::where($where)->count();
        $pagecount = ApiCommon::page_count($grasslist,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"pagecount"=>$pagecount]]);
    }

    public function  grssdateils(){
        $post = $this->request->param();
        $rule = [
            'id'=>'require'
        ];
        $msg = [
            'id.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $grass = Grass::where("G.id",$post["id"])->alias("G")->join('dt_user U','G.uid = U.id')->field("G.id id,G.image image,G.centre centre,G.ctime ctime,G.name name,G.readq readq,U.head head,U.nickname nickname")->find();
        if(!$grass){
            return json(["code"=>-1,"msg"=>"失败","data"=>[]]);
        }
        Grass::where("id",$grass["id"])->setInc("readq");

        $grass["image"] = ApiCommon::image_url($grass["image"]);
        $grass["head"] = ApiCommon::image_url($grass["head"]);
        $grass["ctime"] = date("Y-m-d H:i:s",$grass["ctime"]);
        return json(["code"=>1,"msg"=>"成功","data"=>["centre"=>$grass]]);
    }

    public function get_token_article(){

        $post = $this->request->param();
        $rule = [
            'token'=>'require'
        ];
        $msg = [
            'token.require'     => 'token为空',
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
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] !== 0){
                $p = $post["p"];
            }
        }
        //获取全部关注id
        $all = ApiCommon::allfollow($uid);
        $where = [];
        $where[] = ["uid","in",$all];
        $where[] = ["show","=",1];
        $where[] = ["state","=",1];
        $list = Article::where($where)->alias("A")->join('dt_user C','A.uid = C.id')->page($p,$page)->field("A.id id,A.name name,A.image image,A.type type,A.fabulous fabulous,A.comment comment,C.head head,C.nickname nickname")->order("A.ctime desc")->select();
        $listall = Article::where($where)->count();
        $pagecount = ApiCommon::page_count($listall,$page);
        foreach ($list as $key=>$value){
            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["imgjiu"] = 0;
            $list[$key]["imgjiucount"] = 0;
            $list[$key]["imgcount"] = 0;
            if($value["type"] == 1){
                $count = ApiCommon::listimage($value["image"]);
                if(count($count) > 9){
                    $list[$key]["listimage"] = ApiCommon::listimage_jiu($count);
                }else{
                    $list[$key]["listimage"] = $count;
                }

                $list[$key]["imgjiu"] = count($count)> 9 ? 1 : 0;
                $list[$key]["imgjiucount"] = count($count)> 9 ? count($count) - 9 : 0;
                $list[$key]["imgcount"] = count($count);
            }else{
                $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            }
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount,"post"=>$post]]);
    }

    public function getuser(){
        $post = $this->request->param();
        $rule = [
            'token'=>'require'
        ];
        $msg = [
            'token.require'     => 'token为空',
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
        $user = User::where("id",$uid)->field("id,head,nickname,sugar")->find();
        $user["head"] = ApiCommon::image_url($user["head"]);
        $wencount = Article::where(["uid"=>$uid,"type"=>2])->count();
        $imgcount = Article::where(["uid"=>$uid,"type"=>1])->count();
        if(SugarRecord::is_qd($uid)){
            $dailing = 0;
        }else{
            $dailing = 10;
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["user"=>$user,"wencount"=>$wencount,"imgcount"=>$imgcount,"dailing"=>$dailing]]);
    }

    //收藏
    public function allCollection(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = Collection::where("C.uid",$uid)->alias("C")->join("dt_article A",'C.col_id = A.id')->page($p,$page)->field("C.id id,A.id col_id,A.type type,A.image image,A.name name")->select();
        $listall =  Collection::where("C.uid",$uid)->alias("C")->join("dt_article A",'C.col_id = A.id')->count();

        foreach ($list as $key=>$value){
            if($value["type"] == 1){
                $count = ApiCommon::listimage($value["image"]);
                $list[$key]["image"] = $count[0];
            }else{
                $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            }
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    //收藏
    public function allarticlewen(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = Article::where(["uid"=>$uid,"type"=>2])->page($p,$page)->field("id,image,name,ctime")->select();
        $listall =  Article::where(["uid"=>$uid,"type"=>2])->count();

        foreach ($list as $key=>$value){
            $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            $list[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }
    public function allarticleimg(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = Article::where(["uid"=>$uid,"type"=>1])->page($p,$page)->field("id,image,name,ctime")->select();
        $listall =  Article::where(["uid"=>$uid,"type"=>1])->count();

        foreach ($list as $key=>$value){
            $con = ApiCommon::listimage($value["image"]);
            $list[$key]["image"] = $con[0];
            $list[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    public function userdate(){
        $post = $this->request->param();
        $rule = [
            'id' => 'require',
        ];
        $msg = [
            'id.require'     => 'id为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $user = User::where("id",$post["id"])->field("id,head,cover,nickname")->find();
        if(!$user){
            return json(["code"=>-1,"msg"=>"用户不存在","data"=>[]]);
        }
        $user["head"] = ApiCommon::image_url($user["head"]);
        $user["cover"] = ApiCommon::image_url($user["cover"]);
        $user["fans"] = Follow::where("gz_uid",$post["id"])->count();
        $user["follow"] = Follow::where("uid",$post["id"])->count();

        $url = ApiCommon::image_url("/#/homepage?id=".$post["id"]);

        return json(["code"=>1,"msg"=>"成功","data"=>["user"=>$user,"url"=>$url]]);
    }

    public function getuser_article(){
        $post = $this->request->param();
        $rule = [
            'id'=>'require'
        ];
        $msg = [
            'id.require'     => 'id为空'
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] !== 0){
                $p = $post["p"];
            }
        }
        $where = [];
        $where[] = ["show","=",1];
//        $where[] = ["state","=",1];
        $list = Article::where($where)->alias("A")->join('dt_user C','A.uid = C.id')->page($p,$page)->field("A.id id,A.name name,A.image image,A.type type,A.fabulous fabulous,A.comment comment,C.head head,C.nickname nickname")->order("A.ctime desc")->select();
        $listall = Article::where($where)->count();
        $pagecount = ApiCommon::page_count($listall,$page);
        foreach ($list as $key=>$value){
            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["imgjiu"] = 0;
            $list[$key]["imgjiucount"] = 0;
            $list[$key]["imgcount"] = 0;
            if($value["type"] == 1){
                $count = ApiCommon::listimage($value["image"]);
                if(count($count) > 9){
                    $list[$key]["listimage"] = ApiCommon::listimage_jiu($count);
                }else{
                    $list[$key]["listimage"] = $count;
                }

                $list[$key]["imgjiu"] = count($count)> 9 ? 1 : 0;
                $list[$key]["imgjiucount"] = count($count)> 9 ? count($count) - 9 : 0;
                $list[$key]["imgcount"] = count($count);
            }else{
                $list[$key]["image"] = ApiCommon::image_url($value["image"]);
            }
        }
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount,"post"=>$post]]);
    }

    public function getmyfollow(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = Follow::where(["uid"=>$uid])->alias("F")->join("dt_user U","F.uid = U.id")->field("F.id id,F.gz_uid gz_uid,F.uid uid,U.head head,U.nickname nickname,F.ctime ctime")->page($p,$page)->select();
        $listall =  Follow::where(["uid"=>$uid])->count();

        foreach ($list as $key=>$value){
            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["ctime"] = date("Y-m-d",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    public function qqfollow()
    {
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'id'=>'require'
        ];
        $msg = [
            'token.require' => 'token为空',
            'id.require' => 'id为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $suee = Follow::where(["id"=>$post["id"],"uid"=>$uid])->delete();
        if($suee){
            return json(["code" => 1, "msg" =>"成功", "data" => []]);
        }
        return json(["code" => -1, "msg" =>"失败", "data" => []]);
    }

    //删除收藏
    public function collectiondelete()
    {
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'id'=>'require'
        ];
        $msg = [
            'token.require' => 'token为空',
            'id.require' => 'id为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $suee = Collection::where(["id"=>$post["id"],"uid"=>$uid])->delete();
        if($suee){
            return json(["code" => 1, "msg" =>"成功", "data" => []]);
        }
        return json(["code" => -1, "msg" =>"失败", "data" => []]);
    }

    //删除收藏
    public function articledelete()
    {
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'id'=>'require'
        ];
        $msg = [
            'token.require' => 'token为空',
            'id.require' => 'id为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $suee = Article::where(["id"=>$post["id"],"uid"=>$uid])->update(["isdelete"=>1]);
        if($suee){
            return json(["code" => 1, "msg" =>"成功", "data" => []]);
        }
        return json(["code" => -1, "msg" =>"失败", "data" => []]);
    }

    public function getnews(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = News::where(["uid"=>$uid])->field("id,n_uid,art_id,ctime,type,centre")->page($p,$page)->select();
        $listall =  News::where(["uid"=>$uid])->count();

        foreach ($list as $key=>$value){
//            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    //删除收藏
    public function newsdelete()
    {
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
            'id'=>'require'
        ];
        $msg = [
            'token.require' => 'token为空',
            'id.require' => 'id为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $suee = News::where(["id"=>$post["id"],"uid"=>$uid])->delete();
        if($suee){
            return json(["code" => 1, "msg" =>"成功", "data" => []]);
        }
        return json(["code" => -1, "msg" =>"失败", "data" => []]);
    }

    public function getsearch(){
        $post = $this->request->param();
        $rule = [
            'type' => 'require',

        ];
        $msg = [
            'type.require' => 'type为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $list = Search::where(["type"=>$post["type"]])->order("sort")->select();
        return json(["code" =>1, "msg" =>"成功", "data" => ["list"=>$list]]);
    }

    public function getsetup(){
        $post = $this->request->param();
        $list = Setup::where(["id"=>1])->find();
        return json(["code" =>1, "msg" =>"成功", "data" => ["list"=>$list]]);
    }

    public function gettask(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require' => 'token为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }

        $user = User::where("id",$uid)->field("sugar,sign_count")->find();
        $data["sugar"] = $user["sugar"];
        //进来判断昨天是否签到，不签到直接清零
        $zday = SugarRecord::setsign_count($uid);
        $data["sign_count"] = !$zday ? 0 : $user["sign_count"];
        $data["is_sign"] = !SugarRecord::is_qd($uid) ? 0 : 1;
        if(SugarRecord::is_qd($uid)){
            $dailing = 0;
        }else{
            $dailing = 10;
        }
        return json(["code" => 1, "msg" =>"成功", "data" => ["user"=>$data,"dailing"=>$dailing]]);
    }

    public function getsign(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require' => 'token为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $qq = SugarRecord::is_qd($uid);
        if($qq){
            return json(["code" => -1, "msg" => "您今日已签到！", "data" => []]);
        }
        $suee = SugarRecord::setsighr($uid,1,1,0);
        if($suee[0]){
            return json(["code" => 1, "msg" => "成功", "data" => ["munber"=>$suee[1]]]);
        }
        return json(["code" => -1, "msg" =>"失败", "data" => []]);
    }

    public function getsign_list(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = SugarRecord::where(["uid"=>$uid])->field("id,uid,type,ctime,centre,munber")->page($p,$page)->select();
        $listall =  SugarRecord::where(["uid"=>$uid])->count();

        foreach ($list as $key=>$value){
//            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }

    public function getluckdraw(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require' => 'token为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $user = User::where("id",$uid)->field("sugar")->find();
        $prize = Prize::where("")->order("sort")->limit(8)->field("id,name,image,muber")->select();
        foreach ($prize as $key=>$value){
            $prize[$key]["image"] = ApiCommon::image_url($value["image"]);
            $prize[$key]["meng"] = 0;
        }
        //
        $setup = Setup::where("id",1)->field("explain")->find();

        return json(["code" => 1, "msg" => "成功", "data" => ["user"=>$user,"prize"=>$prize,"setup"=>$setup]]);
    }


    public function smoke(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require' => 'token为空',
        ];
        $validate = Validate::make($rule, $msg);
        $result = $validate->check($post);
        if (!$result) {
            return json(["code" => -1, "msg" => $validate->getError(), "data" => []]);
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        //理由缓存
        try{
            $memcache = new \think\cache\driver\Memcache();             //创建一个memcache对象
            $memcache->connect('localhost', 11211) or json(["code" => -1, "msg" => "稍后再试", "data" => []]);; //连接Memcached服务器
        }catch (\Exception $e){
            return json(["code" => -1, "msg" => "稍后再试", "data" => []]);
        }

        $tjj = $memcache->add("key", 1, false, 10);
        if(!$tjj){
            return json(["code" => -1, "msg" => "稍后再试", "data" => []]);
        }
        //判断糖票
        $user = User::where("id",$uid)->lock(true)->find();
        if($user["sugar"] < 10){
            return json(["code" => -1, "msg" => "糖票不足", "data" => []]);
        }

//        $get_value = $memcache->get('key');   //从内存中取出key的值
//        if($get_value <= 0){
//            return json(["code" => -1, "msg" => "糖票不足", "data" => []]);
//        }
//        $memcache->set('key', $get_value-1);

        $suee = Prize::cchou($uid);
        if($suee[0]){
            $memcache->delete ( "key" );
            return json(["code" => 1, "msg" => "成功", "data" => ["prizeid"=>$suee[1]]]);
        }
        return json(["code" => -1, "msg" => "失败", "data" => []]);
    }

    //获取中奖列表
    public function getprizelist(){
        $list = PrizeRecord::where("")->alias("P")->join("dt_prize G","P.prize_id = G.id")->join("dt_user U","P.uid = U.id")->field("P.id id,U.nickname nickname,G.name name")
        ->order("P.ctime desc")->limit(100)->select();
        return json(["code" => 1, "msg" => "成功", "data" => ["list"=>$list]]);
    }

    public function getprizerecord(){
        $post = $this->request->param();
        $rule = [
            'token' => 'require',
        ];
        $msg = [
            'token.require'     => 'token为空',
        ];
        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($post);
        if(!$result) {
            return json(["code"=>-1,"msg"=>$validate->getError(),"data"=>[]]);
        }
        $p = 1;
        $page = 20;
        if($this->request->has("p")){
            if($post["p"] != 0){
                $p = $post["p"];
            }
        }
        $uid = ApiCommon::overdue_token($post["token"]);
        if(is_array($uid)){
            return json($uid);
        }
        $list = PrizeRecord::where("uid",$uid)->alias("P")->join("dt_prize G","P.prize_id = G.id")->join("dt_user U","P.uid = U.id")->field("P.id id,U.nickname nickname,G.name name,P.ctime ctime")
            ->order("P.ctime desc")->limit(100)->page($p,$page)->select();
        $listall =  PrizeRecord::where("uid",$uid)->count();

        foreach ($list as $key=>$value){
//            $list[$key]["head"] = ApiCommon::image_url($value["head"]);
            $list[$key]["ctime"] = date("Y-m-d H:i:s",$value["ctime"]);
        }
        $pagecount = ApiCommon::page_count($listall,$page);
        return json(["code"=>1,"msg"=>"成功","data"=>["list"=>$list,"listcount"=>$pagecount]]);
    }
//    public function adaw(){
////
////        echo phpinfo();
////        return;
//        $redis = new Redis();
//        $redis->connect('127.0.0.1', 6379); //连接Redis
//        $redis->auth('1544fei'); //密码验证
//        $redis->select(2);//选择数据库
//        $redis->lpush( "list", "1111"); //设置测试key
//        echo "ok";
//        return;
//
////        echo phpinfo();
////        return;
//        try{
//            $memcache = new \think\cache\driver\Memcache();             //创建一个memcache对象         //创建一个memcache对象
//            $memcache->connect('localhost', 11211) or die ("Could not connect"); //连接Memcached服务器
//            if($memcache->add('key3',80) == 1){
//                echo "不存";
//            }else{
//                echo "存在";
//            };        //设置一个变量到内存中，名称是key 值是test
//
////        $get_value = $memcache->get('key');   //从内存中取出key的值
//
//        }catch (\Exception $e) {
//            return "no";
//        }
////        $memcache = new Memcacheds();
////        $memcache::delAllMen();
////        $memcache::setMen(md5("awdawd"),"9998",6000);
////        $val = $memcache::getMen(md5("awdawd"));
////        echo $val;
//
//    }
}