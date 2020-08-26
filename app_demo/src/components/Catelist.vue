<template>
  <div class="catelist">
    <div class="catelist-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="catelist-top-block">
        <van-nav-bar
          v-bind:title=valtitle
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        >
        </van-nav-bar>
      </div>
      <div class="catelist-block">
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>

        <div v-if="isCentre">
          <van-pull-refresh  v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
      <div class="catelist-cate-block">
          <div class="catelist-cate-ul">
            <div class="catelist-cate-li" v-for="itme in catelist" :key="itme.id" @click="listclick(itme.id,itme.name)">
              <div class="catelist-cate-cemtre">
                <div class="catelist-cate-image">
                  <img :src="itme.image">
                </div>
                <div class="catelist-cate-title">{{itme.name}}</div>
              </div>
            </div>
          </div>
      </div>
      <div class="catelist-goods-list">
        <div class="catelist-goods">
          <div class="catelist-goods-title">商品列表</div>
          <div class="catelist-goods-ul">
            <div class="catelist-goods-li" v-for="item in list" :key="item.id" @click="goodsdateils(item.id)">
              <div class="catelist-goods-liimg">
                <van-image class="catelist-all-li-img" fit="cover" :src="item.image" />
              </div>
              <div class="catelist-goods-lidatte">
                <div class="catelist-goods-lititle"><h3 class="van-multi-ellipsis--l2">{{item.name}}</h3></div>
                <div class="catelist-goods-lijuan">
                  <div class="catelist-goods-shop">{{item.shop}}</div>
                  <div class="catelist-goods-discount">领{{item.discount}}元卷</div>
                </div>
                <div class="catelist-goods-lipeice">到手<span>￥{{item.price}}</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </van-pull-refresh>
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
    name: 'Catelist',
    data () {
      return {

        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        loading: false,
        finished: false,
        error:false,
        page:1,
        allpage:0,
        list:[],
        catelist:[],
        valtitle:"列表"
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.id = this.$route.query.id;
      this.valtitle = this.$route.query.valtitle;
      this.getdata("");
      this.getlist(1);
    },
    methods:{
      goodsdateils:function(cen){
        this.$router.push({ name:'Goodsdateils',query: { id:cen}});
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
      listclick:function(cen,val){
        this.$router.push({ name:'Goodslist',query: { id:cen,valtitle:val}});
      },
      onLoad:function(){
        if(this.page <= this.allpage){
          var p = this.page + 1;
          this.getlist(p);
        }
      },
      onRefresh:function(){
        this.getdata("refresh");
        this.getlist(1);
      },
      getdata(type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/goodscate",this.$qs.stringify({id:that.id,token:token}))
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
                      this.isCentre = true;
                      this.request_Loading = false;
                      this.catelist = resdata.shop;

                  }else{
                    this.isError = true;
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
             .post(this.$myStore.url+"/api/index/goodslist",this.$qs.stringify({id:that.id,token:token,p:p,type:1,val:"00"}))
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
                      this.allpage = resdata.pagecount;
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
  .catelist{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .catelist-block{
    float: left;
    width: 100%;
    margin-top: 95px;
  }
  .catelist-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .catelist-top-block{
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
    position: fixed;
    top:0px;
    background-color: #FFFFFF;
    z-index: 10000;
  }
  .catelist-cate-block{
    width: 100%;
    float: left;
    margin-top: 20px;
  }
  .catelist-cate-ul{
    margin: auto;
    width: 100%;
  }
  .catelist-cate-li{
    width: 20%;
    float: left;
  }
  .catelist-cate-cemtre{
    width: 120px;
    margin: 0px 18px;
  }
  .catelist-cate-image{
    width: 120px;
    height: 120px;
    border-radius: 1000px;
    float: left;
  }
  .catelist-cate-image img{
    width: 120px;
    height: 120px;
    border-radius: 1000px;
  }
  .catelist-cate-title{
    float: left;
    width: 100%;
    line-height: 50px;
    font-size: 30px;
    color: #414141;
    text-align: center;
  }
  .catelist-goods-list{
    float: left;
    width: 100%;
  }
  .catelist-goods{
    margin: auto;
    width: 680px;
  }
  .catelist-goods-title{
    float: left;
    line-height: 80px;
    font-size: 40px;
    color: #3d3d3d;
    width: 100%;
  }
  .catelist-goods-ul{
    float: left;
    width: 100%;
  }
  .catelist-goods-li{
    float: left;
    width: 100%;
    height: 200px;
    margin-top: 10px;
  }
  .catelist-goods-liimg{
    width: 200px;
    height: 200px;
    float: left;
  }
  .catelist-goods-liimg .catelist-all-li-img{
    width: 200px;
    height: 200px;
  }
  .catelist-goods-lidatte{
    float: left;
    margin-left: 20px;
    width: 460px;
    height: 200px;
  }
  .catelist-goods-lititle{
    float: left;
    width: 100%;
  }
  .catelist-goods-lititle h3{
    font-size: 30px;
    color: #393939;
    margin: 0px;
  }
  .catelist-goods-lijuan{
    float: left;
    width: 100%;
    height: 50px;
    margin-top: 15px;
  }
  .catelist-goods-shop{
    float: left;
    line-height: 35px;
    font-size: 25px;
    color: #bf868d;
    border: 2px solid #e88b95;
    padding: 0px 10px;
    border-radius: 10px;
  }
  .catelist-goods-discount{
    float: left;
    line-height: 40px;
    font-size: 25px;
    color: #FFFFFF;
    background-color: #fb8375;
    padding: 0px 10px;
    border-radius: 10px;
    margin-left: 20px;
  }
  .catelist-goods-lipeice{
    float: left;
    line-height: 50px;
    font-size: 25px;
    color: #ff4a66;
  }
  .catelist-goods-lipeice span{
    font-size: 35px;
  }
</style>
