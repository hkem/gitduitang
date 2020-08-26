<template>
  <div class="homepage">
    <div class="homepage-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="homepage-top-block">
        <van-nav-bar
          title="主页"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        >
        </van-nav-bar>
      </div>
      <div class="homepage-block">
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>
        <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
      <div class="homepage-cover-block" ref="container" v-on:click="clickhomepage">
        <div class="homepage-cover-image" v-bind:style="cover_ba">
          <div class="homepage-data-block">
            <div class="homepage-data-li">
              <div class="homepage-img-li">
                <div class="homepage-img">
                  <van-image class="homepage-user-img" round fit="cover" :src="user.head" />
                </div>
              </div>
              <div class="homepage-title-li">{{user.nickname}}</div>
              <div class="homepage-fensi-ul">
                <div class="homepage-follow">{{user.follow}}关注</div>
                <div class="homepage-gege">
                  <div class="homepage-gege-li"></div>
                </div>
                <div class="homepage-fans">{{user.fans}}粉丝</div>
              </div>
            </div>
          </div>
          <div class="homepage-data-edit" v-if="edit">
            <div class="homepage-edit-li" @click="showShare = true">分享主页</div>
          </div>
        </div>
      </div>
      <!-- 推荐列表 -->
      <div class="homepage-list-block">

         <div class="homepage-list">

           <div class="homepage-list-li" v-for="item in list" :key="item.id">
             <div class="homepage-user-block">
               <div class="user-block-img">
                 <van-image class="homepage-user-li-img" round fit="cover" :src="item.head" />
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
              <div class="homepage-centre-block" @click="clickcentre(item.id)">
               <div class="homepage-centre">
                 {{item.name}}
               </div>
               <!-- 丹徒 -->
               <div class="homepage-centre-img" v-if="item.type == 2">
                 <van-image class="homepage-centre-li-img" fit="cover" :src="item.image" />
               </div>

               <!-- 多图 -->
               <div class="homepage-all-img" v-if="item.type == 1 && item.imgcount != 4">
                 <div class="homepage-all-li" v-for="(value, index) in item.listimage">
                   <van-image class="all-li-img" fit="cover" :src="value" />
                   <div class="homepage-all-mumber" v-if="item.imgjiu == 1 && index == 8">
                     <div class="all-mumber-centre">
                       <div class="all-mumber-img"><img src="../../assets/img/munmber.png"></div>
                       <div class="all-mumber-shu">{{item.imgjiucount}}</div>
                     </div>
                   </div>
                 </div>
               </div>

               <!--四张图片 -->
               <div class="homepage-si-img" v-if="item.type == 1 && item.imgcount == 4">
                 <div class="homepage-si-li" v-for="(value, name) in item.listimage">
                   <van-image class="si-all-li-img" fit="cover" :src="value" />
                 </div>
               </div>
             </div>

             <div class="homepage-operation-block">
               <div class="homepage-operation-li">
                 <div class="operation-li-abulous">
                   <div class="operation-li-img">
                     <img src="../../assets/img/fabulous.png">
                   </div>
                   <div class="operation-li-title">
                     {{item.fabulous}}
                   </div>
                 </div>
               </div>
               <div class="homepage-operation-li">
                 <div class="operation-li-comment">
                   <div class="operation-li-img">
                     <img src="../../assets/img/comment.png">
                   </div>
                   <div class="operation-li-title">
                     {{item.comment}}
                   </div>
                 </div>
               </div>
               <div class="homepage-operation-li">
                 <div class="operation-li-more">
                   <div class="operation-li-img">
                     <img src="../../assets/img/more.png">
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
      <van-share-sheet
        v-model="showShare"
        title="立即分享给好友"
        :options="options"
        @select="onSelect"
      />
    </div>
  </div>
</template>

