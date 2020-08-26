<template>

  <div class="godateils" >
    <div class="godateils-centre">
      <div class="godateils-top-block">
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
      <div class="godateils-block">
        <div class="godateils-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="godateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>
        <div v-if="isCentre">
        <div class="godateils-goods-block">
          <div class="godateils-goods-img">
            <img :src="centre.image">
          </div>
          <div class="godateils-goods-xinxi-block">
            <div class="godateils-goods-xinxi">
              <div class="godateils-goods-title">{{centre.name}}</div>
              <div class="godateils-goods-nr">
                <div class="godateils-goods-price">￥{{centre.price}}</div>
                <div class="godateils-original-price">原价￥{{centre.original_price}}</div>
                <div class="godateils-goods-shop">{{centre.shop}}|{{centre.shop_name}}</div>
              </div>
              <div class="godateils-goods-discount">
                <div class="godateils-goods-left">优惠信息</div>
                <div class="godateils-goods-right">领{{centre.discount}}元卷</div>
              </div>
            </div>
          </div>
        </div>
        <div class="godateils-jian-block"></div>
        <div class="godateils-tuijian-block">
          <div class="godateils-tuijian">
            <div class="godateils-tuijian-title">推荐理由</div>
            <div class="godateils-tuijian-centre" v-html="centre.reason">

            </div>
          </div>
        </div>
        <div class="godateils-jian-block"></div>
        <div class="godateils-xiangguan-block">
          <div class="godateils-xiangguan">
            <div class="godateils-xiangguan-title">相关推荐</div>
            <div class="godateils-null-ul" v-if="tjlist.length == 0">
              <van-empty image="error" description="暂无内容" />
            </div>
            <div class="godateils-xiangguan-ul" v-if="tjlist.length != 0">
              <div class="godateils-xiangguan-li" v-for="item in tjlist" :key="item.id" @click="clickdate(item.id)">
                <div class="godateils-xiangguan-image">

                  <van-image class="godateils-all-li-img" fit="cover" :src="item.image" />
                </div>
                <div class="godateils-xiangguan-name"><p class="van-multi-ellipsis--l2">{{item.name}}</p></div>
                <div class="godateils-xiangguan-juan">
                  <div class="godateils-xiangguan-shop">{{item.shop}}</div>
                  <div class="godateils-xiangguan-discount">{{item.discount}}</div>
                </div>
                <div class="godateils-xiangguan-jiage">
                  <div class="godateils-xiangguan-price">到手<span>￥{{item.price}}</span></div>
                  <div class="godateils-xiangguan-people">{{item.sold}}人已买</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="godateils-nav">
          <div class="godateils-choice-block">
            <div class="godateils-choice-left">
              <div class="godateils-choice-juan">领劵立省{{centre.province}}元</div>
              <div class="godateils-choice-time">剩余时间{{centre.out_time}}</div>
            </div>
            <div class="godateils-choice-right" @click="clickurl()">
              领劵购买
            </div>
          </div>
        </div>
        </div>
        <div class="godateils-di-block"></div>
        </div>
    </div>

  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  export default {
    name: 'Goodsdateils',
    data () {
      return {
        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        id:0,
        centre:{},
        tjlist:[]
      }
    },
    components: {
        Errora,
        Loading
    },
    watch:{
      '$route' (to,from){
          this.$router.go(0);
        }
    },
    created() {
      this.id = this.$route.query.id;
      console.log(this.id);
       this.get_list();
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
      get_list(){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/goodsdateils",this.$qs.stringify({id:that.id,token:token}))
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
                      // that.isCentre = true;
                      this.request_Loading = false;
                      this.isCentre = true;
                      this.centre = resdata.goods;
                      this.tjlist = resdata.list;
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
      clickdate:function(e){

        this.$router.push({ name:'Goodsdateils',query: { id:e,aaa:8888}});
      },
      clickurl:function(){
         window.location.href = this.centre.url;
      }
    }
  }
</script>

<style>
  .godateils{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .godateils-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .godateils-block{
    margin-top: 95px;
    float: left;
    width: 100%;
  }
  .godateils-top-block{
    position: fixed;
    top:0px;
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .godateils-goods-block{
    float: left;
    width: 100%;

  }
  .godateils-goods-img{
    width: 750px;
    height: 750px;
    float: left;
  }
  .godateils-goods-img img{
    width: 750px;
    height: 750px;
  }
  .godateils-goods-xinxi-block{
    width: 100%;
    float: left;
  }
  .godateils-goods-xinxi{
    width: 680px;
    margin: auto;
  }
  .godateils-goods-title{
    width: 100%;
    line-height: 50px;
    color: #000000;
    float: left;
    font-size: 40px;
    margin-top: 10px;
  }
  .godateils-goods-nr{
    width: 100%;
    float: left;
    height: 100px;
  }
  .godateils-goods-price{
    font-size: 55px;
    color: #ff616f;
    float: left;
    line-height: 70px;
    margin-top: 30px;
  }
  .godateils-original-price{
    float: left;
    line-height: 30px;
    margin-top: 60px;
    color: #787878;
    font-size: 25px;
    margin-left: 10px;
  }
  .godateils-goods-shop{
    float: right;
    line-height: 30px;
    margin-top: 60px;
    color: #787878;
    font-size: 25px;
  }
  .godateils-goods-discount{
    border-radius: 10px;
    width: 100%;
    height: 80px;
    background-color: #fff3f5;
    float: left;
  }
  .godateils-goods-left{
    float: left;
    line-height: 80px;
    color: #1a1011;
    width: 150px;
    text-align: center;
  }
  .godateils-goods-right{
    float: right;
    width: 150px;
    height: 50px;
    margin-top: 15px;
    border-radius: 10px;
    background-color: #ff7d82;
    line-height: 50px;
    text-align: center;
    font-size: 25px;
    color: #FFFFFF;
    margin-right: 20px;
  }
  .godateils-jian-block{
    height: 20px;
    width: 100%;
    float: left;
    background-color: #f5f5f5;
    margin-top: 20px;
  }
  .godateils-tuijian-block{
    float: left;
    width: 100%;
  }
  .godateils-tuijian{
    width: 680px;
    margin: auto;
  }
  .godateils-tuijian-title{
    width: 100%;
    line-height: 80px;
    color: #393939;
    font-size: 40px;
    float: left;
  }
  .godateils-tuijian-centre{
    width: 100%;
    float: left;
  }
  .godateils-tuijian-centre img{
    width: 100%;
  }
  .godateils-xiangguan-block{
    float: left;
    width: 100%;
  }
  .godateils-xiangguan{
    width: 680px;
    margin: auto;
  }
  .godateils-xiangguan-title{
    width: 100%;
    line-height: 80px;
    color: #393939;
    font-size: 40px;
    float: left;
  }
  .godateils-xiangguan-ul{
    float: left;
  }
  .godateils-xiangguan-li{
    float: left;
    width: 320px;
    margin-left: 20px;
  }
  .godateils-xiangguan-image{
    width: 320px;
    height: 320px;
    float: left;
  }
  .godateils-xiangguan-image .godateils-all-li-img{
    width: 320px;
    height: 320px;
  }
  .godateils-xiangguan-name{
    float: left;
    width: 100%;

  }
  .godateils-xiangguan-name p{
    font-size: 25px;
    color: #464646;
  }
  .godateils-xiangguan-juan{
    width: 100%;
    height: 50px;
    float: left;
  }
  .godateils-xiangguan-shop{
    float: left;
    line-height: 30px;
    border: 1px solid #f95879;
    color: #e25172;
    border-radius: 10px;
    padding: 0 10px;
    font-size: 20px;
  }
  .godateils-xiangguan-discount{
    float: left;
    line-height: 30px;
    background-color: #f95452;
    color: #FFFFFF;
    border-radius: 10px;
    padding: 0 10px;
    font-size: 20px;
    margin-left: 20px;
  }
  .godateils-xiangguan-jiage{
    float: left;
    width: 100%;
    height: 50px;
  }
  .godateils-xiangguan-price{
    float: left;
    color: #fd5e62;
    font-size: 20px;
  }
  .godateils-xiangguan-price span{
    color: #fd5e62;
    font-size: 30px;
  }
  .godateils-xiangguan-people{
    float: right;
    font-size: 20px;
    color: #787878;
    line-height: 50px;
  }
  .godateils-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    background-color: #FFFFFF;
    border-top: 2px solid #e8e8e8;
  }
  .godateils-choice-block{
    width: 100%;
    height: 100px;
  }
  .godateils-choice-left{
    float: left;
    width: 50%;
  }
  .godateils-choice-right{
    float: left;
    width: 50%;
    line-height: 100px;
    text-align: center;
    color: #FFFFFF;
    font-size: 40px;
    background-color: #ff5959;
  }
  .godateils-choice-juan{
    float: left;
    line-height: 60px;
    color: #424242;
    text-align: center;
    width: 100%;
    font-size: 30px;
  }
  .godateils-choice-time{
    float: left;
    line-height: 30px;
    color: #424242;
    text-align: center;
    width: 100%;
    font-size: 20px;
  }
  .godateils-di-block{
    height: 100px;
    float: left;
    width: 100%;
  }
  .godateils-null-ul{
    width: 100%;
    float: left;
  }
</style>
