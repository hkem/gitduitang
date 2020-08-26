<template>

  <div class="index-recommend">
    <van-list v-model="loading"
          :finished="finished"
          finished-text="没有更多了"
           :error.sync="error"
            error-text="请求失败，点击重新加载"
            :immediate-check="false"
          @load="onLoad"
        >
        <div class="van-clearfix">
    <!-- 加载中 -->
    <div class="recommend-error-block" v-if="isError">
      <Errora></Errora>
    </div>
    <!-- 错误 -->
    <div class="recommend-loading-block" v-if="request_Loading">
      <Loading></Loading>
    </div>
    <!-- 正文 -->

      <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
        <!-- 轮播 -->
        <div class="index-Swipe-block">
          <div class="index-Swipe">
            <van-swipe class="index-van-swipe" :autoplay="3000" indicator-color="white">
              <van-swipe-item v-for="item in banner" :key="item.id"  @click="clickbanner(item.url)">
                <img :src="item.image"/>
              </van-swipe-item>
            </van-swipe>
          </div>
        </div>

        <!-- 推荐列表 -->
        <div class="recommend-list-block">

          <div class="recommend-list">

            <div class="recommend-list-li" v-for="item in list" :key="item.id">
              <div class="recommend-user-block">
                <div class="user-block-img">
                  <van-image class="recommend-user-img" round fit="cover" :src="item.head" />
                </div>
                <div class="user-name-block">
                  <div class="user-name">
                    <h1>{{item.nickname}}</h1>
                  </div>
                  <div class="user-label">
                    <p>热门内容</p>
                  </div>
                </div>
              </div>
              <div class="recommend-centre-block" @click="clickcentre(item.id,item.type)">
                <div class="recommend-centre">
                  {{item.name}}
                </div>
                <!-- 丹徒 -->
                <div class="recommend-centre-img" v-if="item.type == 2">
                  <van-image class="recommend-centre-li-img" fit="cover" :src="item.image" />
                </div>

                <!-- 多图 -->
                <div class="recommend-all-img" v-if="item.type == 1 && item.imgcount != 4">
                  <div class="recommend-all-li" v-for="(value, index) in item.listimage">
                    <van-image class="all-li-img" fit="cover" :src="value" />
                    <div class="recommend-all-mumber" v-if="item.imgjiu == 1 && index == 8">
                      <div class="all-mumber-centre">
                        <div class="all-mumber-img"><img src="../assets/img/munmber.png"></div>
                        <div class="all-mumber-shu">{{item.imgjiucount}}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!--四张图片 -->
                <div class="recommend-si-img" v-if="item.type == 1 && item.imgcount == 4">
                  <div class="recommend-si-li" v-for="(value, name) in item.listimage">
                    <van-image class="si-all-li-img" fit="cover" :src="value" />
                  </div>
                </div>
              </div>
              <div class="recommend-operation-block">
                <div class="recommend-operation-li">
                  <div class="operation-li-abulous">
                    <div class="operation-li-img">
                      <img src="../assets/img/fabulous.png">
                    </div>
                    <div class="operation-li-title">
                      {{item.fabulous}}
                    </div>
                  </div>
                </div>
                <div class="recommend-operation-li">
                  <div class="operation-li-comment">
                    <div class="operation-li-img">
                      <img src="../assets/img/comment.png">
                    </div>
                    <div class="operation-li-title">
                      {{item.comment}}
                    </div>
                  </div>
                </div>
                <div class="recommend-operation-li">
                  <div class="operation-li-more">
                    <div class="operation-li-img">
                      <img src="../assets/img/more.png">
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

       </div>
       </van-pull-refresh>
       </div>
       </van-list>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';

  export default {
    name: 'Recommend',
    data () {
      return {
        msg: '登录页',
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        banner:[],
        list:[],
        page:1,
        allpage:0,
        loading: false,
        finished: false,
        error:false
      }
    },
    watch: {
        // 如果路由有变化，会再次执行该方法
        '$route': 'fetchData'
      },
    created(){
      this.fetchData();
      console.log();
    },
    components: {
        Errora,
        Loading
    },
    methods:{
      onLoad:function(){
        if(this.page !== this.allpage){
          // var p = this.page;
          var p = this.page + 1;
          this.get_list(p);
        }
      },
      //请求数据
      fetchData (type) {
        var that = this;
        this.$axios
             .post(this.$myStore.url+"/api/index/get_recommend_banner",this.$qs.stringify({}))
             .then(response=>{
                //成功
                if(type == "refresh"){
                  that.isLoading = false;
                  that.$toast('刷新成功');
                }
                var data = response.data;
                if(data.code == 1){
                  var resdata = data.data;
                  this.banner = resdata.banner;
                  that.request_Loading = false;
                  that.isCentre = true;
                  that.isError = false;
                  //根据
                  console.log(response.status);
                }

                //渲染数据
             })
             .catch(error=>{
                //失败
                console.log("失败");
                if(type == "refresh"){
                  this.$toast('刷新成功');
                }
                this.request_Loading = false;
                this.isCentre = false;
                this.isError = true;

         });
         this.get_list(1);
      },
      get_list(p){
        this.$axios
             .post(this.$myStore.url+"/api/index/get_article",this.$qs.stringify({p:p,type:1,id:-1}))
             .then(response=>{
                var data = response.data;
                if(data.code == 1){
                  var resdata = data.data;

                  if(p === 1){
                    this.list = resdata.list;
                    this.allpage = resdata.listcount;
                  }else{
                    var jiulist = this.list;
                    this.list = jiulist.concat(resdata.list);
                  }
                  if(this.allpage == p){
                    this.finished = true;
                  }
                  this.loading = false;
                  // this.page = p + 1;
                  this.page = p;
                }else{
                  this.error = true;
                }
                //根据
                //渲染数据
             })
             .catch(error=>{
                //失败
                 if(error.response){
                    this.$toast('获取失败');
                    this.error = true;
                 }
         });
      },
      //刷新
      onRefresh() {
        this.fetchData("refresh");
      },
      clickcentre:function(cen){
        this.$router.push({ name: 'Articledateils', query: { id:cen}})
      },
      clickbanner:function(url){
        if(url != ""){
          window.location.href = url;
        }
      }
    }
  }
