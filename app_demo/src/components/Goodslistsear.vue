<template>
  <div class="goodslist">
    <div class="goodslist-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="goodslist-top-block">
        <div class="goodslist-top">
          <div class="goodslist-top-left" @click="onClickLeft">
              <img src="../assets/img/left.png">
          </div>
          <div class="goodslist-top-right">
              <van-search
                v-model="val"
                show-action
                label=""
                placeholder="请输入搜索关键词"
                @search="onsearch"
              >
                <template #action>
                  <div @click="onsearch">搜索</div>
                </template>
              </van-search>
          </div>
        </div>
      </div>
      <div class="goodslist-block">
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>

      <div v-if="isCentre">

      <div class="goodslist-goods-list">
        <div class="goodslist-goods">
          <van-pull-refresh  v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
          <div class="goodslist-goods-ul">
            <div class="goodslist-goods-li" v-for="item in list" :key="item.id" @click="goodsdateils(item.id)">
              <div class="goodslist-goods-liimg">
                <van-image class="goodslist-all-li-img" fit="cover" :src="item.image" />
              </div>
              <div class="goodslist-goods-lidatte">
                <div class="goodslist-goods-lititle"><h3 class="van-multi-ellipsis--l2">{{item.name}}</h3></div>
                <div class="goodslist-goods-lijuan">
                  <div class="goodslist-goods-shop">{{item.shop}}</div>
                  <div class="goodslist-goods-discount">领{{item.discount}}元卷</div>
                </div>
                <div class="goodslist-goods-lipeice">到手<span>￥{{item.price}}</span></div>
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
    name: 'Goodslistsear',
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
        val:0
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.val = this.$route.query.val;
      this.getlist(1),"";
    },
    methods:{
      onsearch:function(){
        this.getlist(1);
      },
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
      onLoad:function(){
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
             .post(this.$myStore.url+"/api/index/goodslist",this.$qs.stringify({val:that.val,token:token,p:p,type:3,id:-1}))
             .then(response=>{
                //成功
                var data = response.data;
                console.log("成功");

                if(response.status != 200){
                  this.$toast("商品获取失败");
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
                      console.log(999);
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
  .goodslist{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .goodslist-block{
    width: 100%;
    float: left;
    margin-top: 95px;
  }

  .goodslist-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .goodslist-top-block{
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
    position: fixed;
    top:0px;
    background-color: #FFFFFF;
    z-index: 10000;
  }

  .goodslist-goods-list{
    float: left;
    width: 100%;
  }
  .goodslist-goods{
    margin: auto;
    width: 680px;
  }

  .goodslist-goods-ul{
    float: left;
    width: 100%;
  }
  .goodslist-goods-li{
    float: left;
    width: 100%;
    height: 200px;
    margin-top: 20px;
  }
  .goodslist-goods-liimg{
    width: 200px;
    height: 200px;
    float: left;
  }
  .goodslist-goods-liimg .goodslist-all-li-img{
    width: 200px;
    height: 200px;
  }
  .goodslist-goods-lidatte{
    float: left;
    margin-left: 20px;
    width: 460px;
    height: 200px;
  }
  .goodslist-goods-lititle{
    float: left;
    width: 100%;
  }
  .goodslist-goods-lititle h3{
    margin: 0px;
    font-size: 30px;
    color: #393939;
  }
  .goodslist-goods-lijuan{
    float: left;
    width: 100%;
    height: 50px;
    margin-top: 15px;
  }
  .goodslist-goods-shop{
    float: left;
    line-height: 35px;
    font-size: 25px;
    color: #bf868d;
    border: 2px solid #e88b95;
    padding: 0px 10px;
    border-radius: 10px;
  }
  .goodslist-goods-discount{
    float: left;
    line-height: 38px;
    font-size: 25px;
    color: #FFFFFF;
    background-color: #fb8375;
    padding: 0px 10px;
    border-radius: 10px;
    margin-left: 20px;
  }
  .goodslist-goods-lipeice{
    float: left;
    line-height: 50px;
    font-size: 25px;
    color: #ff4a66;
  }
  .goodslist-goods-lipeice span{
    font-size: 35px;
  }

  .goodslist-top{
    width: 100%;
    margin: auto;
  }
  .goodslist-top-left{
    float: left;
    width: 75px;
    height: 95px;
  }
  .goodslist-top-left img{
    width: 35px;
    height: 35px;
    margin: 35px 20px 0px 30px;
  }
  .goodslist-top-right{
    width: 650px;
    float:left ;
  }
</style>
