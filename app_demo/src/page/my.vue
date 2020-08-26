<template>
  <div class="my">
    <div class="my-center">
      <!-- 设置 -->
      <div class="my-top-block">
        <div class="my-top-left"><p>我</p></div>
        <div class="my-top-right">
          <router-link to="/setup">
            <img src="../assets/img/setup.png">
          </router-link>
        </div>
      </div>
      <!-- 我的资料 -->
      <div class="my-block">
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>
        <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
      <div class="my-mine-block">
        <div class="my-mine">

            <div class="my-mine-data" @click="homepage()">
              <div class="mine-data-img">

                <van-image class="mine-userimg" round fit="cover" :src="user.head" />
              </div>
              <div class="mine-data-name">
                <h1>{{user.nickname}}</h1>
              </div>
              <div class="mine-data-right">
               <img src="../assets/img/arrow.png">
              </div>
            </div>

          <div class="my-mine-count">
            <div class="mine-count-li">
              <div class="mine-count-centre1" @click="artiwen()">
                <div class="mine-count-cc">{{wencount}}</div>
                <div class="mine-count-title">文章</div>
              </div>
            </div>
            <div class="mine-count-li">
              <div class="mine-count-centre2" @click="artiimg()">
                <div class="mine-count-cc">{{imgcount}}</div>
                <div class="mine-count-title">图片</div>
              </div>
            </div>
            <div class="mine-count-li">
              <div class="mine-count-centre" @click="shoucang()">
                <div class="mine-count-img">
                  <img src="../assets/img/onCollection.png">
                </div>
                <div class="mine-count-title">我的收藏</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 间隔 -->
      <div class="my-mine-jian"></div>
      <!-- 消息 -->
      <div class="my-news-block">
        <div class="my-news" @click="clicknews">
          <div class="my-news-top">
            <div class="my-news-title">
              <h1>我的消息</h1>
            </div>
            <div class="my-news-right">
             <img src="../assets/img/arrow.png">
            </div>
          </div>
          <div class="my-news-tips">
            <div class="news-tips-img">
              <img src="../assets/img/ringtone.png">
            </div>
            <div class="news-tips-content"><p>今天未收到赞哦/收藏/</p></div>
          </div>
        </div>
      </div>
      <!-- 任务 -->
      <div class="my-task-block">
        <div class="my-task">
          <div class="my-task-top">
            <div class="my-task-title">
              <h1>任务中心</h1>
            </div>
          </div>
          <div class="my-task-sugar">
            <div class="news-task-content">拥有糖票</div>
            <div class="news-task-img">
              <img src="../assets/img/sugar.png">
            </div>
            <div class="news-task-count">{{user.sugar}}</div>
          </div>
          <div class="my-task-day" @click="clicktask()">
            <div class="task-day-left"><p>今日待领取{{dailing}}</p></div>
            <div class="task-day-right">
              <img src="../assets/img/right.png">
            </div>
          </div>
        </div>
      </div>
      <!-- 间隔 -->
      <div class="jiangegeg" ></div>
      <!-- 我的订单 -->
      <div class="my-order-block" style="display: none;">
        <div class="my-order">
          <div class="my-order-top">
            <div class="my-order-title">
              <h1>我的订单</h1>
            </div>
            <div class="my-order-right">
             <img src="../assets/img/arrow.png">
            </div>
          </div>
          <div class="my-order-centre">
            <div class="my-order-li"><p>待付款</p></div>
            <div class="my-order-li"><p>代发货</p></div>
            <div class="my-order-li"><p>待收货</p></div>
            <div class="my-order-li"><p>待评价</p></div>
          </div>
        </div>
      </div>
      <!-- 间隔 -->
      <div class="reewrwer" style="display: none;"></div>
      <!-- 标签 -->
      <div class="my-label-block">
        <div class="my-label">
          <div class="my-label-li">
            <div class="my-label-centre" @click="myfollow()">
              <div class="my-label-img">
                <img src="../assets/img/subscribe.png" />
              </div>
              <div class="my-label-title">我的关注</div>
            </div>
          </div>
        </div>
      </div>
      <div class="my-bommt"></div>
      </van-pull-refresh>
      </div>
    </div>
    <div class="my-nav">
      <BottomNavigation></BottomNavigation>
    </div>
  </div>
</template>

<script>
  import BottomNavigation from '../components/BottomNavigation.vue'
  import Errora from '../components/Errora.vue';
  import Loading from '../components/Loading.vue';
