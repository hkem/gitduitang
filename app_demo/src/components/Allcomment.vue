<template>
  <div class="allcomm">
    <div class="allcomm-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="allcomm-top-block">
        <van-nav-bar
          title="全部评价"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
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

        <div class="allcomm-comment-block">
          <div class="allcomm-comment">
        <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
            <div class="allcomm-commentlist-ul">
              <div class="allcomm-commentlist-li" v-for="(item,index) in list" :key="item.id">
                <div class="allcomm-commentlist-user">
                  <div class="allcomm-commentlist-user-img" @click="homepage(item.uid)">
                    <van-image class="allcomm-userimg" round fit="cover" :src="item.head" />
                  </div>
                  <div class="allcomm-commentlist-user-ul">
                    <div class="allcomm-commentlist-user-name">{{item.nickname}}</div>
                    <div class="allcomm-commentlist-user-time">{{item.ctime}}</div>
                  </div>
                  <div class="allcomm-commentlist-user-fabulous">
                    <div class="allcomm-commentlist-fabulous-img" @click="commentfabulous(item.id,index)">
                      <img v-if="item.is_fabulous == 1" src="../assets/img/chioce_Fabulous.png"/>
                      <img v-if="item.is_fabulous == 0" src="../assets/img/fabulous.png"/>
                    </div>
                    <div class="allcomm-commentlist-fabulous-munber">{{item.fabulous}}</div>
                  </div>
                </div>
                <div class="allcomm-commentlist-centre">
                  <div class="allcomm-commentlist-title"><p>{{item.centre}}</p></div>
                </div>
              </div>
            </div>
            </van-pull-refresh>
          </div>
        </div>
        <div class="allcomm-nav">
          <div class="allcomm-comment-block">
            <div class="allcomm-comment-inout">
              <input type="text" v-model="commentval" placeholder="你留下的每一句,他都能收到哦"/>
            </div>
            <div class="allcomm-comment-collection" @click="fasong()">发送</div>
          </div>
        </div>
        </div>
</van-list>
<div class="allcomm-ddi-block"></div>
    </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  export default {
    name: 'Allcomment',
    data () {
      return {

        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:true,
        isError:false,
        id:0,
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
      this.id = this.$route.query.id;
       this.get_list(1);
    },
    methods:{
      homepage:function(val){
        this.$router.push({ name:'Homepage',query: { id:val}});
      },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        this.edit = this.edit ? false : true;
      },
      clickhomepage:function(event){
        this.edit = false;
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
             .post(this.$myStore.url+"/api/index/allcomment",this.$qs.stringify({id:that.id,token:token,p:p}))
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
      commentfabulous:function(event,key){
        var token = this.$store.state.token;
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/comment_fabulous",this.$qs.stringify({
                   token: token,
                   id:event,
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('点赞失败');
                   }else{
                     if(data.code == 1){
                        this.$toast.success('取消成功');

                         this.list[key]["is_fabulous"] = 0;
                         this.list[key]["fabulous"] -= 1;
                     }
                     if(data.code == 2){
                        this.$toast.success('点赞成功');

                         this.list[key]["is_fabulous"] = 1;
                         this.list[key]["fabulous"] += 1;
                     }
                     if(data.code == 3){
                        this.$toast.fail('取消失败');

                     }
                     if(data.code == 4){
                        this.$toast.fail('点赞失败');

                     }


                      if(data.code == -1){
                        this.$toast.fail("点赞失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('点赞失败');
                  }
               });
      },
      fasong:function(){
        var token = this.$store.state.token;
         if(this.commentval == ""){
           this.$toast("请填写评论内容");
           return;
         }
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/comment",this.$qs.stringify({
                   token: token,
                   id:that.id,
                   centre:that.commentval
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('评论失败');
                   }else{
                     if(data.code == 1){
                        this.$toast.success('评论成功');
                         this.commentval = "";
                     }
                      if(data.code == -1){
                        this.$toast.fail("评论失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('评论失败');
                  }
               });
      },
    }
  }
</script>

<style>
  .allcomm-ddi-block{
    float: left;
    width: 100%;
    height: 150px;
  }
  .allcomm-error-block{
    float: left;
    width: 100%;
  }
  .allcomm{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .allcomm-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .allcomm-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .allcomm-commentlist-ul{
    width: 680px;
    float: left;
  }
  .allcomm-commentlist-li{
    float: left;
    width: 100%;
    margin-top: 30px;
  }
  .allcomm-commentlist-user{
    float: left;
    width: 100%;
    height: 80px;
  }
  .allcomm-commentlist-user-img{
    float: left;
    width: 80px;
    height: 80px;
    border-radius: 1000px;
  }
  .allcomm-commentlist-user-img .allcomm-userimg{
    width: 80px;
    height: 80px;
    border-radius: 1000px;
  }
  .allcomm-commentlist-user-ul{
    width: 400px;
    float: left;
    height: 5px;
    margin-left: 20px;
  }
  .allcomm-commentlist-user-name{
    line-height: 40px;
    font-size: 30px;
    color: #454545;
    float: left;
    width: 100%;
  }
  .allcomm-commentlist-user-time{
    float: left;
    width: 100%;
    font-size: 20px;
    color: #aaaaaa;
    line-height: 40px;
  }
  .allcomm-commentlist-user-fabulous{
    float: right;
    width: 100px;
    margin-top: 20px;
  }
  .allcomm-commentlist-fabulous-img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .allcomm-commentlist-fabulous-img img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .allcomm-commentlist-fabulous-munber{
    width: 40px;
    line-height: 40px;
    float: left;
    margin-left: 15px;
    color: #a9a9a9;
    font-size: 30px;
  }
  .allcomm-commentlist-centre{
    float: left;
    width: 100%;
  }
  .allcomm-commentlist-title{
    width: 520px;
    margin-left: 100px;

  }
  .allcomm-commentlist-title p{
    margin: 0px;
    font-size: 30px;
    float: #3e3e3e;
  }

  .allcomm-comment-block{
    float: left;
    width: 100%;
  }
  .allcomm-comment{
    margin: auto;
    width: 680px;
  }
  .allcomm-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    background-color: #FFFFFF;
    border-top: 2px solid #e8e8e8;
  }

  .allcomm-comment-inout{
    width: 630px;
    float: left;
    height: 100px;
    margin-left: 20px;
  }
  .allcomm-comment-inout input{
    border-radius: 0px;
    height: 60px;
    margin-top: 20px;
    background-color: #f0f0f0;
    border: 0px;
    width: 590px;
  }
  .allcomm-comment-collection{
    width: 100px;
    line-height: 100px;
    color: #f36969;
    float: left;
    font-size: 40px;
  }
</style>
