<template>
  <div class="grasslist">
    <div class="grasslist-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="grasslist-top-block">
        <van-nav-bar
          title="值得种草"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        >

        </van-nav-bar>
      </div>
      <div class="grasslist-block">
      <div class="allcomm-error-block" v-if="isError">
        <van-empty image="error" description="网络错误,请刷新" />
      </div>
      <!-- 请求中 -->
      <div class="artdateils-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>
      <div v-if="isCentre">
      <div class="grasslist-list-block">
        <div class="grasslist-list">
          <van-pull-refresh  v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
          <div class="grasslist-list-ul">
            <div class="grasslist-list-li" v-for="item in list" :key="item.id" @click="xq(item.id)">
              <div class="grasslist-li-img">
                <van-image class="grasslistall-li-img" fit="cover" :src="item.image" />
              </div>
              <div class="grasslist-li-title"><h6 class="van-multi-ellipsis--l2">{{item.name}}</h6></div>
              <div class="grasslist-li-user">
                <div class="grasslist-user-image">
                  <van-image class="grasslist-userimg" round fit="cover" :src="item.head" />
                </div>
                <div class="grasslist-user-name">{{item.nickname}}</div>
                <div class="grasslist-user-red">
                  <div class="grasslist-user-redimg">
                    <img src="../assets/img/browse.png">
                  </div>
                  <div class="grasslist-user-redmbber">{{item.readq}}</div>
                </div>
              </div>
            </div>

          </div>
          </van-pull-refresh>
        </div>
      </div>
      </div>
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
    name: 'Grasslist',
    data () {
      return {

        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:true,
        isError:false,
        loading: false,
        finished: false,
        error:false,
        page:1,
        allpage:0,
        list:[],
        id:0
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.id = this.$route.query.type;
      this.getlist(1,"");
    },
    methods:{
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        this.edit = this.edit ? false : true;
      },
      clickhomepage:function(event){
        this.edit = false;
      },
      xq:function(e){
        this.$router.push({ name:'Grassdatelis',query: { id:e}});
      },
      onLoad() {
        if(this.page <= this.allpage){
          var p = this.page + 1;
          this.getlist(p,"");
        }
      },
      onRefresh:function(){
        this.getlist(1,"refresh");
      },
      getlist(p,type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/grsslist",this.$qs.stringify({type:that.id,token:token,p:p}))
             .then(response=>{
                //成功
                var data = response.data;
                console.log("成功");

                if(response.status != 200){
                  this.$toast("获取失败");
                }else{
                  if(data.code == 1){
                    var resdata = data.data;
                    if(type == "refresh"){
                      this.$toast("刷新成功");
                      this.isLoading = false;
                    }
                    if(p == 1){
                      this.list = resdata.list;
                      this.allpage = resdata.pagecount;
                      this.isCentre = true;
                      this.request_Loading = false;
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
      }
    }
  }
</script>

<style>
  .grasslist{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .grasslist-block{
    float: left;
    width: 100%;
    margin-top: 95px;
  }
  .grasslist-centre{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .grasslist-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
    position: fixed;
    top:0px;
    z-index: 1000;
  }
  .grasslist-list-block{
    float: left;
    width: 100%;
  }
  .grasslist-list{
    width: 680px;
    margin: auto;
  }
  .grasslist-list-ul{
    width: 680px;
    float: left;
    margin-top: 20px;
  }
  .grasslist-list-li{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .grasslist-li-img{
    width: 680px;
    height: 300px;
    float: left;
  }
  .grasslist-li-img .grasslistall-li-img{
    width: 680px;
    height: 300px;
  }
  .grasslist-li-title{
    width: 100%;
    float: left;
  }
  .grasslist-li-title h6{
    color: #3d3d3d;
    font-size: 30px;
    margin: 0px;
  }
  .grasslist-li-user{
    float: left;
    height: 80px;
    width: 100%;
  }
  .grasslist-user-image{
    float: left;
    width: 80px;
    height: 80px;
    border-radius: 1100px;
  }
  .grasslist-user-image .grasslist-userimg{
    width: 80px;
    height: 80px;
    border-radius: 1100px;
  }
  .grasslist-user-name{
    float: left;
    line-height: 80px;
    padding: 0px 10px;
    color: #484848;
    font-size: 30px;
  }
  .grasslist-user-red{
    float: right;
    height: 50px;
    margin-top: 15px;
    margin-right: 20px;
  }
  .grasslist-user-redimg{
    float: left;
    height: 50px;
    width: 50px;
  }
  .grasslist-user-redimg img{
    height: 50px;
    width: 50px;
  }
  .grasslist-user-redmbber{
    float: left;
    line-height: 50px;
    font-size: 20px;
    color: #6d6d6d;
  }



  .grasslist-user-collection{
    float: right;
    height: 50px;
    margin-top: 15px;

  }
  .grasslist-collectionimg{
    float: left;
    height: 50px;
    width: 50px;
  }
  .grasslist-collectionimg img{
    height: 50px;
    width: 50px;
  }
  .grasslist-collectionmber{
    float: left;
    line-height: 50px;
    font-size: 20px;
    color: #6d6d6d;
  }
</style>
