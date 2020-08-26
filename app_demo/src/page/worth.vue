<template>
  <div class="worth">
    <div class="worth-center">
      <!-- 搜索 -->
      <div class="worth-search-block" v-bind:style="worth_block_ba">
        <div class="worth-search">
          <div class="search-add-to" @click="goodsshuom()">
            <img src="../assets/img/doubt.png"/>
          </div>
          <div class="worth-search-background" @click="clisear()">
            <img src="../assets/img/search.png"/>
            <div class="search-title"><p>值呀，给你看最好的<q>东西</q></p></div>
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
      <div class="worth-ll-block">
       <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
        <!-- 分类 -->
         <div class="worth-classify-block">
          <div class="worth-classify">
            <div class="worth-classify-left">
              <div class="worth-classify-centre">
                <div class="worth-classify-selected">
                  <div class="worth-classify-img">
                    <img src="../assets/img/discount.png">
                  </div>
                  <div class="worth-selected-title"><p>精选</p></div>
                </div>
              </div>
              <div class="worth-selected-select"></div>
            </div>
             <div class="worth-classify-right">
               <div class="worth-classify-li" v-for="item in catelist" :key="item.id" @click="cateclick(item.id,item.name)">{{item.name}}
                <div class="worth-classify-select" v-if="item.isselect"></div>
               </div>
             </div>
          </div>
         </div>
        <!-- 轮播图 -->
        <div class="worth-swipe-block">
          <div class="worth-swipe-bb">
              <div class="worth-swipe">
                <van-swipe class="worth-van-swipe" :autoplay="3000" indicator-color="white">
                  <van-swipe-item v-for="item in bannerlist" :key="item.id">
                    <img :src="item.image">
                  </van-swipe-item>
                </van-swipe>
              </div>
          </div>
          <!-- 卡片 -->
          <div class="worth-card-block">
            <div class="worth-card">
              <div class="worth-card-li" @click="allwant('1')">
                <div class="worth-card-centre">
                  <div class="worth-card-img">
                    <img src="../assets/img/choice_find.png">
                  </div>
                  <div class="worth-card-right" >
                    <div class="card-right-title"><h1>生活种草</h1></div>
                    <div class="card-right-futitle"><p>天天好生活</p></div>
                  </div>
                </div>
              </div>
              <div class="worth-card-li2" @click="allwant('2')">
                <div class="worth-card-centre">
                  <div class="worth-card-img">
                    <img src="../assets/img/choice_find.png">
                  </div>
                  <div class="worth-card-right">
                    <div class="card-right-title"><h1>家居种草</h1></div>
                    <div class="card-right-futitle"><p>奢华家居</p></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- 种草 -->
        <div class="worth-want-block">
          <div class="worth-want">
            <div class="worth-want-top">
              <div class="want-top-left"><h1>值得虫草</h1></div>
              <div class="want-top-right">

                <div class="want-top-img">
                  <img src="../assets/img/right.png">
                </div>
                <div class="want-top-duo" @click="allwant('3')"><p>查看更多</p></div>
                </div>
            </div>
            <div class="worth-want-centre">
              <van-swipe class="want-swipe" :show-indicators="false" :autoplay="5000" indicator-color="white">
                <van-swipe-item v-for="item in grasslist" :key="item.id" @click="grassxq(item.id)">
                  <div class="worth-want-li">
                    <div class="want-li-img">
                      <van-image class="want-all-li-img" fit="cover" :src="item.image" />
                    </div>
                    <div class="want-li-right">
                      <div class="want-li-title "><h6 class="van-multi-ellipsis--l2">{{item.name}}</h6></div>
                      <div class="want-li-bottom">
                        <div class="want-bottom-li">
                          <div class="bottom-li-img">
                            <img src="../assets/img/browse.png">
                          </div>
                          <div class="bottom-li-count">
                            <p>{{item.readq}}</p>
                          </div>
                        </div>
                        <div class="want-bottom-li">
                          <div class="bottom-li-img">
                            <img src="../assets/img/collection.png">
                          </div>
                          <div class="bottom-li-count">
                            <p>{{item.collection}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </van-swipe-item>
              </van-swipe>
            </div>
          </div>
        </div>
        <div class="worth-interval1"></div>
        <!-- 商品 -->
        <div class="worth-goods-block">
          <div class="worth-goods">
            <div class="worth-goods-top">
              <div class="goods-top-title"><h1>值呀精选</h1></div>
              <div class="goods-top-fu"><p>这些优惠值得看！每天更新</p></div>
            </div>
            <div class="worth-goods-centre">
              <div class="goods-centre-li" v-for="item in goodslist" @click="centreclick(item.id)">
                <div class="goods-centre-img">
                  <van-image class="goods-all-li-img" fit="cover" :src="item.image" />
                </div>
                <div class="goods-centre-right">
                  <div class="goods-right-title"><h3 class="van-multi-ellipsis--l2">{{item.name}}</h3></div>
                  <div class="goods-right-entre">￥{{item.price}}</div>
                  <div class="goods-right-bottom">
                    <div class="goods-right-volume">领{{item.discount}}元卷</div>
                    <div class="goods-right-source">{{item.shop}}</div>
                    <div class="goods-right-ge"></div>
                    <div class="goods-right-price"><s>￥{{item.original_price}}</s></div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
    <div class="worth-bottum"></div>
        </van-pull-refresh>
    </div>
    </div>
    <div class="worth-nav">
      <BottomNavigation></BottomNavigation>
    </div>
  </div>
</template>

<script>
  import BottomNavigation from '../components/BottomNavigation.vue'
  import Errora from '../components/Errora.vue';
  import Loading from '../components/Loading.vue';
export default {
  name: 'Worth',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      worth_block_ba:{
        backgroundImage: "url(" + require("../assets/img/cx.png") + ")",
        backgroundRepeat: "no-repeat",
        backgroundSize: "100% 100%",
      },
      isLoading: false,
      isCentre:false,
      isError:false,
      request_Loading:true,
      bannerlist:[],
      grasslist:[],
      catelist:[],
      page:1,
      allpage:0,
      goodslist:[]
    }
  },
  components: {
      BottomNavigation,
      Errora,
      Loading
  },
  created () {
    this.fetchData("");

  },
  methods:{
    grassxq:function(e){
      this.$router.push({ name:'Grassdatelis',query: { id:e}});
    },
    goodsshuom:function(){
        this.$router.push({ name:'Goodsexplain'});
    },
    clisear:function(){
        this.$router.push({ name:'Searchgoods'});
    },
    cateclick:function(e,val){
      this.$router.push({ name:'Catelist',query: { id:e,valtitle:val}});
    },
    get_list:function(){
      var token = this.$store.state.token;
      this.$axios
              .post(this.$myStore.url+"/api/index/get_goods",this.$qs.stringify({
                 token: token,
                 p:this.page
               }))
              .then(response=>{
                var data = response.data;

                 //成功
                 if(response.status != 200){
                   this.$toast.fail('获取失败');
                 }else{
                   if(data.code == 1){

                      var resdata = data.data;
                      this.goodslist = resdata.list;
                      this.allpage = resdata.listcount;
                      this.page = 1;
                   }
                    if(data.code == -1){
                      this.$toast.fail('退出失败');
                    }
                 }

              })
              .catch(error=>{
                if(error.response){
                  // this.isError = true;
                  // this.request_Loading = false;
                }
             });
    },
    onRefresh:function(){
      this.fetchData("refresh");
    },
    fetchData:function(type){
      var token = this.$store.state.token;
       this.$axios
               .post(this.$myStore.url+"/api/index/get_worth",this.$qs.stringify({
                  token: token,
                }))
               .then(response=>{
                 console.log(type);
                 var data = response.data;
                 this.request_Loading = false;
                 // this.isError = false;
                  //成功
                  if(type == "refresh"){
                    this.isLoading = false;
                    this.$toast('刷新成功');
                  }
                  if(response.status != 200){
                    this.$toast.fail('获取失败');
                  }else{
                    if(data.code == 1){
                       this.isCentre = true;
                       var resdata = data.data;
                       this.bannerlist = resdata.banner;
                       this.grasslist = resdata.list;
                       this.catelist = resdata.cate;
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
                   if(type == "refresh"){
                     this.isLoading = false;
                     this.$toast('刷新失败');
                   }
                 }
              });

          this.get_list();
    },
    centreclick:function(e){
      this.$router.push({ name:'Goodsdateils',query: { id:e}});
    },
    allwant:function(e){
      this.$router.push({ name:'Grasslist',query: { type:e}});
    }
  }
}
</script>

