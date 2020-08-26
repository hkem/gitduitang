<template>
  <div class="artdateils">
    <div class="artdateils-centre">
      <div class="artdateils-top-block">
        <van-nav-bar
          title="详情"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        >

        </van-nav-bar>
      </div>
      <!-- 错误 -->
      <div class="artdateils-error-block" v-if="isError">
        <van-empty image="error" description="网络错误,请刷新" />
      </div>
      <!-- 请求中 -->
      <div class="artdateils-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>

      <div v-if="isCentre">

        <div class="artdateils-centre-block">
          <div class="artdateils-cen">
            <!-- 文章标题 -->
            <div class="artdateils-wenz-block" v-if="centre.type == 2">
              {{centre.name}}
            </div>
            <div class="artdateils-user-block">
              <div class="artdateils-user-img" @click="homepage(centre.uid)">
                <van-image class="artdateils-userimg" round fit="cover" :src="centre.head" />
                </div>
              <div class="artdateils-user-ul">
                <div class="artdateils-user-name">{{centre.nickname}}</div>
                <div class="artdateils-user-fu">{{centre.ctime}}</div>
              </div>
              <div class="artdateils-user-follow">
                <van-button class="user-follow" v-if="centre.is_follow == 0" size="small" @click="gzz(centre.uid)" type="primary">+关注</van-button>
                <van-button class="follow-user-follow" v-if="centre.is_follow == 1" size="small" @click="gzz(centre.uid)" type="primary">已关注</van-button>
              </div>
            </div>
            <div class="artdateils-art-block">
              <!-- 文章 -->
              <div class="artdateils-wenzhang-centre" v-if="centre.type == 2" v-html="centre.centre"></div>
              <!-- 图片 -->
              <div v-if="centre.type == 1">
                <div class="artdateils-art-title">
                  {{centre.name}}
                </div>
                <!-- 多图 -->
                <div class="artdateils-art-image" v-if="centre.imgcount != 4">
                  <div class="artdateils-image-li" v-for="(value,name) in centre.image">
                    <van-image class="artdateilsall-li-img" fit="cover" :src="value" @click="yulan(name)" />
                  </div>
                </div>
                <!-- 四张图 -->
                <div class="artdateils-si-image" v-if="centre.imgcount == 4">
                  <div class="artdateils-siimage-li" v-for="(value,name) in centre.image">
                    <van-image class="artdateils-si-li-img" fit="cover" :src="value" @click="yulan(name)" />
                  </div>
                </div>
              </div>
            </div>
            <!-- 点赞收藏 -->
            <div class="artdateils-ddd-block">
              <div class="artdateils-ddd">
                <div class="artdateils-comment-collection" @click="clickcollection(centre.id)">
                  <div class="artdateils-comment-li" >
                    <div class="artdateils-collection-img">
                      <img v-if="centre.is_collection == 0" src="../assets/img/collection.png"/>
                      <img v-if="centre.is_collection == 1" src="../assets/img/chioce_collection.png"/>
                    </div>
                    <div class="artdateils-collection-munmber">
                      {{centre.collection}}
                    </div>
                  </div>
                </div>
                <div class="artdateils-comment-fabulous"  @click="clickfabulous(centre.id)">
                  <div class="artdateils-fabulous-li" >
                    <div class="artdateils-fabulous-img">
                      <img v-if="centre.is_fabulous == 0" src="../assets/img/fabulous.png"/>
                      <img v-if="centre.is_fabulous == 1" src="../assets/img/chioce_Fabulous.png"/>
                    </div>
                    <div class="artdateils-fabulous-munmber">
                      {{centre.fabulous}}
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="artdateils-commentlist-block">
              <div class="artdateils-commentlist-block">所有评论</div>
              <div class="artdateils-null-block" v-if="commentcount == 0">
                <van-empty image="error" description="暂无评论" />
              </div>
              <div class="artdateils-commentlist-ul" v-if="commentcount != 0">
                <div class="artdateils-commentlist-li" v-for="(item, index) in commentlist" :key="item.id">
                  <div class="artdateils-commentlist-user">
                    <div class="commentlist-user-img" @click="homepage(item.uid)">
                      <van-image class="commentlist-userimg" round fit="cover" :src="item.head" />
                    </div>
                    <div class="commentlist-user-ul">
                      <div class="commentlist-user-name">{{item.nickname}}</div>
                      <div class="commentlist-user-time">{{item.ctime}}</div>
                    </div>
                    <div class="commentlist-user-fabulous">
                      <div class="commentlist-fabulous-img" @click="commentfabulous(item.id,index)">
                        <img v-if="item.is_fabulous == 0" src="../assets/img/fabulous.png"/>
                        <img v-if="item.is_fabulous == 1" src="../assets/img/chioce_Fabulous.png"/>
                      </div>
                      <div class="commentlist-fabulous-munber">{{item.fabulous}}</div>
                    </div>
                  </div>
                  <div class="artdateils-commentlist-centre">
                    <div class="artdateils-commentlist-title"><p>{{item.centre}}</p></div>
                  </div>
                </div>
              </div>
              <div class="artdateils-commentlist-all" v-if="commentcount > 5" @click="allcom(centre.id)">查看全部评论({{commentcount}})</div>
            </div>
            <div class="artdateils-di-block"></div>
          </div>
        </div>

      <div class="artdateils-nav">
        <div class="artdateils-comment-block">
          <div class="artdateils-comment-inout">
            <input type="text" v-model="commentval" placeholder="你留下的每一句,他都能收到哦"/>
          </div>

          <div class="artdateils-comment-button" @click="subb">发送</div>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  import { ImagePreview } from 'vant';
  export default {
    name: 'Articledateils',
    data () {
      return {
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        edit: false,
        id:0,
        centre:{},
        imagelist:[],
        commentval:"",
        commentlist:[],
        commentcount:0,

      }
    },
    components: {
        Errora,
        Loading
    },
    created(){
      this.id = this.$route.query.id;
      var that = this;
      var token = this.$store.state.token;
      this.$axios
           .post(this.$myStore.url+"/api/index/article_dateils",this.$qs.stringify({id:that.id,token:token}))
           .then(response=>{
              //成功
              var data = response.data;
              console.log("成功");
              if(response.status != 200){
                this.isError = true;
              }else{
                if(data.code == 1){
                  var resdata = data.data;
                  this.banner = resdata.banner;
                  this.request_Loading = false;
                  that.isCentre = true;
                  this.centre = resdata.list;
                  this.commentlist = resdata.comment;
                  this.commentcount = resdata.commentcount;
                }else{
                  this.isError = false;
                }
              }
           })
           .catch(error=>{
              //失败
              this.request_Loading = false;
              this.isCentre = false;
              this.isError = true;

       });
    },
    methods:{
      homepage:function(val){
        this.$router.push({ name:'Homepage',query: { id:val}});
      },
      yulan:function(evnet){
        ImagePreview({
          images: this.centre.image,
          startPosition: evnet
        });
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
      allcom:function(cen){
        this.$router.push({ name:'Allcomment',query: { id:cen}});
      },
      subb:function(){
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
      gzz:function(event){
        var token = this.$store.state.token;
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/follow",this.$qs.stringify({
                   token: token,
                   id:event,
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('关注失败');
                   }else{
                     if(data.code == 1){
                        this.$toast.success('取消成功');
                         this.commentval = "";
                         this.centre.is_follow = 0;
                     }
                     if(data.code == 2){
                        this.$toast.success('关注成功');
                         this.commentval = "";
                         this.centre.is_follow = 1;
                     }
                     if(data.code == 3){
                        this.$toast.fail('取消失败');
                         this.commentval = "";
                     }
                     if(data.code == 4){
                        this.$toast.fail('关注失败');
                         this.commentval = "";
                     }


                      if(data.code == -1){
                        this.$toast.fail("关注失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('关注失败');
                  }
               });
      },
      clickfabulous:function(event){
        var token = this.$store.state.token;
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/fabulous",this.$qs.stringify({
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
                         this.commentval = "";
                         this.centre.is_fabulous = 0;
                         this.centre.fabulous -= 1;
                     }
                     if(data.code == 2){
                        this.$toast.success('点赞成功');
                         this.commentval = "";
                         this.centre.is_fabulous = 1;
                         this.centre.fabulous += 1;
                     }
                     if(data.code == 3){
                        this.$toast.fail('取消失败');
                         this.commentval = "";
                     }
                     if(data.code == 4){
                        this.$toast.fail('点赞失败');
                         this.commentval = "";
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
      clickcollection:function(event){
        var token = this.$store.state.token;
         if(token == ""){
           this.$router.push({ name:'Signin'});
           return;
         }
         var that = this;
        this.$axios
                .post(this.$myStore.url+"/api/index/collection",this.$qs.stringify({
                   token: token,
                   id:event,
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('收藏失败');
                   }else{
                     if(data.code == 1){
                        this.$toast.success('取消成功');
                         this.commentval = "";
                         this.centre.is_collection = 0;
                         this.centre.collection -= 1;
                     }
                     if(data.code == 2){
                        this.$toast.success('收藏成功');
                         this.commentval = "";
                         this.centre.is_collection = 1;
                         this.centre.collection += 1;
                     }
                     if(data.code == 3){
                        this.$toast.fail('取消失败');
                         this.commentval = "";
                     }
                     if(data.code == 4){
                        this.$toast.fail('收藏失败');
                         this.commentval = "";
                     }


                      if(data.code == -1){
                        this.$toast.fail("收藏失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('收藏失败');
                  }
               });
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
                         this.commentval = "";
                         this.commentlist[key]["is_fabulous"] = 0;
                         this.commentlist[key]["fabulous"] -= 1;
                     }
                     if(data.code == 2){
                        this.$toast.success('点赞成功');
                         this.commentval = "";
                         this.commentlist[key]["is_fabulous"] = 1;
                         this.commentlist[key]["fabulous"] += 1;
                     }
                     if(data.code == 3){
                        this.$toast.fail('取消失败');
                         this.commentval = "";
                     }
                     if(data.code == 4){
                        this.$toast.fail('收藏失败');
                         this.commentval = "";
                     }


                      if(data.code == -1){
                        this.$toast.fail("收藏失败");
                      }
                      if(data.code == -2){
                         this.$router.push({ name:'Signin'});
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.$toast.fail('收藏失败');
                  }
               });
      }
    }
  }
</script>

<style>
  .artdateils-null-block{
    float: left;
    width: 100%;
  }
  .artdateils-wenz-block{
    width: 100%;
    line-height: 80px;
    font-size: 40px;
    color: #323232;
  }
  .artdateils-error-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .artdateils-loading-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .artdateils{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .artdateils-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .artdateils-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .artdateils-centre-block{
    float: left;
    width: 100%;
  }
  .artdateils-cen{
    width: 680px;
    margin: auto;
  }
  .artdateils-user-block{
    width: 680px;
    margin: auto;
    height: 100px;
    margin-top: 20px;
  }
  .artdateils-user-img{
    width: 100px;
    height: 100px;
    float: left;
    border-radius: 1000px;
  }
  .artdateils-user-img .artdateils-userimg{
    width: 100px;
    height: 100px;
    border-radius: 1000px;
  }
  .artdateils-user-ul{
    float: left;
    height: 100px;
    width: 430px;
    margin-left: 20px;
  }
  .artdateils-user-name{
    color: #404040;
    font-size: 35px;
    line-height: 50px;
    float: left;
    width: 100%;
  }
  .artdateils-user-fu{
    float: left;
    width: 100%;
    line-height: 50px;
    font-size: 30px;
    color: #aeaeae;
  }
  .artdateils-user-follow{
    float: right;
    height: 100px;
    width: 120px;
  }
  .artdateils-user-follow .user-follow{
    border-radius: 100px;
    margin-top: 20px;
    background-color: #fd807c;
    border: 1px solid #fd807c;
  }
  .artdateils-user-follow .follow-user-follow{
    border-radius: 100px;
    margin-top: 20px;
    border: 1px solid #fd807c;
    background-color: #FFFFFF;
    color: #fd807c;
  }
  .artdateils-art-block{
    float: left;
    width: 680px;
    margin: auto;
    margin-top: 20px;
  }
  .artdateils-art-title{
    float: left;
    font-size: 35px;
    color: #424242;
    width: 100%;
  }
  .artdateils-art-image{
    width: 100%;
    float: left;
  }
  .artdateils-image-li{
    width: 226.66666px;
    height: 226.66666px;
    float: left;
  }
  .artdateils-image-li .artdateilsall-li-img{
    width: 216.66666px;
    height: 216.66666px;
    margin: 5px;
  }
  .artdateils-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    background-color: #FFFFFF;
    border-top: 2px solid #e8e8e8;
  }
  .artdateils-comment-block{
    width: 100%;
    height: 100px;
  }
  .artdateils-comment-inout{
    width: 580px;
    float: left;
    height: 100px;
    margin-left: 20px;
  }
  .artdateils-comment-inout input{
    border-radius: 0px;
    height: 60px;
    margin-top: 20px;
    background-color: #f0f0f0;
    border: 0px;
    width: 580px;
  }
  .artdateils-comment-collection{
    width: 242px;
    float: left;
    height: 80px;
    border-radius: 1000px 0px 0px 1000px;
    border: 1px solid #cccccc;
  }
  .artdateils-comment-li{
    width: 150px;
    margin: auto;
    height: 80px;
  }
  .artdateils-collection-img{
    width: 50px;
    height: 50px;
    float: left;
    margin-top: 10px;
    margin-left: 20px;
  }
  .artdateils-collection-img img{
    width: 50px;
    height: 50px;
  }
  .artdateils-collection-munmber{
    line-height: 80px;
    font-size: 30px;
    text-align: center;
    color: #7a7a7a;
    float: left;
    margin-left: 10px;
  }
  .artdateils-comment-fabulous{
    width: 242px;
    float: left;
    height: 80px;
    border-radius: 0px 1000px 1000px 0px;
    border: 1px solid #cccccc;
  }
  .artdateils-fabulous-li{
    width: 150px;
    margin: auto;
    height: 80px;
  }
  .artdateils-fabulous-img{
    width: 50px;
    height: 50px;
    float: left;
    margin-top: 10px;
  }
  .artdateils-fabulous-img img{
    width: 50px;
    height: 50px;
  }
  .artdateils-fabulous-munmber{
    line-height: 80px;
    font-size: 30px;
    text-align: center;
    color: #7a7a7a;
    float: left;
    margin-left: 20px;
  }
  .artdateils-commentlist-block{
    float: left;
    width: 100%;
  }
  .artdateils-commentlist-block{
    width: 100%;
    float: left;
    line-height: 80px;
    color: #787878;
    font-size: 35px;
  }
  .artdateils-commentlist-ul{
    width: 680px;
    float: left;
  }
  .artdateils-commentlist-li{
    float: left;
    width: 100%;
    margin-top: 30px;
  }
  .artdateils-commentlist-user{
    float: left;
    width: 100%;
    height: 80px;
  }
  .commentlist-user-img{
    float: left;
    width: 80px;
    height: 80px;
    border-radius: 1000px;
  }
  .commentlist-user-img .commentlist-userimg{
    width: 80px;
    height: 80px;
    border-radius: 1000px;
  }
  .commentlist-user-ul{
    width: 400px;
    float: left;
    height: 5px;
    margin-left: 20px;
  }
  .commentlist-user-name{
    line-height: 40px;
    font-size: 30px;
    color: #454545;
    float: left;
    width: 100%;
  }
  .commentlist-user-time{
    float: left;
    width: 100%;
    font-size: 20px;
    color: #aaaaaa;
    line-height: 40px;
  }
  .commentlist-user-fabulous{
    float: right;
    width: 100px;
    margin-top: 20px;
  }
  .commentlist-fabulous-img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .commentlist-fabulous-img img{
    float: left;
    width: 40px;
    height: 40px;
  }
  .commentlist-fabulous-munber{
    width: 40px;
    line-height: 40px;
    float: left;
    margin-left: 15px;
  }
  .artdateils-commentlist-centre{
    float: left;
    width: 100%;
  }
  .artdateils-commentlist-title{
    width: 520px;
    margin-left: 100px;
  }
  .artdateils-commentlist-title p{
    margin: 0px;
    font-size: 30px;
    color: #3e3e3e;
    line-height: 45px;
  }
  .artdateils-di-block{
    height: 150px;
    float: left;
    width: 100%;
  }
  .artdateils-commentlist-all{
    float: left;
    line-height: 50px;
    text-align: center;
    font-size: 30px;
    width: 100%;
    color: #262626;
    margin-top: 20px;
  }



  .artdateils-siimage-li{
    width: 340px;
    height: 340px;
    float: left;
    position: relative;
  }
  .artdateils-siimage-li .artdateils-si-li-img{
    width: 330px;
    height: 330px;
    margin: 5px 5px;
  }
  .artdateils-si-image{
    width: 100%;
    float: left;
  }
  .artdateils-comment-button{
    line-height: 100px;
    float: left;
    width: 150px;
    text-align: center;
    color: #ff5959;
    font-size: 35px;
  }

  .artdateils-ddd-block{
    width: 100%;
    float: left;
    height: 100px;
    margin-top: 20px;
  }
  .artdateils-ddd{
    width: 500px;
    margin: auto;
    height: 100px;
  }
  .artdateils-wenzhang-centre{
    width: 680px;
    margin: auto;
  }
  .artdateils-wenzhang-centre img{
    width: 680px;
  }
</style>
