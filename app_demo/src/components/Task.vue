<template>
  <div class="task">
    <div class="task-centre">
      <div class="task-top-block">
        <van-nav-bar
          title="任务中心"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        >
        </van-nav-bar>
      </div>
      <div class="task-block" >
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>
      <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
      <div class="task-my-block">
        <div class="task-my">
          <div class="task-my-top">
            <div class="task-my-title">我的糖票</div>
            <div class="task-my-nr">
              <div class="task-nr-muber">{{user.sugar}}</div>
              <div class="task-nr-record" @click="clicktaskrecord()">糖票记录</div>
              <div class="task-nr-right" @click="glclick()">
                <div class="task-nr-right-img">
                  <img src="../assets/img/bulb.png">
                </div>
                <div class="task-nr-right-title">糖票获取攻略</div>
              </div>
            </div>
          </div>
          <div class="task-my-day">
            <div class="task-my-day-title">已经连续签到{{user.sign_count}}天</div>
            <div class="task-day-ul">
              <div class="task-day-li" v-for="(item,index) in day">
                <div class="task-day-li-centre">
                  <div class="task-li-title" v-bind:class="{tasklititleon: index <= user.sign_count-1}">{{item.daycount}}</div>
                  <div class="task-li-daytitle">{{item.tian}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="task-exchange-block">
        <div class="task-exchange">
          <div class="task-exchange-top">
            <div class="task-exchange-toptitle">糖票兑换</div>
            <div class="task-exchange-topimg">
              <img src="../assets/img/candy.png" />
            </div>
          </div>
          <div class="task-exchange-ul">
            <div class="task-exchange-li">
              <div class="task-exchange-licentre" @click="clickluckdraw()">
                <div class="task-exchange-img">
                  <img src="../assets/img/prize.png" />
                </div>
                <div class="task-exchange-lititle">糖票抽奖</div>
                <div class="task-exchange-fulititle">10糖票/次</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="task-dailytitle-block">
        <div class="task-dailytitle">
          <div class="task-dailytitle-top">
            <div class="task-dailytitle-title">日常任务</div>
            <div class="task-dailytitle-img">
              <img src="../assets/img/candy.png">
            </div>
          </div>
          <div class="task-dailytitle-futop">今日待领取{{dailing}}糖票</div>
        </div>
      </div>
      <div class="task-daily-block">
          <div class="task-daily-li">
            <div class="task-daily-centreli">
              <div class="task-daily-centre">
                <div class="task-daily-centreimg">
                  <img src="../assets/img/sign.png">
                </div>
                <div class="task-daily-licebtre">
                  <div class="task-licebtre-title">每日签到</div>
                  <div class="task-licebtre-futitle">领取10糖票，每天一次</div>
                </div>
                <div class="task-daily-right">
                  <div class="task-daily-buttom" @click="cliqd()" v-if="user.is_sign == 0">签到</div>
                </div>
              </div>
            </div>
            <div class="task-daily-di"></div>
          </div>
      </div>
      </van-pull-refresh>
      </div>
    </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  export default {
    name: 'Task',
    data () {
      return {
        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:true,
        isError:false,
        user:{},
        day:[
          {"daycount":10,"tian":"1天"},
          {"daycount":20,"tian":"2天"},
          {"daycount":30,"tian":"3天"},
          {"daycount":40,"tian":"4天"},
          {"daycount":50,"tian":"5天"},
          {"daycount":60,"tian":"6天"},
          {"daycount":70,"tian":"7天"},
         ],
         dailing:0
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.get_list("");
    },
    methods:{
      clickluckdraw:function(){
        this.$router.push({ name:'Luckdraw'});
      },
      glclick:function(){
        this.$router.push({ name:'Strategy'});
      },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      clicktaskrecord:function(){
        this.$router.push({ name:'Taskrecord'});
      },
      onRefresh:function(){
        this.get_list("refresh");
      },
      cliqd:function(){
        var token = this.$store.state.token;
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/getsign",this.$qs.stringify({
                   token: token
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('关注失败');
                   }else{
                     var resdata = data.data;
                     if(data.code == 1){
                        this.$toast.success('签到成功');
                         this.user.is_sign = 1;
                         this.user.sugar = this.user.sugar + parseInt(resdata.munber);
                         this.user.sign_count = this.user.sign_count + 1;
                     }
                      if(data.code == -1){
                        this.$toast.fail("签到失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('签到失败');
                  }
               });
      },
      get_list(type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/gettask",this.$qs.stringify({token:token}))
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
                      this.request_Loading = false;
                      this.user = resdata.user;
                      this.loading = false;
                      this.dailing = resdata.dailing;
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
    },
  }
</script>

<style>
  .task{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .task-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .task-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .task-my-block{
    width: 100%;
    float: left;
    background-color: #f5f5f5;

  }
  .task-my{
    width: 680px;
    margin: auto;
    margin-top: 20px;
  }
  .task-my-top{
    width: 100%;
    float: left;
  }
  .task-my-title{
    width: 100%;
    line-height: 50px;
    color: #343434;
    float: left;
    font-size: 30px;
  }
  .task-my-nr{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .task-nr-muber{
    color: #434343;
    font-size: 60px;
    line-height: 50px;
    float: left;
  }
  .task-nr-record{
    width: 150px;
    float: left;
    color: #e58f98;
    line-height: 50px;
    font-size: 25px;
    margin-left: 20px;
  }
  .task-block{
    width: 100%;
    float: left;
  }
  .task-nr-right{
    float: right;
    width: 205px;
  }
  .task-nr-right-img{
    width: 40px;
    height: 40px;
    float: left;
  }
  .task-nr-right-img img{
    width: 40px;
    height: 40px;
  }
  .task-nr-right-title{
    width: 160px;
    float: left;
    line-height: 40px;
    color: #434343;
    font-size: 25px;
    margin-left: 5px;
  }
  .task-my-day{
    width: 100%;
    float: left;
    margin-top: 20px;
    background-color: #FFFFFF;
    padding-bottom: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
  }
  .task-my-day-title{
    width: 100%;
    line-height: 50px;
    color: #343434;
    float: left;
    font-size: 30px;
    padding-left: 10px;
    margin-top: 20px;
  }
  .task-day-ul{
    width: 100%;
    float: left;
  }
  .task-day-li{
    width:97.140px;
    float: left;
  }
  .task-day-li-centre{
    width: 77.148px;
    margin: 10px;
  }
  .task-li-title{
    width: 77.148px;
    height: 77.148px;
    text-align: center;
    line-height: 77.148px;
    background-color: #fbedec;
    font-size: 30px;
    color: #eb8f9a;
    border-radius: 1000px;
    float: left;
  }
  .tasklititleon{
    background-color: #fc8888 !important;
    color: #FFFFFF;
  }
  .task-li-daytitle{
    width: 77.148px;
    float: left;
    font-size: 25px;
    line-height: 30px;
    text-align: center;
    color: #353535;
    margin-top: 10px;
  }

  .task-exchange-block{
    width: 100%;
    margin-top: 20px;
    float: left;
  }
  .task-exchange{
    width: 680px;
    margin: auto;
  }
  .task-exchange-top{
    width: 100%;
    float: left;
  }
  .task-exchange-toptitle{
    float: left;
    line-height: 50px;
    color: #353535;
    font-size: 40px;
  }
  .task-exchange-topimg{
    float: left;
    width: 50px;
    height: 50px;
    margin-left: 20px;
  }
  .task-exchange-topimg img{
    width: 50px;
    height: 50px;
  }
  .task-exchange-ul{
    width: 100%;
    float: left;
    margin-top: 20px;
  }
  .task-exchange-li{
    width: 340px;
    float: left;
  }
  .task-exchange-licentre{
    width: 320px;
    float: left;
  }
  .task-exchange-img{
    width: 320px;
    height: 200px;
    float: left;
  }
  .task-exchange-img img{
    width: 320px;
    height: 180px;
  }
  .task-exchange-lititle{
    float: left;
    width: 100%;
    line-height: 70px;
    font-size: 35px;
    color: #3a3a3a;
  }
  .task-exchange-fulititle{
    float: left;
    width: 100%;
    line-height: 50px;
    font-size: 30px;
    color: #a8a8a8;
  }
  .task-dailytitle-block{
    width: 100%;
    float: left;
    margin-top: 50px;
  }
  .task-dailytitle{
    width: 680px;
    margin: auto;
  }
  .task-dailytitle-top{
    float: left;
    width: 100%;
  }
  .task-dailytitle-title{
    line-height: 60px;
    font-size: 40px;
    color: #3b3b3b;
    float: left;
  }
  .task-dailytitle-img{
    float: left;
    width: 50px;
    height: 50px;
  }
  .task-dailytitle-img img{
    width: 50px;
    height: 50px;
    margin: 5px;
  }
  .task-dailytitle-futop{
    float: left;
    width: 100%;
    line-height: 80px;
    font-size: 30px;
    color: #7a7a7a;
    border-bottom: 2px solid #e8e8e8;
  }
  .task-daily-block{
    width: 100%;
    float: left;
  }
  .task-daily-li{
    width: 100%;
    float: left;
  }
  .task-daily-centreli{
    width: 100%;
    float: left;
    margin-top: 20px;
    margin-bottom: 20px;
  }
  .task-daily-centre{
    margin: auto;
    width: 680px;

  }
  .task-daily-di{
    float: left;
    height: 5px;
    width: 100%;
    background-color: #fafafa;
  }
  .task-daily-centreimg{
    float: left;
    width: 100px;
    height: 100px;
  }
  .task-daily-centreimg img{
    width: 100px;
    height: 100px;
  }
  .task-daily-licebtre{
    float: left;
    width: 400px;
    height: 100px;
    margin-left: 20px;
  }
  .task-licebtre-title{
    width: 100%;
    line-height: 50px;
    font-size: 35px;
    color: #3b3b3b;
    float: left;
  }
  .task-licebtre-futitle{
    width: 100%;
    line-height: 50px;
    font-size: 25px;
    color: #747474;
    float: left;
  }
  .task-daily-right{
    float: left;
    width: 150px;
  }
  .task-daily-buttom{
    width: 150px;
    height: 70px;
    background-color: #ff7f7f;
    line-height: 70px;
    text-align: center;
    font-size: 30px;
    color: #FFFFFF;
    border-radius: 20px;
    margin-top: 15px;
  }
</style>