<script>
  import Errora from '../../components/Errora.vue';
  import Loading from '../../components/Loading.vue';
  export default {
    name: 'Homepage',
    data () {
      return {
        cover_ba:{
          backgroundImage: "url(" + require("../../assets/img/cx.png") + ")",
          backgroundRepeat: "no-repeat",
          backgroundSize: "100% 100%",
        },
        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        user:{},
        id:0,
        list:[],
        page:1,
        allpage:0,
        loading: false,
        finished: false,
        error:false,
        showShare:false,
        options: [
        [
          { name: '复制链接', icon: 'link' },
        ],
      ],
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.id = this.$route.query.id;
      this.getdata("");
      this.getlist(1);
      console.log(this.$route.path);
    },
    methods:{
      onSelect:function(option){
        console.log(option.name);
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
      clickhomepage:function(event){
        this.edit = false;
      },
      onRefresh:function(){
        this.getdata("refresh");
        this.getlist(1);
      },
      onLoad:function(){
        if(this.page <= this.allpage){
          var p = this.page + 1;
          this.getlist(p);
        }
      },
      getdata(type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/userdate",this.$qs.stringify({id:that.id}))
             .then(response=>{
                //成功
                var data = response.data;
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
                      this.isCentre = true;
                      this.request_Loading = false;
                      this.user = resdata.user;
                      this.cover_ba.backgroundImage = "url(" + resdata.user.cover + ")";
                  }else{
                    this.isError = true;
                    this.request_Loading = false;
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
      getlist(p){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/getuser_article",this.$qs.stringify({id:that.id,token:token,p:p}))
             .then(response=>{
                //成功
                var data = response.data;
                console.log("成功");
                console.log(8888);
                if(response.status != 200){
                  this.$toast("商品获取失败");
                }else{
                  if(data.code == 1){
                    var resdata = data.data;
                    if(p == 1){
                      this.list = resdata.list;
                      this.allpage = resdata.listcount;
                    }else{
                      var jiulist = this.list;
                      this.list = jiulist.concat(resdata.list);
                    }

                    if(this.allpage == p){
                      this.finished = true;
                      console.log("哈哈我等哈我的");
                    }
                    this.finished = true;
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
  .homepage{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .homepage-center{
    float: left;
    width: 100%;
  }
  .homepage-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
    background-color: #FFFFFF;
    z-index: 1000;
    position: fixed;
    top: 0px;
  }
  .homepage-block{
    float: left;
    width: 100%;
  }
  .homepage-cover-block{
    float: left;
    width: 100%;
    height: 600px;
  }
  .homepage-cover-image{
    width: 100%;
    height: 600px;
    position: relative;
  }
  .homepage-data-block{
    position: absolute;
    margin-top: 200px;
    width: 100%;
  }
  .homepage-data-li{
    width: 680px;
    margin: auto;
  }
  .homepage-img-li{
    float: left;
    width: 100%;
    height: 150px;
  }
  .homepage-img{
    width: 150px;
    height: 150px;
    margin-left: 265px;
    float: left;
    border-radius: 10000px;
    border: 2px solid #FFFFFF;
  }
  .homepage-img .homepage-user-img{
    width: 150px;
    height: 150px;
    border-radius: 10000px;
  }
  .homepage-title-li{

    width: 680px;
    margin: auto;
    font-size: 35px;
    color: #FFFFFF;
    line-height: 100px;
    text-align: center;
  }
  .homepage-fensi-ul{
    float: left;
    width: 680px;
    margin: auto;
  }
  .homepage-follow{
    width: 300px;
    text-align: right;
    color: #FFFFFF;
    font-size: 25px;
    float: left;
  }
  .homepage-gege{
    float: left;
    width: 80px;
    height: 50px;
  }
  .homepage-gege-li{
    width: 3px;
    background-color: #FFFFFF;
    margin: auto;
    height: 20px;
    margin-top: 10px;
  }
  .homepage-fans{
    width: 300px;
    text-align: left;
    color: #FFFFFF;
    font-size: 25px;
    float: left;
  }
  .homepage-data-edit{
    position: absolute;
    top: 95px;
    right: 0px;
    width: 300px;
    background-color: #FFFFFF;
  }
  .homepage-data-edit a{
    color: #FFFFFF;
  }
  .homepage-edit-li{
    width: 300px;
    line-height: 80px;
    float: left;
    font-size: 35px;
    text-align: center;
    color: #000000;
  }




  /* 列表 */
  .homepage-list-block{
    width: 100%;
    float: left;
  }
  .homepage-list{
    width: 680px;
    margin: auto;
  }
  .homepage-list-li{
    width: 100%;
    float: left;
  }
  .homepage-user-block{
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
  .user-block-img .homepage-user-li-img{
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
  .homepage-centre-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .homepage-centre{
    width: 100%;
    float: left;
    font-size: 35px;
    color: #444444;
  }
  .homepage-centre-img{
    margin-top: 10px;
    width: 100%;
    float: left;
  }
  .homepage-centre-img .homepage-centre-li-img{
    width: 680px;
    height: 300px;
  }
  .homepage-operation-block{
    width: 100%;
    height: 40px;
    float: left;
    margin-top: 15px;
  }
  .homepage-operation-li{
    width: 33.33333333333%;
    float: left;
    height: 40px;
  }
  .operation-li-abulous{
    float: left;
    height: 50px;
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
  .homepage-all-img{
    width: 100%;
    float: left;
  }
  .homepage-all-li{
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
  .homepage-all-mumber{
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
  .homepage-si-img{
    width: 100%;
    float: left;
  }
  .homepage-si-li{
    width: 340px;
    height: 340px;
    float: left;
    position: relative;
  }
  .homepage-si-li .si-all-li-img{
    width: 330px;
    height: 330px;
    margin: 5px 5px;
  }
</style>
