<template>
  <div class="taskrecord">
    <div class="taskrecord-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="taskrecord-top-block">
        <van-nav-bar
          title="抽奖记录"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        >
        </van-nav-bar>
      </div>
      <div class="allcomm-error-block" v-if="isError">
        <van-empty image="error" description="网络错误,请刷新" />
      </div>
      <!-- 请求中 -->
      <div class="artdateils-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>
      <div class="taskrecord-block">
      <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
      <div class="taskrecord-list-block">
        <div class="taskrecord-list-li" v-for="item in list">
          <div class="taskrecord-li-centren">
            <div class="taskrecord-li-left">
              <div class="taskrecord-li-title">{{item.name}}</div>
              <div class="taskrecord-li-time">{{item.ctime}}</div>
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
    name: 'Prizerecord',
    data () {
      return {
        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:true,
        isError:false,
        list:[],
        page:1,
        allpage:0,
        loading: false,
        finished: false,
        error:false,
        commentval:""
      }
    },
    components: {
        Errora,
        Loading
    },
      created() {
        console.log("awdawd");
         this.get_list(1,"");

      },
    methods:{
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onLoad:function(){
        if(this.page <= this.allpage){
          var p = this.page + 1;
          this.get_list(p,"");
        }
      },

      get_list(p,type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/getprizerecord",this.$qs.stringify({id:that.id,token:token,p:p}))
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
                    if(p == 1){
                      // that.isCentre = true;
                      this.request_Loading = false;
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
                    this.isError = true;
                    if(p != 1){
                      this.error = true;
                    }
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
        this.get_list(1,"refresh");
      },
    },
  }
</script>

<style>
  .taskrecord{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .taskrecord-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .taskrecord-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .taskrecord-list-block{
    width: 100%;
    float: left;
  }
  .taskrecord-list-li{
    float: left;
    width: 100%;
    border-bottom: 1px solid #eeeeee;
  }
  .taskrecord-block{
    float: left;
    width: 100%;
  }
  .taskrecord-li-centren{
    width: 680px;
    margin: auto;
    height: 100px;
  }
  .taskrecord-li-left{
    float: left;
    width: 550px;
    height: 100px;
  }
  .taskrecord-li-title{
    line-height: 60px;
    font-size: 30px;
    color: #444444;
  }
  .taskrecord-li-time{
    line-height: 40px;
    font-size: 25px;
    color: #a7a7a7;
  }
  .taskrecord-li-right{
    float: right;
    line-height: 100px;
    color: #f97970;
    font-size: 30px;
  }
</style>
