<template>
  <div class="find">
    <!-- 加载中 -->

    <div class="find-center" >
      <!-- 搜索 -->
      <div class="find-search-block">
        <div class="find-search" @click="Searchcli()">
          <div class="find-search-background">
            <img src="../assets/img/search.png"/>
            <div class="search-title">大家都在搜<q>柯南</q></div>
          </div>
          <div class="find-search-add-to">
            <img src="../assets/img/Scanning_code.png"/>
          </div>
         </div>
      </div>
      <div class="recommend-error-block" v-if="isError">
        <Errora></Errora>
      </div>
      <!-- 错误 -->
      <div class="recommend-loading-block" v-if="request_Loading">
          <Loading></Loading>
      </div>
      <!-- 内容 -->
      <div class="find-classify-block" v-if="isCentre">
        <div class="find-classify">
          <div class="find-classify-left">
            <div class="classify-left-li" v-for="item in cate1" :key="item.id" @click="cate1click(item.id)">
              <div class="left-li-centre">
                <div class="left-li-on" v-if="item.isselect == 1"></div>
                <div class="left-li-title"><h1>{{item.text}}</h1></div>
              </div>
            </div>
          </div>
          <div class="find-classify-right">
            <div class="classify-right">
              <div class="find-swipe-block">
                <van-swipe class="find-swipe" :show-indicators="false" :autoplay="3000" indicator-color="white">
                  <van-swipe-item v-for="item in bannerlist" :src="item.image" :key="item.id" @click="clickbanner(item.url)">
                    <img :src="item.image">
                  </van-swipe-item>
                </van-swipe>
              </div>
              <div class="classify-list-block">
                 <div class="classify-list-li" v-for="c2 in cate2" :key="c2.id">
                    <div class="list-li-top">
                      <div class="li-top-left"></div>
                      <div class="li-top-right"><h1>{{c2.text}}</h1></div>
                    </div>
                    <div class="list-li-centre">
                      <div class="centre-list-li" v-for="c3 in c2.children" :key="c3.id" @click="artlist(c3.id,c3.text)">
                        <div class="li-centre-img">
                          <img :src="c3.image">
                        </div>
                        <div class="li-centre-title"><h2>{{c3.text}}</h2></div>
                      </div>
                    </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="find-bottum"></div>
    </div>
    <div class="find-nav">
      <BottomNavigation></BottomNavigation>
    </div>
  </div>
</template>

<script>
  import BottomNavigation from '../components/BottomNavigation.vue'
  import Errora from '../components/Errora.vue';
  import Loading from '../components/Loading.vue';