</script>

<style>

  .index-recommend{
    width: 100%;
  }
  /* 轮播 */
  .index-Swipe-block{
    width: 100%;
    height: 300px;
  }
  .index-Swipe{

    height: 250px;
    width: 680px;
    margin: auto;
    border-radius: 20px;
  }
  .index-van-swipe .van-swipe-item {
      height: 250px;
      background-color: #39a9ed;
      border-radius: 20px;
  }
  .index-van-swipe .van-swipe-item img{
    height: 250px;
    width: 680px;
    border-radius: 20px;
  }
  /* 列表 */
  .recommend-list-block{
    width: 100%;
    float: left;
  }
  .recommend-list{
    width: 680px;
    margin: auto;
  }
  .recommend-list-li{
    width: 100%;
    float: left;
  }
  .recommend-user-block{
    height: 80px;
    width: 100%;
    float: left;
    margin-top: 40px;
  }
  .user-block-img{
    float: left;
    width: 80px;
    height: 50px;
    border-radius: 10000px;
  }
  .user-block-img .recommend-user-img{
    float: left;
    width: 80px;
    height: 80px;
    border-radius: 10000px;
  }
  .user-name-block{
    float: left;
    height: 80px;
    margin-left: 20px;
  }
  .user-name{
    width: 100%;
    height: 30px;
  }
  .user-name h1{
    font-size: 35px;
    line-height: 30px;
    margin: 5px 0px;
    color: #777777;
  }
  .user-label{
    width: 100%;
    height: 20px;
  }
  .user-label p{
    font-size: 14px;
    line-height: 20px;
    color: #d2d2d2;
  }
  .recommend-centre-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .recommend-centre{
    width: 100%;
    float: left;
    font-size: 35px;
    color: #444444;
  }
  .recommend-centre-img{
    margin-top: 10px;
    width: 100%;
    float: left;
  }
  .recommend-centre-img .recommend-centre-li-img{
    width: 680px;
    height: 300px;
  }
  .recommend-operation-block{
    width: 100%;
    height: 40px;
    float: left;
    margin-top: 15px;
  }
  .recommend-operation-li{
    width: 33.33333333333%;
    float: left;
    height: 40px;
  }
  .operation-li-abulous{
    float: left;
    height: 50px;
  }
  .operation-li-img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .operation-li-img img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .operation-li-title{
    float: left;
    line-height: 40px;
    margin-left: 10px;
    font-size: 30px;
    color: #aaaaaa;
  }
  .operation-li-comment{
    width: 100px;
    margin: auto;
    height: 40px;
  }
  .operation-li-more{
    float: right;
    height: 40px;
  }
  .recommend-list-bottom{
    height: 20px;
    width: 100%;
    float: left;
    background-color: #f5f5f5;
    margin-top: 40px;
  }
  .recommend-error-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .recommend-loading-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .recommend-all-img{
    width: 100%;
    float: left;
  }
  .recommend-all-li{
    width: 226.66666px;
    height: 226.66666px;
    float: left;
    position: relative;
  }
  .all-li-img{
    width: 216.66666px;
    height: 216.66666px;
    margin: 5px 5px;
  }
  .recommend-all-mumber{
    position: absolute;
    width: 216.66666px;
    height: 216.66666px;
    top: 5px;
    left: 5px;
    background: rgba(0, 0, 0, 0.5) !important;
  }
  .all-mumber-centre{
    height: 100px;
    margin-top: 58.333px;
  }
  .all-mumber-img{
    width: 80px;
    height: 100px;
    float: left;
    margin-left: 40px;
  }
  .all-mumber-img img{
    width: 60px;
    height: 60px;
    margin: 20px;
  }
  .all-mumber-shu{
    line-height: 100px;
    font-size: 60px;
    float: left;
    color: #dbdbdb;
  }
  .recommend-cc{
    float: left;
    width: 100%;
  }
  .recommend-si-img{
    width: 100%;
    float: left;
  }
  .recommend-si-li{
    width: 340px;
    height: 340px;
    float: left;
    position: relative;
  }
  .recommend-si-li .si-all-li-img{
    width: 330px;
    height: 330px;
    margin: 5px 5px;
  }
</style>