<style>

  .worth{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .worth-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    border-top: 1px gray solid;
    z-index: 10100;
  }
  .worth-center{
    width: 100%;
    float: left;
  }
.worth-search-block{
    width: 100%;
    height: 90px;
    float: left;
  }
  .worth-search-block-note{

  }
  .worth-search{
    width: 800px;
    float: right;
    height: 70px;
    margin-top: 10px;
  }
  .worth-search-background {
    background-color: #f5f5f5;
    border-radius: 5px;
    width: 450px;
    height: 70px;
    float: right;
    margin-right: 10px;
  }
  .worth-search-background img{
    width: 40px;
    height: 40px;
    float: left;
    margin-left: 20px;
    margin-top: 15px;
  }
  .worth-search-background .search-title{
    color: #989898;
    margin-left: 10px;
    float: left;
  }
  .worth-search-background .search-title p{
    color: #989898;
    margin-left: 10px;
    float: left;
    line-height: 70px;
    margin: 0px;
    color: #777777;
    font-size: 25px;
  }
  .search-add-to{
    float: right;
    height: 70px;
    width: 70px;
    margin-right: 30px;
  }
  .search-add-to img{
    height: 70px;
    width: 70px;
  }
/* 轮播 */
.worth-swipe-block{
  width: 100%;
  float: left;
  background-color: #f5f5f5;
}
.worth-swipe-bb{
  width: 100%;
  float: left;
  margin-top: 20px;
}
.worth-swipe{
  width: 680px;
  margin: auto;
  height: 200px;

}
.worth-van-swipe{
   height: 200px;
   width: 680px;
}
.worth-van-swipe img{
   height: 200px;
   width: 680px;
}
/* 卡片 */
.worth-card-block{
  width: 100%;
  float: left;
  margin-top: 20px;
  margin-bottom: 20px;
}
.worth-card{
  width: 680px;
  margin: auto;
}
.worth-card-li{
  width: 330px;
  float: left;
  height: 200px;
  background-color: #FFFFFF;
  border-radius: 10px;
}
.worth-card-li2{
  width: 330px;
  float: left;
  height: 200px;
  margin-left: 20px;
  background-color: #FFFFFF;
  border-radius: 10px;
}
.worth-card-centre{
  width: 250px;
  margin: auto;
  height: 100px;
  margin-top: 50px;
}
.worth-card-img{
  width: 100px;
  height: 100px;
  float: left;
}
.worth-card-img img{
  width: 100px;
  height: 100px;
}
.worth-card-right{
  float: left;
  width: 150px;
  height: 100px;
}
.card-right-title{
  float: left;
  width: 150px;
  height: 60px;
}
.card-right-title h1{
  margin: 0px;
  width: 150px;
  line-height: 60px;
  font-size: 30px;
  text-align: center;
  color: #444444;
}
.card-right-futitle{
  float: left;
  width: 150px;
  height: 60px;
}
.card-right-futitle p{
  margin: 0px;
  width: 150px;
  line-height: 30px;
  color: #777777;
  text-align: center;
}
.worth-want-block{
  float: left;
  width: 100%;
  margin-top: 20px;
  margin-bottom: 20px;
}
.worth-want{
  margin: auto;
  width: 680px;
}
.worth-want-top{
  width: 100%;
  height: 50px;
  float: left;
  margin-top: 20px;
}
.want-top-left{
  width: 200px;
  line-height: 50px;
  float: left;
}
.want-top-left h1{
  font-size: 30px;
  margin: 0px;
}
.want-top-right{
  float: right;
  width: 300px;
  height: 50px;
}
.want-top-duo{
  float: right;
}
.want-top-duo p{
  text-align: right;
  line-height: 50px;
  font-size: 25px;
  margin: 0px;
}
.want-top-img{
  width: 50px;
  height: 50px;
  float: right;
}
.want-top-img img{
  width: 50px;
  height: 50px;
}
.worth-want-centre{
  width: 100%;
  float: left;
  height: 200px;
  margin-top: 20px;
  margin-bottom: 20px;

}
.want-swipe {
    height: 200px;
width: 680px;
  }
  .worth-want-li{
    width: 680px;
    height: 200px;
  }
  .want-li-img{
    float: left;
    height: 200px;
    width: 250px;
  }
  .want-li-img .want-all-li-img{
    height: 200px;
    width: 250px;
  }
  .want-li-right{
    width: 400px;
    height: 200px;
    margin-left: 20px;
    float: left;
  }
  .want-li-title{
    width: 400px;
    float: left;
    height: 120px;
    font-size: 40px;
  }
  .want-li-title h6{
    margin: 0px;
    font-size: 30px;
  }
  .want-li-bottom{
    height: 50px;
    float: left;
    width: 400px;
    margin-top: 20px;
  }
  .want-bottom-li{
    height: 30px;
    margin-top: 10px;
    float: left;
  }
  .bottom-li-img{
    width: 30px;
    height: 30px;
    float: left;
  }
  .bottom-li-img img{
    width: 30px;
    height: 30px;
  }
