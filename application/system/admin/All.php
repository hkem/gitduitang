<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/12
 * Time: 14:51
 */

namespace app\system\admin;


use app\system\model\Article;
use app\system\model\Banner;
use app\system\model\Classify;
use app\system\model\Goods;
use app\system\model\Grass;
use app\system\model\Prize;
use app\system\model\Search;
use app\system\model\Setup;
use app\system\model\Shopclassify;
use think\facade\Validate;
use app\system\model\User;
class All extends Admin
{
    public function banner(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $data['data'] = Banner::where("type",1)->page($page)->limit($limit)->select();
            $data['count'] = Banner::where("type",1)->count('id');
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function banner_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::add_banner($url,$post["sort"],$post["image"],1);
            if($suee){
                return $this->success("成功","system/all/banner");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function banner_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::update_banner($url,$post["sort"],$post["image"],1,$post["id"]);
            if($suee){
                return $this->success("成功","system/all/banner");
            }
            return $this->error("失败");
        }
        $list = Banner::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function banner_delete(){
        $post = $this->request->param();
        $suee = Banner::delete_banner($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function banner_sort(){
        $post = $this->request->param();
        $suee = Banner::sort_banner($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }


    public function banner2(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $data['data'] = Banner::where("type",2)->page($page)->limit($limit)->select();
            $data['count'] = Banner::where("type",2)->count('id');
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function banner2_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::add_banner($url,$post["sort"],$post["image"],2);
            if($suee){
                return $this->success("成功","system/all/banner2");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function banner2_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::update_banner($url,$post["sort"],$post["image"],2,$post["id"]);
            if($suee){
                return $this->success("成功","system/all/banner2");
            }
            return $this->error("失败");
        }
        $list = Banner::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function banner2_delete(){
        $post = $this->request->param();
        $suee = Banner::delete_banner($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function banner2_sort(){
        $post = $this->request->param();
        $suee = Banner::sort_banner($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function banner3(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $data['data'] = Banner::where("type",3)->page($page)->limit($limit)->select();
            $data['count'] = Banner::where("type",3)->count('id');
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function banner3_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::add_banner($url,$post["sort"],$post["image"],3);
            if($suee){
                return $this->success("成功","system/all/banner3");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function banner3_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'image' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'image.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $url = $this->request->has("url") ? $post["url"] : "";
            $suee = Banner::update_banner($url,$post["sort"],$post["image"],3,$post["id"]);
            if($suee){
                return $this->success("成功","system/all/banner3");
            }
            return $this->error("失败");
        }
        $list = Banner::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function banner3_delete(){
        $post = $this->request->param();
        $suee = Banner::delete_banner($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function banner3_sort(){
        $post = $this->request->param();
        $suee = Banner::sort_banner($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }


    public function classify(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $list = Classify::get_list();
            $data['data'] = $list;
            $data['count'] = count($list);
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function classify_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'name' => 'require',
                'pid' => 'require',
            ];
            $msg = [

                'sort.require'     => '排序为空',
                'name.require'     => '名称为空',
                'pid.require'     => '名称为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }

            $image = $this->request->has("image") ? $post["image"] : "";
            $pid2 = Classify::get_pid2($post["pid"]);
            $suee = Classify::add_classify($image,$post["pid"],$pid2,$post["sort"],$post["name"]);
            if($suee){
                return $this->success("成功","system/all/classify");
            }
            return $this->error("失败");
        }
        $this->assign("list",Classify::get_all());
        return $this->fetch();
    }

    public function classify_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'name' => 'require',
                'pid' => 'require',
            ];
            $msg = [
                'sort.require'     => '排序为空',
                'name.require'     => '名称为空',
                'pid.require'     => '名称为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $image = $this->request->has("image") ? $post["image"] : "";
            $pid2 = Classify::get_pid2($post["pid"]);
            $suee = Classify::update_classify($image,$post["pid"],$pid2,$post["sort"],$post["name"],$post["id"]);
            if($suee){
                return $this->success("成功","system/all/classify");
            }
            return $this->error("失败");
        }
        $list2 = Classify::get($post["id"]);
        $this->assign("list2",$list2);

        $this->assign("list",Classify::get_all());
        return $this->fetch();
    }

    public function classify_delete(){
        $post = $this->request->param();
        $suee = Classify::delete_classify($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function classify_sort(){
        $post = $this->request->param();
        $suee = Classify::sort_classify($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function shopclassify(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $list = Shopclassify::get_list();
            $data['data'] = $list;
            $data['count'] = count($list);
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function shopclassify_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'name' => 'require',
                'pid' => 'require',
            ];
            $msg = [

                'sort.require'     => '排序为空',
                'name.require'     => '名称为空',
                'pid.require'     => '名称为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }

            $image = $this->request->has("image") ? $post["image"] : "";
            $suee = Shopclassify::add_classify($image,$post["pid"],$post["sort"],$post["name"]);
            if($suee){
                return $this->success("成功","system/all/shopclassify");
            }
            return $this->error("失败");
        }
        $this->assign("list",Shopclassify::get_all());
        return $this->fetch();
    }

    public function shopclassify_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'name' => 'require',
                'pid' => 'require',
            ];
            $msg = [
                'sort.require'     => '排序为空',
                'name.require'     => '名称为空',
                'pid.require'     => '名称为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $image = $this->request->has("image") ? $post["image"] : "";

            $suee = Shopclassify::update_classify($image,$post["pid"],$post["sort"],$post["name"],$post["id"]);
            if($suee){
                return $this->success("成功","system/all/shopclassify");
            }
            return $this->error("失败");
        }
        $list2 = Shopclassify::get($post["id"]);
        $this->assign("list2",$list2);

        $this->assign("list",Shopclassify::get_all());
        return $this->fetch();
    }

    public function shopclassify_delete(){
        $post = $this->request->param();
        $suee = Shopclassify::delete_classify($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function shopclassify_sort(){
        $post = $this->request->param();
        $suee = Shopclassify::sort_classify($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function goods(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $data['data'] = Goods::where("")->alias("G")->join('dt_shopclassify C','G.classify_id = C.id')
                ->field('*,G.image image,G.ctime ctime,G.name name,G.id id,C.name classify_name')->page($page)->limit($limit)->select();
            $data['count'] = Goods::count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function goods_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'classify_id' => 'require',
                'price' => 'require',
                'original_price' => 'require',
                'shop' => 'require',
                'shop_name' => 'require',
                'discount' => 'require',
                'reason' => 'require',
                'out_time' => 'require',

            ];
            $msg = [

                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'classify_id.require'     => '分类为空',
                'price.require'     => '价格为空',
                'original_price.require'     => '原价格为空',
                'shop.require'     => '店铺类型为空',
                'shop_name.require'     => '店铺名称为空',
                'discount.require'     => '优惠价为空',
                'reason.require'     => '理由为空',
                'out_time.require'     => '过期时间为空',

            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Goods::add_goods($post["url"],$post["sort"],$post["image"],$post["name"],$post["price"],$post["original_price"],$post["shop"],$post["shop_name"],$post["discount"],$post["reason"],$post["out_time"],$post["classify_id"],$post["sold"],$post["province"]);
            if($suee){
                return $this->success("成功","system/all/goods");
            }
            return $this->error("失败");
        }
        $this->assign("list",Shopclassify::get_all());
        return $this->fetch();
    }

    public function goods_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'classify_id' => 'require',
                'price' => 'require',
                'original_price' => 'require',
                'shop' => 'require',
                'shop_name' => 'require',
                'discount' => 'require',
                'reason' => 'require',
                'out_time' => 'require',
            ];
            $msg = [

                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'classify_id.require'     => '分类为空',
                'price.require'     => '价格为空',
                'original_price.require'     => '原价格为空',
                'shop.require'     => '店铺类型为空',
                'shop_name.require'     => '店铺名称为空',
                'discount.require'     => '优惠价为空',
                'reason.require'     => '理由为空',
                'out_time.require'     => '过期时间为空',
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Goods::update_goods($post["url"],$post["sort"],$post["image"],$post["name"],$post["price"],$post["original_price"],$post["shop"],$post["shop_name"],$post["discount"],$post["reason"],$post["out_time"],$post["classify_id"],$post["sold"],$post["province"],$post["id"]);
            if($suee){
                return $this->success("成功","system/all/goods");
            }
            return $this->error("失败");
        }
        $this->assign("list",Shopclassify::get_all());
        $list2 = Goods::get($post["id"]);
        $list2["out_time"] = date("Y-m-d H:i:s",$list2["out_time"]);

        $this->assign("list2",$list2);
        return $this->fetch();
    }

    public function goods_delete(){
        $post = $this->request->param();
        $suee = Goods::delete_goods($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function goods_sort(){
        $post = $this->request->param();
        $suee = Goods::sort_goods($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }


    public function grass(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $data['data'] = Grass::where("")->alias("G")->join('dt_user C','G.uid = C.id')
                ->field('*,G.image image,G.ctime ctime,G.name name,G.collection collection,G.readq readq,G.sort sort,G.id id,C.nickname uname')->page($page)->limit($limit)->select();
            $data['count'] = Goods::count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function grass_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'uid' => 'require',
                'readq' => 'require',
                'collection' => 'require',
                'centre' => 'require',

            ];
            $msg = [

                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'uid.require'     => '用户为空',
                'readq.require'     => '阅读为空',
                'collection.require'     => '收藏为空',
                'centre.require'     => '内容为空',
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Grass::add_grass($post["sort"],$post["image"],$post["name"],$post["centre"],$post["readq"],$post["collection"],$post["uid"],$post["type"]);
            if($suee){
                return $this->success("成功","system/all/grass");
            }
            return $this->error("失败");
        }
        $user = User::where("is_own",1)->select();
        $this->assign("list",$user);
        return $this->fetch();
    }

    public function grass_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'uid' => 'require',
                'readq' => 'require',
                'collection' => 'require',
                'centre' => 'require',
            ];
            $msg = [

                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'uid.require'     => '用户为空',
                'readq.require'     => '阅读为空',
                'collection.require'     => '收藏为空',
                'centre.require'     => '内容为空',
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Grass::update_grass($post["sort"],$post["image"],$post["name"],$post["centre"],$post["readq"],$post["collection"],$post["uid"],$post["type"],$post["id"]);
            if($suee){
                return $this->success("成功","system/all/grass");
            }
            return $this->error("失败");
        }
        $user = User::where("is_own",1)->select();
        $this->assign("list",$user);
        $list2 = Grass::get($post["id"]);
        $this->assign("list2",$list2);
        return $this->fetch();
    }

    public function grass_delete(){
        $post = $this->request->param();
        $suee = Grass::delete_grass($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function grass_sort(){
        $post = $this->request->param();
        $suee = Grass::sort_grass($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
    public function grass_recommend(){
        $post = $this->request->param();
        $suee = Grass::recommend_grass($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
    //    文章
    public function articlewen(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $data['data'] = Article::where("type",2)->alias("G")->join('dt_classify C','G.classify_id = C.id')->join('dt_user U','G.uid = U.id')
                ->field('G.image image,G.ctime ctime,G.name name,G.id id,G.state state,U.nickname nickname,C.name classify_name,G.fabulous fabulous,G.comment comment,G.collection collection,G.recommend recommend,G.sort sort,G.show showa')
                ->page($page)->limit($limit)->select();
            $data['count'] = Article::where("type",2)->count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function articl_sort(){
        $post = $this->request->param();
        $suee = Article::sort_articl($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function articl_show(){
        $post = $this->request->param();
        $suee = Article::show_articl($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
    public function articlewen_recommend(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $suee = Article::where("id",$post["id"])->update(["recommend"=>$post["recommend"]]);
            if($suee){
                return $this->success("成功","system/all/articlewen");
            }
            return $this->error("失败");
        }
        $list = Article::where("id",$post["id"])->find();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function articlewen_delete(){
        $post = $this->request->param();
        $suee = Article::delete_articl($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function articlewen_xq(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $suee = Article::where("id",$post["id"])->update(["state"=>1]);
            if($suee){
                return $this->success("成功","system/all/articlewen");
            }
            return $this->error("失败");
        }
        $list = Article::where("G.id",$post["id"])->alias("G")->join('dt_classify C','G.classify_id = C.id')->join('dt_user U','G.uid = U.id')
            ->field('G.image image,G.ctime ctime,G.name name,G.centre centre,G.state state,G.id id,U.nickname nickname,C.name classify_name,G.fabulous fabulous,G.comment comment,G.collection collection,G.recommend recommend,G.sort sort,G.show showa')
            ->find();
        $this->assign("list",$list);
        return $this->fetch();
    }

    //    文章
    public function articleimg(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $list = Article::where("type",1)->alias("G")->join('dt_classify C','G.classify_id = C.id')->join('dt_user U','G.uid = U.id')
                ->field('G.image image,G.ctime ctime,G.name name,G.id id,G.state state,U.nickname nickname,C.name classify_name,G.fabulous fabulous,G.comment comment,G.collection collection,G.recommend recommend,G.sort sort,G.show showa')
                ->page($page)->limit($limit)->select();
            foreach ($list as $key=>$value){
                $list[$key]["image"] = explode(",",$value["image"]);
            }
            $data['data'] = $list;
            $data['count'] = Article::where("type",1)->count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }


    public function articleimg_xq(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $suee = Article::where("id",$post["id"])->update(["state"=>1]);
            if($suee){
                return $this->success("成功","system/all/articlewen");
            }
            return $this->error("失败");
        }
        $list = Article::where("G.id",$post["id"])->alias("G")->join('dt_classify C','G.classify_id = C.id')->join('dt_user U','G.uid = U.id')
            ->field('G.image image,G.ctime ctime,G.name name,G.centre centre,G.state state,G.id id,U.nickname nickname,C.name classify_name,G.fabulous fabulous,G.comment comment,G.collection collection,G.recommend recommend,G.sort sort,G.show showa')
            ->find();
        $list["image"] = explode(",",$list["image"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function search(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $data['data'] = Search::where("type",1)->page($page)->limit($limit)->select();
            $data['count'] = Search::where("type",1)->count('id');
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function search_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'name' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'name.require'     => '名称为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Search::add_search($post["sort"],$post["name"],1);
            if($suee){
                return $this->success("成功","system/all/search");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function search_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'name' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'name.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Search::update_search($post["sort"],$post["name"],1,$post["id"]);
            if($suee){
                return $this->success("成功","system/all/search");
            }
            return $this->error("失败");
        }
        $list = Search::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function search_delete(){
        $post = $this->request->param();
        $suee = Search::delete_search($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function search_sort(){
        $post = $this->request->param();
        $suee = Search::sort_search($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function search2(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $data['data'] = Search::where("type",2)->page($page)->limit($limit)->select();
            $data['count'] = Search::where("type",2)->count('id');
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function search_add2(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'name' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'name.require'     => '名称为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Search::add_search($post["sort"],$post["name"],2);
            if($suee){
                return $this->success("成功","system/all/search2");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function search_edit2(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'name' => 'require',
                'sort' => 'require'
            ];
            $msg = [
                'name.require'     => '图片为空',
                'sort.require'     => '排序为空'
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Search::update_search($post["sort"],$post["name"],2,$post["id"]);
            if($suee){
                return $this->success("成功","system/all/search2");
            }
            return $this->error("失败");
        }
        $list = Search::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function user(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $list = User::where("")->page($page)->limit($limit)->select();
            foreach ($list as $key=>$value){
                $list[$key]["birthday"] = date("Y-m-d",$value["birthday"]);
            }
            $data['data'] = $list;
            $data['count'] = User::where("")->count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function goodsexplain(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $post["goodsexplain"] = htmlspecialchars_decode($post["goodsexplain"]);
            $suee = Setup::where("id",1)->update(["goodsexplain"=>$post["goodsexplain"]]);
            if($suee){
                return $this->success("成功","system/all/goodsexplain");
            }
            return $this->error("失败");
        }
        $list = Setup::where("id",1)->find();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function aboutus(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $post["aboutus"] = htmlspecialchars_decode($post["aboutus"]);
            $suee = Setup::where("id",1)->update(["aboutus"=>$post["aboutus"]]);
            if($suee){
                return $this->success("成功","system/all/aboutus");
            }
            return $this->error("失败");
        }
        $list = Setup::where("id",1)->find();
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function strategy(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $post["strategy"] = htmlspecialchars_decode($post["strategy"]);
            $suee = Setup::where("id",1)->update(["strategy"=>$post["strategy"]]);
            if($suee){
                return $this->success("成功","system/all/strategy");
            }
            return $this->error("失败");
        }
        $list = Setup::where("id",1)->find();
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function explain(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $post["explain"] = htmlspecialchars_decode($post["explain"]);
            $suee = Setup::where("id",1)->update(["explain"=>$post["explain"]]);
            if($suee){
                return $this->success("成功","system/all/explain");
            }
            return $this->error("失败");
        }
        $list = Setup::where("id",1)->find();
        $this->assign("list",$list);
        return $this->fetch();
    }


    public function prize(){
        if ($this->request->isAjax()) {
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 110);
            $data['data'] = Prize::where("")
                ->page($page)->limit($limit)->select();
            $data['count'] = Prize::count();
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }

    public function prize_add(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'rate' => 'require',
                'mun' => 'require',
                'muber'=>'require'
            ];
            $msg = [
                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'rate.require'     => '概率为空',
                'mun.require'     => '总数量为空',
                'muber.require'     => '糖票为空',
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Prize::add_prize($post["sort"],$post["image"],$post["name"],$post["rate"],$post["mun"],$post["muber"]);
            if($suee){
                return $this->success("成功","system/all/prize");
            }
            return $this->error("失败");
        }
        return $this->fetch();
    }

    public function prize_edit(){
        $post = $this->request->param();
        if($this->request->isPost()){
            $rule = [
                'sort' => 'require',
                'image' => 'require',
                'name' => 'require',
                'rate' => 'require',
                'mun' => 'require',
                'muber'=>'require'
            ];
            $msg = [
                'sort.require'     => '排序为空',
                'image.require'     => '图片为空',
                'name.require'     => '名称为空',
                'rate.require'     => '概率为空',
                'mun.require'     => '总数量为空',
                'muber.require'     => '糖票为空',
            ];
            $validate   = Validate::make($rule,$msg);
            $result = $validate->check($post);
            if(!$result) {
                return $this->error($validate->getError());
            }
            $suee = Prize::update_prize($post["sort"],$post["image"],$post["name"],$post["rate"],$post["mun"],$post["muber"],$post["id"]);
            if($suee){
                return $this->success("成功","system/all/prize");
            }
            return $this->error("失败");
        }
        $list = Prize::get($post["id"]);
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function prize_delete(){
        $post = $this->request->param();
        $suee = Prize::delete_prize($post["id"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }

    public function prize_sort(){
        $post = $this->request->param();
        $suee = Prize::sort_prize($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
    public function prize_rate(){
        $post = $this->request->param();
        $suee = Prize::rate_prize($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
    public function prize_mun(){
        $post = $this->request->param();
        $suee = Prize::mun_prize($post["id"],$post["val"]);
        if($suee){
            return $this->success("成功");
        }
        return $this->error("失败");
    }
}