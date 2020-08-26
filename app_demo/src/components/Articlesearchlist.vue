<template>

  <div class="artsealist">
    <div class="artsealist-centre">
    <van-list v-model="loading"
          :finished="finished"
          finished-text="没有更多了"
           :error.sync="error"
            error-text="请求失败，点击重新加载"
            :immediate-check="false"
          @load="onLoad"
        >
        <div class="van-clearfix">


          <div class="artsealist-top-block">
            <div class="artsealist-top">
              <div class="artsealist-top-left" @click="onClickLeft">
                  <img src="../assets/img/left.png">
              </div>
              <div class="artsealist-top-right">
                  <van-search
                    v-model="val"
                    show-action
                    label=""
                    placeholder="请输入搜索关键词"
                    @search="onSearch"
                  >
                    <template #action>
                      <div @click="onSearch">搜索</div>
                    </template>
                  </van-search>
              </div>
            </div>
          </div>

    <div class="artsealist-block" v-if="isCentre">
    <!-- 加载中 -->
    <div class="artsealist-error-block" v-if="isError">
      <Errora></Errora>
    </div>
    <!-- 错误 -->
    <div class="artsealist-loading-block" v-if="request_Loading">
      <Loading></Loading>
    </div>
    <!-- 正文 -->
      <van-pull-refresh  v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
        <!-- 推荐列表 -->
        <div class="artsealist-list-block">

          <div class="artsealist-list">

            <div class="artsealist-list-li" v-for="item in list" :key="item.id">
              <div class="artsealist-user-block">
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
              <div class="artsealist-centre-block" @click="clickcentre(item.id,item.type)">
                <div class="artsealist-centre">
                  {{item.name}}
                </div>
                <!-- 丹徒 -->
                <div class="artsealist-centre-img" v-if="item.type == 2">
                  <van-image class="artsealistall-li-img" fit="cover" :src="item.image" />
                </div>

                <!-- 多图 -->
                <div class="artsealist-all-img" v-if="item.type == 1 && item.imgcount != 4">
                  <div class="artsealist-all-li" v-for="(value, index) in item.listimage">
                    <van-image class="all-li-img" fit="cover" :src="value" />
                    <div class="artsealist-all-mumber" v-if="item.imgjiu == 1 && index == 8">
                      <div class="all-mumber-centre">
                        <div class="all-mumber-img"><img src="../assets/img/munmber.png"></div>
                        <div class="all-mumber-shu">{{item.imgjiucount}}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!--四张图片 -->
                <div class="artsealist-si-img" v-if="item.type == 1 && item.imgcount == 4">
                  <div class="artsealist-si-li" v-for="(value, name) in item.listimage">
                    <van-image class="si-all-li-img" fit="cover" :src="value" />
                  </div>
                </div>
              </div>
              <div class="artsealist-operation-block">
                <div class="artsealist-operation-li">
                  <div class="operation-li-abulous">
                    <div class="operation-li-img">
                      <img src="../assets/img/fabulous.png">
                    </div>
                    <div class="operation-li-title">
                      {{item.fabulous}}
                    </div>
                  </div>
                </div>
                <div class="artsealist-operation-li">
                  <div class="operation-li-comment">
                    <div class="operation-li-img">
                      <img src="../assets/img/comment.png">
                    </div>
                    <div class="operation-li-title">
                      {{item.comment}}
                    </div>
                  </div>
                </div>
                <div class="artsealist-operation-li">
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
       </div>
       </van-list>
  </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';

  export default {
    name: 'Articlesearchlist',
    data () {
      return {
        msg: '登录页',
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,

        list:[],
        page:1,
        allpage:0,
        loading: false,
        finished: false,
        error:false,
        val:0
      }
    },
    watch: {
        // 如果路由有变化，会再次执行该方法
        '$route': 'fetchData'
      },
    created(){
      console.log(8888);
      this.val = this.$route.query.val;
      this.get_list(1,"");

    },
    components: {
        Errora,
        Loading
    },
    methods:{
      onSearch:function(){
        this.get_list(1);
      },
      onLoad:function(){
        if(this.page !== this.allpage){
          // var p = this.page;
          var p = this.page + 1;
          this.get_list(p,"");
        }
      },
      //请求数据

      get_list(p){
        var that = this;
        var token = this.$store.state.token;
        // if(token == ""){
        //   this.$router.push({ name:'Signin'});
        //   return;
        // }
        this.$axios
             .post(this.$myStore.url+"/api/index/get_article",this.$qs.stringify({type:5,p:p,token:token,val:that.val,id:-1}))
             .then(response=>{
                var data = response.data;
                if(data.code == 1){
                  var resdata = data.data;
                  this.isLoading = false;
                  if(p === 1){
                    this.allpage = resdata.listcount;
                    this.list = resdata.list;
                    this.request_Loading = false;
                    this.isCentre = true;
                    this.isError = false;
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
                }
                if(data.code == -1){
                  this.error = true;
                }
                if(data.code == -2){
                  this.$router.push({ name:'Signin'});
                  return;
                }
                //根据
                //渲染数据
             })
             .catch(error=>{
                //失败
                 if(error.response){
                    this.$toast('获取失败');
                    this.error = true;
                    this.request_Loading = false;
                    this.isCentre = true;
                    this.isError = false;
                 }
         });
      },
      //刷新
      onRefresh() {
        this.get_list(1,"refresh");
      },
      clickcentre:function(cen){
        this.$router.push({ name: 'Articledateils', query: { id:cen}})
      },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        this.edit = this.edit ? false : true;
      },
    }
  }