.bottom-li-count{
  width: 100px;
  height: 30px;
  float: left;
}
.bottom-li-count p{
  line-height: 30px;
  color: #777777;
  margin: 0px;
}
.worth-interval1{
  height: 20px;
  width: 100%;
  float: left;
  background-color: #f5f5f5;
}
/* 商品列表 */
.worth-goods-block{
  width: 100%;
  float: left;

}
.worth-goods{
  width: 680px;
  margin: auto;
}
.worth-goods-top{
  width: 100%;
}
.goods-top-title{
  float: left;
  width: 100%;
  margin-top: 10px;
}
.goods-top-title h1{
  line-height: 60px;
  font-size: 35px;
  margin: 0px;
}
.goods-top-fu{
  float: left;
  width: 100%;
}
.goods-top-fu p{
  line-height: 50px;
  margin: 0px;
  font-size: 25px;
}
.worth-goods-centre{
  float: left;
  width: 100%;
}
.goods-centre-li{
  width: 100%;
  float: left;
  margin-top: 20px;
}
.goods-centre-img{
  width: 250px;
  height: 200px;
  float: left;
}
.goods-centre-img .goods-all-li-img{
  width: 250px;
  height: 200px;
}
.goods-centre-right{
  float: left;
  margin-left: 20px;
  width: 410px;
  height: 200px;
}
.goods-right-title{
  float: left;
  width: 410px;
  height: 100px;
}
.goods-right-title h3{
  margin: 0px;
  font-size: 30px;
}
.goods-right-entre{
  line-height: 30px;
  float: left;
  color: #ff7f7e;
  font-size: 30px;
  width: 100%;
}
.goods-right-bottom{
  float: left;
  height: 50px;
  width: 100%;
  margin-top: 10px;
}
.goods-right-volume{
  line-height: 40px;
  max-width: 200px;
  text-align: center;
  background-color: #ff5959;
  float: left;
  border-radius: 5px;
  color: #FFFFFF;
  font-size: 25px;
  padding: 0px 5px;
}
.goods-right-source{
  float: left;
  line-height: 40px;
  width: 100px;
  text-align: center;
  color: #777777;
  font-size: 25px;
}
.goods-right-ge{
  float: left;
  width: 3px;
  height: 30px;
  margin-top: 5px;
  background-color: #444444;
}
.goods-right-price{
  float: left;
  line-height: 40px;
  width: 100px;
  text-align: center;
  color: #777777;
  font-size: 25px;
}
.worth-bottum{
  float: left;
  height: 150px;
  width: 100%;
}
/* 分类 */
.worth-classify-block{
  width: 100%;
  height: 100px;
}
.worth-classify{
  width: 100%;
  height: 100px;
}
.worth-classify-left{
  float: left;
  height: 100px;
  width: 180px;
  position: relative;
}
.worth-classify-centre{
  width: 180px;
  height: 80px;
  border-right: 2px solid #ebebeb;
  margin-top: 10px;
}
.worth-classify-selected{
  width: 150px;
  height: 50px;
  margin: auto;
  padding-top: 15px;

}
.worth-classify-img{
  width: 50px;
  height: 50px;
  float: left;

}
.worth-classify-img img{
  width: 50px;
  height: 50px;
}
.worth-selected-title{
  float: left;
  width: 100px;

}
.worth-selected-title p{
  line-height: 50px;
  font-size: 35px;
  margin: 0rem;
  color: #ff5959;
}
.worth-selected-select{
  position: absolute;
  width: 50px;
  height: 5px;
  background-color: #ff5959;
  bottom: 5px;
  margin-left: 65px;
}
.worth-classify-right{
  width: 570px;
  height: 100px;
  float: left;
  white-space: nowrap;
  overflow-y:auto;
}
.worth-classify-li{
  line-height: 100px;
  font-size: 30px;
  text-align: center;
  width: 120px;
  position:relative;
  display: inline-block;
}
.worth-classify-select{
  position: absolute;
  width: 50px;
  height: 5px;
  background-color: #ff5959;
  bottom: 5px;
  margin-left: 35px;
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
  .worth-ll-block{
    width: 100%;
    float: left;
  }
</style>