export default {
  name: 'Find',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      request_Loading:true,
      isLoading: false,
      isCentre:false,
      isError:false,
      bannerlist:[],
      allcate:[],
      cate1:[],
      cate2:[],
      cateid:0
    }
  },
  components: {
      BottomNavigation,
      Errora,
      Loading
  },
  created () {
    var token = this.$store.state.token;
     this.$axios
             .post(this.$myStore.url+"/api/index/find",this.$qs.stringify({
                token: token,
              }))
             .then(response=>{
               var data = response.data;
               this.request_Loading = false;
               this.isError = false;
                //成功
                if(response.status != 200){
                  this.$toast.fail('获取失败');
                }else{
                  if(data.code == 1){
                     this.isCentre = true;
                     var resdata = data.data;
                     this.bannerlist = resdata.banner;
                     resdata.list[0]["isselect"] = 1;
                     this.allcate = resdata.list;
                     this.cate1 = resdata.list;
                     this.cate2 = resdata.list[0]["children"];
                      console.log(resdata.list[0]["children"]);
                  }
                   if(data.code == -1){
                     this.$toast.fail('退出失败');
                   }
                }

             })
             .catch(error=>{
               if(error.response){
                 this.isError = true;
                 this.request_Loading = false;
               }


            });



  },
  methods:{
    Searchcli:function(){
      this.$router.push({ name:'Search'});
    },
    cate1click:function(e){
      var all = this.allcate;
      all[this.cateid]["isselect"] = 0;
      for(var i=0;i<all.length;i++){
        if(all[i]["id"] == e){
          all[i]["isselect"] = 1;
          this.cateid = i;
          this.cate2 = all[i]["children"];
        }
      }
    },
    artlist:function(cen,valtitle){
      this.$router.push({ name:'Articlelist',query: { id:cen,valtitle:valtitle}});

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

  /* 搜索 */
  .find{
    width: 100%;
    height: 100%;
    position: relative;

    float: left;
  }
  .find-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    border-top: 1px gray solid;
    z-index: 1000;
  }
  .find-center{
    padding-bottom: 100px;
    float: left;
    width: 100%;
  }
  .find-search-block{
    width: 100%;
    height: 80px;
    float: left;
    margin-top: 25px;
    border-bottom: #e2e2e2 2px solid;
  }
  .find-search{
    width: 680px;
    margin: auto;
    height: 50px;
  }
  .find-search-background {
    background-color: #f5f5f5;
    border-radius: 5px;
    width: 600px;
    height: 50px;
    float: left;
  }
  .find-search-background img{
    width: 30px;
    height: 30px;
    float: left;
    margin-left: 20px;
    margin-top: 10px;
  }
  .find-search-background .search-title{
    color: #989898;
    margin-left: 10px;
    float: left;
    line-height: 50px;
  }
  .find-search-add-to{
    float: left;
    height: 50px;
    width: 50px;
    margin-left: 20px;
  }
  .find-search-add-to img{
    height: 50px;
    width: 50px;
  }
  /* 分类 */
  .find-classify-block{
    width: 100%;
    float: left;

  }
  .find-classify{
    width: 100%;
    margin: auto;


  }
  .find-classify-left{
    float: left;
    width: 200px;

    background-color: #fafafa;
  }
  .find-classify-right{
    float: left;
    width: 542px;

  }
  .classify-left-li{
    float: left;
    width: 200px;
    height: 80px;
  }
  .left-li-centre{
    width: 200px;
    height: 50px;
    margin-top: 15px;
    float: left;
  }
  .left-li-on{
    width: 5px;
    background-color: #ff5959;
    height: 50px;
    float: left;
    margin-left: 10px;
  }
  .left-li-title{
    float: left;
    line-height: 50px;
    text-align: center;
    width: 180px;
  }
  .left-li-title h1{
    font-size: 30px;
    margin: 0px;
    color: #777777;
  }

  .classify-right{
    margin: auto;
    width: 480px;
  }
  .find-swipe-block{
    width: 470px;
    margin: auto;
    border-radius: 10px;
    float: left;
    margin-top: 20px;
  }
  .find-swipe-block .find-swipe{
    width: 470px;
    height: 150px;
    border-radius: 10px;
  }
  .find-swipe-block .find-swipe img{
    width: 470px;
    height: 150px;
  }
  .classify-list-block{
    margin: auto;
    width: 470px;
    float: left;
    margin-top: 20px;
  }
  .list-li-top{
    width: 100%;
    height: 50px;
    float: left;
  }
  .li-top-left{
    float: left;
    height: 50px;
    background-color: #ff5959;
    width: 5px;
  }
  .li-top-right{
    float: left;
    height: 50px;
    margin-left: 10px;
  }
  .li-top-right h1{
    color: #444444;
    font-size: 25px;
    line-height: 50px;
    margin: 0px;
  }
  .list-li-centre{
    width: 100%;
    float: left;
    margin-top: 20px;
  }
  .centre-list-li{
    width: 210px;
    height: 200px;
    float: left;
    margin-left: 20px;
  }
  .li-centre-img{
    width: 210px;
    height: 150px;
    float: left;
  }
  .li-centre-img img{
    width: 210px;
    height: 150px;
  }
  .li-centre-title{
    width: 210px;
    height: 50px;


  }
  .li-centre-title h2{
    line-height: 50px;
    text-align: center;
    color: #444444;
    font-size: 15px;
    margin: 0rem;
  }
  .find-bottum{
    float: left;
    height: 150px;
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
</style>