</script>

<style>
  .artsealist{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .artsealist-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .artsealist-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
    position: fixed;
    top:0px;
    z-index: 1000;
    background-color: #FFFFFF;
  }
  .index-artsealist{
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
  .artsealist-block{
    float: left;
    width: 100%;
    margin-top: 95px;
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
  .artsealist-list-block{
    width: 100%;
    float: left;
  }
  .artsealist-list{
    width: 680px;
    margin: auto;
  }
  .artsealist-list-li{
    width: 100%;
    float: left;
  }
  .artsealist-user-block{
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
  .artsealist-centre-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .artsealist-centre{
    width: 100%;
    float: left;
    font-size: 35px;
    color: #444444;
  }
  .artsealist-centre-img{
    margin-top: 10px;
    width: 100%;
    float: left;
  }
  .artsealist-centre-img .artsealistall-li-img{
    height: 300px;
    width: 680px;
  }
  .artsealist-operation-block{
    width: 100%;
    height: 40px;
    float: left;
    margin-top: 15px;
  }
  .artsealist-operation-li{
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
  .artsealist-list-bottom{
    height: 20px;
    width: 100%;
    float: left;
    background-color: #f5f5f5;
    margin-top: 40px;
  }
  .artsealist-error-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .artsealist-loading-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .artsealist-all-img{
    width: 100%;
    float: left;
  }
  .artsealist-all-li{
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
  .artsealist-all-mumber{
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
  .artsealist-cc{
    float: left;
    width: 100%;
  }
  .artsealist-si-img{
    width: 100%;
    float: left;
  }
  .artsealist-si-li{
    width: 340px;
    height: 340px;
    float: left;
    position: relative;
  }
  .artsealist-si-li .si-all-li-img{
    width: 330px;
    height: 330px;
    margin: 5px 5px;
  }

  .artsealist-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .artsealist-top{
    width: 100%;
    margin: auto;
  }
  .artsealist-top-left{
    float: left;
    width: 75px;
    height: 95px;
  }
  .artsealist-top-left img{
    width: 35px;
    height: 35px;
    margin: 35px 20px 0px 30px;
  }
  .artsealist-top-right{
    width: 650px;
    float:left ;
  }
</style>