export default {
  name: 'My',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      request_Loading:true,
      isLoading: false,
      isCentre:false,
      isError:false,
      user:{},
      wencount:0,
      imgcount:0,
      dailing:0
    }
  },
  components: {
      BottomNavigation,
      Errora,
      Loading
  },
  created() {
    this.getlist("");
  },
  methods:{
    clicktask:function(){
      this.$router.push({ name:'Task'});
    },
    myfollow:function(){
      this.$router.push({ name:'Myfollow'});
    },
    homepage:function(){
      this.$router.push({ name:'SetupData'});
    },
    clicknews:function(){
      this.$router.push({ name:'Mynews'});
    },
    getlist:function(type){

      var that = this;
      var token = this.$store.state.token;
      if(token == ""){
        this.$router.push({ name:'Signin'});
        return;
      }
      this.$axios
           .post(this.$myStore.url+"/api/index/getuser",this.$qs.stringify({token:token}))
           .then(response=>{
              //成功
              var data = response.data;
              console.log("成功");
              if(response.status != 200){
                this.isError = true;
                this.request_Loading = false;
              }else{
                if(data.code == 1){
                  var resdata = data.data;
                  if(type == "refresh"){
                    this.isLoading = false;
                    this.$toast("刷新成功");
                  }
                  that.isCentre = true;
                  this.request_Loading = false;
                  this.user = resdata.user;
                  this.wencount = resdata.wencount;
                  this.imgcount = resdata.imgcount;
                  this.dailing = resdata.dailing;
              }else if(data.code == -2){
                // this.$store.commit('settoken',"");
                this.$router.push({ name:'Signin',query: { type:"out"}});
              }else{
                that.isError = true;
              }
            }
           })
           .catch(error=>{
              //失败
              if(error.response){
                this.request_Loading = false;
                this.isCentre = false;
                this.isError = true;
              }


       });
    },
    onRefresh:function(){
      this.getlist("refresh");
    },
    shoucang:function(){
      this.$router.push({ name:'Mycollection'});
    },
    artiwen:function(){
      this.$router.push({ name:'Articlewen'});
    },
    artiimg:function(){
      this.$router.push({ name:'Articleimg'});
    }


  }

}
</script>

