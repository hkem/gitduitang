<template>
  <div class="sssdatelis">
    <div class="sssdatelis-centre">
      <div class="sssdatelis-top-block">
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
      <div class="artdateils-block">
      <div class="allcomm-error-block" v-if="isError">
        <van-empty image="error" description="网络错误,请刷新" />
      </div>
      <!-- 请求中 -->
      <div class="artdateils-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>
      <div v-if="isCentre">
        <div class="sssdatelis-xq-block">
          <div class="sssdatelis-xq">
            <div class="sssdatelis-xq-title">{{centre.name}}</div>
            <div class="sssdatelis-xq-user">
              <div class="sssdatelis-xq-image">
                <van-image class="sssdatelis-userimg" round fit="cover" :src="centre.head" />
              </div>
              <div class="sssdatelis-xq-xinxi">
                <div class="sssdatelis-user-name">{{centre.nickname}}</div>
                <div class="sssdatelis-user-titme">{{centre.ctime}} ▪ 浏览{{centre.readq}}</div>
              </div>
            </div>
            <div class="sssdatelis-nr" >
              <div class="sssdatelis-nr2" v-html="centre.centre">

              </div>
            </div>
          </div>
        </div>
        </div>
        </div>
    </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  export default {
    name: 'Grassdatelis',
    data () {
      return {

        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        id:0,
        centre:{}

      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.id = this.$route.query.id;
      this.getlist();
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
      getlist:function(){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/grssdateils",this.$qs.stringify({id:that.id,token:token}))
             .then(response=>{
                //成功
                var data = response.data;
                console.log("成功");

                if(response.status != 200){
                  this.$toast("获取失败");
                  this.isError = true;
                  this.request_Loading = false;
                }else{
                  if(data.code == 1){
                    var resdata = data.data;
                      this.isLoading = false;
                      this.centre = resdata.centre;
                      this.isCentre = true;
                      this.request_Loading = false;
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
      }
    }
  }
</script>

<style>
  .sssdatelis{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .sssdatelis-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .artdateils-block{
    float: left;
    width: 100%;
     margin-top: 95px;
  }
  .sssdatelis-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
   position: fixed;
   top:0px;
  }
  .sssdatelis-xq-block{
    float: left;
    width: 100%;
  }
  .sssdatelis-xq{
    width: 680px;
    margin: auto;
  }
  .sssdatelis-xq-title{
    width: 100%;
    line-height: 50px;
    font-size: 50px;
    color: #343434;
    margin-top: 20px;
    float: left;
  }
  .sssdatelis-xq-user{
    width: 100%;
    float: left;
    height: 100px;
    margin-top: 20px;
  }
  .sssdatelis-xq-image{
    width: 100px;
    height: 100px;
    float: left;
    border-radius: 1000px;
  }
  .sssdatelis-xq-image .sssdatelis-userimg{
    width: 100px;
    height: 100px;
    border-radius: 1000px;
  }
  .sssdatelis-xq-xinxi{
    float: left;
    width: 380px;
    height: 100px;
    margin-left: 20px;
  }
  .sssdatelis-user-name{
    width: 100%;
    line-height: 50px;
    font-size: 30px;
    color: #3c3c3c;
  }
  .sssdatelis-user-titme{
    float: left;
    width: 100%;
    font-size: 25px;
    color: #9c9c9c;
  }
  .sssdatelis-xq-gz{
    float: right;
    width: 140px;
    line-height: 60px;
    color: #ff7c8c;
    border-radius: 1000px;
    border: 2px solid #f48192;
    font-size: 35px;
    text-align: center;
  }
  .sssdatelis-nr{
    width: 100%;
    float: left;
  }
  .sssdatelis-nr2 img{
    width: 100%;
  }
</style>