<style>

  .my{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .my a{
    color: #444444;
  }
  .my-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    border-top: 1px gray solid;
  }
  .my-center{
    float: left;
    width: 100%;
  }
  .my-top-block{
    float: left;
    width: 100%;
    height: 80px;
    border-bottom: 2px solid #e0e0e0;
    position: fixed;
    top: 0px;
    z-index: 1000;
    background-color: #FFFFFF;
  }
  .my-block{
    float: left;
    width: 100%;
    margin-top: 95px;
  }
  .my-top-left{
    float: left;
    margin-left: 20px;
    height: 80px;
    width: 100px;
  }
  .my-top-left p{
    color: #444444;
    line-height: 80px;
    margin: auto;
    font-size: 40px;
  }
  .my-top-right{
    float: right;
    width: 50px;
    height: 80px;
    margin-right: 20px;
  }
  .my-top-right img{
    width: 50px;
    height: 50px;
    margin-top: 15px;
  }
  /* 资料 */
  .my-mine-block{
    width: 100%;
    float: left;
    margin-top: 30px;
  }
  .my-mine{
    width: 680px;
    margin: auto;
  }
  .my-mine-data{
    width: 100%;
    float: left;
    height: 150px;
  }
  .mine-data-img{
    float: left;
    width: 150px;
    height: 150px;
    border-radius: 10000px;
  }
  .mine-data-img .mine-userimg{
    float: left;
    width: 150px;
    height: 150px;
    border-radius: 10000px;
  }
  .mine-data-name{
    float: left;
    width: 400px;
    margin-left: 20px;
  }
  .mine-data-name h1{
     line-height: 150px;
     font-size: 40px;
     margin: 0px;
  }
  .mine-data-right{
    float: right;
    width: 30px;
    height: 150px;
  }
  .mine-data-right img{
    width: 30px;
    height: 30px;
    margin-top: 60px;
  }
  .my-mine-count{
    width: 100%;
    float: left;
    margin-top: 50px;
  }
  .mine-count-li{
    width: 226.6666666px;
    height: 100px;
    float: left;
  }
  .mine-count-centre{
    width: 100%;
  }
  .mine-count-centre1{
    width: 100%;
    border-right: 2px solid #ebebeb;
  }
  .mine-count-centre2{
    width: 100%;
    border-right: 2px solid #ebebeb;
  }
  .mine-count-cc{
    text-align: center;
    line-height: 50px;
    width: 100%;
    color: #444444;
    font-size: 30px;
  }
  .mine-count-title{
    text-align: center;
    height: 50px;
    width: 100%;
    font-size: 30px;
    color: #aaaaaa;
  }
  .mine-count-img{
    width: 100%;
    height: 50px;
  }
  .mine-count-img img{
    width: 40px;
    height: 40px;
    margin: auto;
    margin-top: 5px;
    margin-left: 98.333333px;
  }
  .my-mine-jian{
    float: left;
    width: 100%;
    height: 20px;
    background-color: #f5f5f5;
    margin-top: 40px;
  }
  /* 消息 */
  .my-news-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #ebebeb;
  }
  .my-news{
    width: 680px;
    margin: auto;
  }
  .my-news-top{
    width: 100%;
    float: left;
    height: 80px;
    margin-top: 20px;
  }
  .my-news-title{
    float: left;
    width: 300px;
  }
  .my-news-title h1{
    float: left;
    line-height: 80px;
    margin: 0px;
    font-size: 35px;
    color: #444444;
  }
  .my-news-right{
    float: right;
    height: 80px;
    width: 30px;
  }
  .my-news-right img{
    width: 30px;
    height: 30px;
    margin-top: 20px;
  }
  .my-news-tips{
    float: left;
    height: 100px;
  }
  .news-tips-img{
    width: 50px;
    float: left;
    height: 80px;
  }
  .news-tips-img img{
    width: 50px;
    margin-top: 15px;
    height: 50px;
  }
  .news-tips-content{
    float: left;
  }
  .news-tips-content p{
    margin: 0px;
    line-height: 80px;
    font-size: 28px;
    color: #777777;
    margin-left: 5px;
  }
  /* 消息 */
  .my-task-block{
    width: 100%;
    float: left;
  }
  .my-task{
    width: 680px;
    margin: auto;
  }
  .my-task-top{
    float: left;
    width: 100%;
    height: 80px;
  }
  .my-task-title{
     float: left;
     height: 80px;
  }
  .my-task-title h1{
    line-height: 80px;
    margin: 0px;
    font-size: 35px;
    color: #444444;
  }
  .my-task-sugar{
    float: left;
    height: 80px;
    width: 100%;
  }
  .news-task-content{
    float: left;
    line-height: 80px;
    font-size: 30px;
    color: #777777;
  }
  .news-task-img{
    float: left;
    margin-left: 20px;
  }
  .news-task-img img{
    width: 50px;
    height: 50px;
    margin-top: 15px;
  }
  .news-task-count{
    float: left;
    color: #444444;
    line-height: 80px;
    font-size: 35px;
    margin-left: 20px;
  }
  .my-task-day{
    width: 100%;
    float: left;
    height: 70px;
    background-color: #fcf3f4;
    border-radius: 10px;
  }
  .task-day-left{
    float: left;
  }
  .task-day-left p{
    float: left;
    line-height: 70px;
    margin: 0px;
    font-size: 30px;
    color: #ff7f7e;
    padding-left: 20px;
  }
  .task-day-right{
    float: right;
    width: 70px;
    width: 30px;
    margin-right: 20px;
  }
  .task-day-right img{
    width: 30px;
    height: 30px;
    margin-top: 20px;
  }
  .jiangegeg{
    height: 20px;
    float: left;
    width: 100%;
    background-color: #f5f5f5;
    margin-top: 40px;
  }
  /* 订单 */
  .my-order-block{
    width: 100%;
    float: left;
  }
  .my-order{
    width: 680px;
    margin: auto;
  }
  .my-order-top{
    float: left;
    width: 100%;
    height: 80px;
    margin-top: 20px;
  }
  .my-order-title{
     float: left;
     height: 80px;
  }
  .my-order-title h1{
    line-height: 80px;
    margin: 0px;
    font-size: 35px;
    color: #444444;
  }
  .my-order-right{
    float: right;
    height: 80px;
    width: 30px;
  }
  .my-order-right img{
    width: 30px;
    height: 30px;
    margin-top: 20px;
  }
  .my-order-centre{
    width: 100%;
    float: left;
  }
  .my-order-li{
    width: 25%;
    float: left;
    line-height: 80px;
    text-align: center;
    font-size: 30px;
  }
  .reewrwer{
    height: 20px;
    background-color: #f5f5f5;
    width: 100%;
    float: left;
  }
  .my-label-block{
    float: left;
    width: 100%;
  }
  .my-label{

    width: 100%;
  }
  .my-label-li{
    width: 25%;
    height: 180px;
    float: left;
  }
  .my-label-centre{
    width: 100%;

  }
  .my-label-img{
    width: 100%;
    height: 80px;
    margin-top: 40px;
  }
  .my-label-img img{
    width: 80px;
    height: 80px;
    margin-left: 55px;
  }
  .my-label-title{
    width: 100%;
    font-size: 28px;
    text-align: center;
    margin-top: 20px;
    color: #444444;
  }
  .my-bommt{
    float: left;
    height: 150px;
    width: 100%;
  }
</style>
