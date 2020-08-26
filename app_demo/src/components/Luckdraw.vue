<template>
  <div class="luckdraw">
    <div class="luckdraw-centre">
      <div class="luckdraw-top-block">
        <van-nav-bar
          title="抽奖"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        >
        </van-nav-bar>
      </div>
      <div class="luckdraw-block">
        <div class="allcomm-error-block" v-if="isError">
          <van-empty image="error" description="网络错误,请刷新" />
        </div>
        <!-- 请求中 -->
        <div class="artdateils-loading-block" v-if="request_Loading">
          <Loading></Loading>
        </div>

          <div class="luckdraw-refresh-div" v-if="isCentre">

      <div class="luckdraw-cj-block">
        <div class="luckdraw-cj">
           <div class="luckdraw-cj-centre">
             <div class="luckdraw-cj-centre-nr">
                <div class="luckdraw-cj-title">
                  <div class="luckdraw-cj-titleleft">剩余{{user.sugar}}糖票</div>
                  <div class="luckdraw-cj-titleright">可抽{{cishu}}次</div>
                </div>
                <div class="luckdraw-cj-centrexx">
                  <div class="luckdraw-centrexx-top">
                    <div class="luckdraw-centrexx-topbg"></div>
                  </div>
                  <div class="luckdraw-centrexx-nr">
                    <div class="luckdraw-nr-centre">
                      <div class="luckdraw-nr-ul">
                      <div class="luckdraw-nr-li" v-for="(item,index) in list" v-if=" 0 <= index &&  index<= 3">
                        <div class="luckdraw-li-centre">
                          <div class="luckdraw-li-centreimg">
                            <img :src="item.image">
                          </div>
                          <div class="luckdraw-li-centretitle">{{item.name}}</div>
                          <div class="luckdraw-li-cenemng" v-if="item.meng == 1"></div>
                        </div>
                      </div>
                      <div class="luckdraw-nr-li">
                        <div class="luckdraw-li-centre-click" @click="ccclick()">
                          <div class="luckdraw-li-centreimg-click">
                            <img src="../assets/img/click.png">
                          </div>
                          <div class="luckdraw-li-centretitle-click">10糖票/次</div>
                        </div>
                      </div>
                      <div class="luckdraw-nr-li" v-for="(item,index) in list" v-if=" 4 <= index && index <= 9">
                        <div class="luckdraw-li-centre">
                          <div class="luckdraw-li-centreimg">
                            <img :src="item.image">
                          </div>
                          <div class="luckdraw-li-centretitle">{{item.name}}</div>
                          <div class="luckdraw-li-cenemng" v-if="item.meng == 1"></div>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                  <div class="luckdraw-centrexx-xia">
                    <div class="luckdraw-centrexx-xiabg"></div>
                  </div>
                </div>
                <div class="luckdraw-jil-jil">
                  <div class="luckdraw-jil-buttom" @click="myprice()">我的奖品</div>
                </div>
              </div>
           </div>
        </div>
        <van-popup v-model="zjshow" round>
          <div class="luckdraw-zjjj-block">
            <div class="luckdraw-zjjj-img">
              <img :src="zjshowlist.image">
            </div>
            <div class="luckdraw-zjjj-title">{{zjshowlist.name}}</div>
            <div class="luckdraw-zjjj-buttomdiv">
              <div class="luckdraw-zjjj-buttom" @click="zjshow = false">点击领取</div>
            </div>
          </div>
        </van-popup>
      </div>
      <div class="luckdraw-zhongjaa-list">
        <div class="luckdraw-zhongj">
          <div class="luckdraw-zhongj-top"></div>
          <div class="luckdraw-zhongj-centre"></div>
          <div class="luckdraw-zhongj-xia">
            <div class="luckdraw-list-centre">
              <div class="luckdraw-list-top">
                <img src="../assets/img/namelist.png">
              </div>
              <div class="luckdraw-list-uldiv">
              <div class="luckdraw-list-ul" :style="{ top }" @mouseenter="Stop()" @mouseleave="Up()">
                <div class="luckdraw-list-li" v-for="item in prizeList" :key="item.id">
                  <div class="luckdraw-listli-centre">
                    <div class="luckdraw-listli-user"><p>{{item.nickname}}</p></div>
                    <div class="luckdraw-listli-right">{{item.name}}</div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="luckdraw-explain-zj-block">
        <div class="luckdraw-explain" v-html="explain">

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
    name: 'Luckdraw',
    data () {
      return {
        edit: false,
        list:[],
        prizeList: [],
        activeIndex: 0,
        request_Loading:true,
        isLoading: false,
        isCentre:false,
        isError:false,
        explain:"",
        user:{},
        zjshow:false,
        listkey:0,
        zjshowlist:{},
        zcclick:false
      }
    },
    components: {
        Errora,
        Loading
    },
    computed: {
      top() {
          return -this.activeIndex * 10 + 'px';
      },
      cishu(){
        var munber = this.user.sugar;
        return (munber/10);
      }
    },
    created() {
      this.ScrollUp();
      this.getlist("");
      this.meglist();
      var that = this;
      setInterval(function(){
        that.meglist();
      },1000*60)
    },
    methods:{
      myprice:function(){
        this.$router.push({ name:'Prizerecord'});
      },
      ScrollUp() {
          // eslint-disable-next-line no-unused-vars
          this.intnum = setInterval(_ => {
              if (this.activeIndex < this.prizeList.length) {
                  this.activeIndex += 1;
              } else {
                  this.activeIndex = 0;
              }
          }, 1000);
      },

      Stop() {
          clearInterval(this.intnum);
      },
      Up() {
          this.ScrollUp();
      },
      onRefresh:function(){
        this.getlist("refresh");
      },
      meglist:function(){
        this.$axios
             .post(this.$myStore.url+"/api/index/getprizelist",this.$qs.stringify({}))
             .then(response=>{
                //成功
                var data = response.data;
                console.log("成功");
                if(response.status != 200){
                  this.$toast("抽奖获取失败");
                }else{
                  if(data.code == 1){
                    var resdata = data.data;
                    this.prizeList = resdata.list;
                }
              }
             })
             .catch(error=>{
                //失败
                if(error.response){
                  this.$toast("抽奖获取失败");
                }
         });
      },
      ccclick:function(){
        if(this.user.sugar < 10){
          this.$toast("糖票不足");
        }
        var that = this;
        var token = this.$store.state.token;
        if(token == ""){
          this.$router.push({ name:'Signin'});
          return;
        }
        if(this.zcclick){
          this.$toast("请稍后操作！");
          return;
        }
        this.zcclick = true;
        this.$axios
             .post(this.$myStore.url+"/api/index/smoke",this.$qs.stringify({token:token}))
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
                    this.getxunhuankey(resdata.prizeid);
                    this.showzhh(this.listkey);
                    this.zjshowlist = this.list[this.listkey];

                }else if(data.code == -2){
                  // this.$store.commit('settoken',"");
                  this.$router.push({ name:'Signin',query: { type:"out"}});
                }else{
                  this.$toast("抽奖失败");
                }
              }
             })
             .catch(error=>{

                //失败
                if(error.response){
                  this.$toast("抽奖失败");
                }
         });
      },
      getlist:function(type){
        var that = this;
        var token = this.$store.state.token;
        if(token == ""){
          this.$router.push({ name:'Signin'});
          return;
        }
        this.$axios
             .post(this.$myStore.url+"/api/index/getluckdraw",this.$qs.stringify({token:token}))
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
                    this.explain = resdata.setup.explain;
                    this.user = resdata.user;
                    this.list = resdata.prize;
                }else if(data.code == -2){
                  // this.$store.commit('settoken',"");
                  this.$router.push({ name:'Signin',query: { type:"out"}});
                }else{
                  that.isError = true;
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
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      xunhuan:function(key){
        var list = this.list;
        for(var i=0;i<list.length;i++){
          if(key == i){
            list[i]["meng"] = 0;
          }else if(key == -1){
            list[i]["meng"] = 0;
          }else{
            list[i]["meng"] = 1;
          }
        }
        this.list = list;
      },
      getxunhuankey:function(id){
        var list = this.list;
        for(var i=0;i<list.length;i++){
          if(list[i]["id"] == id){
            this.listkey = i;
          }
        }
      },
      showzhh:function(key){
        var mubne = 0;
        var danmuber = 0;
        var that = this;
        var quan = 24 + (key+1);
        var quantime = 100;
        var settt = setInterval(function(){
          if(mubne >= quan){
            clearTimeout(settt);
            setTimeout(function(){
              that.xunhuan(-1);
              that.zjshow = true;
              that.user.sugar -= 10;
              that.user.sugar += parseInt(that.zjshowlist["muber"]);
              that.zcclick = false;
            },200)
          }else{
            if(mubne > 24){
              quantime = 500;
            }
            if(danmuber >= 8){
              danmuber  = 0;
            }
            that.xunhuan(danmuber);
            danmuber++;
            mubne++;
          }

        },quantime)
      }
    },

  }
</script>

<style>
  .luckdraw{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #fdf8da;
  }
  .luckdraw-center{
    float: left;
    width: 100%;
    background-color: #fdf8da;
  }
  .luckdraw-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .luckdraw-cj-block{
    width: 100%;
    float: left;
    background-color: #fdf8da;
  }
  .luckdraw-cj{
    width: 680px;
    background-color: #e3d373;
    border-radius: 30px;
    height: 100%;
    float: left;
    margin: 35px;
  }
  .luckdraw-refresh-div{
    width: 100%;
    float: left;
  }
  .luckdraw-cj-centre{
    width: 100%;
    background-color: #feec98;
    border-radius: 30px;
    float: left;
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .luckdraw-cj-centre-nr{
    width: 640px;
    margin: auto;
  }
  .luckdraw-block{
    float: left;
    width: 100%;
  }
  .luckdraw-cj-title{
    float: left;
    width: 100%;
    border-radius: 10px;
    background-color: #fedd72;
    height: 80px;
    margin-top: 20px;
  }
  .luckdraw-cj-titleleft{
    line-height: 80px;
    float: left;
    font-size: 30px;
    color: #d47d1c;
    padding-left: 20px;
  }
  .luckdraw-cj-titleright{
    float: right;
    line-height: 80px;
    font-size: 30px;
    color: #d47d1c;
    padding-right: 20px;
  }
  .luckdraw-cj-centrexx{
    float: left;
    width: 100%;
    position: relative;
    margin-top: 20px;
  }
  .luckdraw-centrexx-top{
    width: 100%;
    float: left;
    height: 320px;
  }
  .luckdraw-centrexx-topbg{
    width: 600px;
    margin: auto;
    border-radius: 30px 30px 1px 1px;
    height: 320px;
    background-color: #fb948d;
  }
  .luckdraw-centrexx-nr{
    width: 640px;
    float: left;
    background-color: #fb6564;
    height: 640px;
    border-radius: 30px;
    position:absolute;
    top: 10px;
  }
  .luckdraw-centrexx-xia{
    width: 100%;
    float: left;
    height: 340px;
  }
  .luckdraw-centrexx-xiabg{
    width: 600px;
    margin: auto;
    border-radius: 1px 1px 30px 30px ;
    height: 340px;
    background-color: #c24f4c;
  }
  .luckdraw-nr-centre{
    width: 570px;
    height: 570px;
    margin: 35px;
    border-radius: 30px;
    background-color: #a62a42;
  }
  .luckdraw-nr-ul{
    width: 550px;
    height: 550px;
    float: left;
    margin-left: 10px;
    margin-top: 10px;
  }
  .luckdraw-nr-li{
    width: 183.333333px;
    height: 183.333333px;
    float: left;
  }
  .luckdraw-li-centre{
    border-radius: 10px;
    width: 163.333333px;
    height: 163.333333px;
    margin: 10px;
    background-color: #fff3c9;
    position: relative;
  }
  .luckdraw-li-centreimg{
    width: 100%;
    height: 80px;
    float: left;
    margin-top: 30px;
  }
  .luckdraw-li-centreimg img{
    width: 80px;
    height: 80px;
    margin-left: 41.666666px;
  }
  .luckdraw-li-centretitle{
    line-height: 50px;
    font-size: 20px;
    color: #af4859;
    width: 100%;
    text-align: center;
  }
  .luckdraw-li-cenemng{
    position: absolute;
    width: 100%;
    height: 100%;
    background:rgba(0,0,0,.5);
    top: 0px;
    border-radius: 10px;
  }
  .luckdraw-li-centre-click{
    border-radius: 10px;
    width: 163.333333px;
    height: 163.333333px;
    margin: 10px;
    background-color: #fd8a83;
    position: relative;
  }
  .luckdraw-li-centreimg-click{
    width: 100%;
    height: 100px;
    float: left;
    margin-top: 10px;
  }
  .luckdraw-li-centreimg-click img{
    width: 100px;
    height: 100px;
    margin-left: 31.6666px;
  }
  .luckdraw-li-centretitle-click{
    line-height: 40px;
    font-size: 25px;
    text-align: center;
    color: #fff0cc;
  }
  .luckdraw-jil-jil{
    width: 100%;
    float: left;
    margin-top: 20px;
    margin-bottom: 30px;
  }
  .luckdraw-jil-buttom{
    float: right;
    width: 200px;
    line-height: 70px;
    color: #cf880a;
    text-align: center;
    background-color: #FFFFFF;
    border-radius: 1000px;
    font-size: 28px;
  }
  .luckdraw-zhongjaa-list{
    float: left;
    width: 100%;
    margin-top: 50px;
    height: 700px;
    background-color: #fdf8da;
  }
  .luckdraw-zhongj{
    margin: auto;
    width: 680px;
    position: relative;
  }
  .luckdraw-zhongj-top{
    width: 100%;
    height: 40px;
    background-color: #f1d655;
    border-radius: 1000px;
    position: absolute;
    top: 0px;
  }
  .luckdraw-zhongj-centre{
    width: 100%;
    height: 30px;
    background-color: #746e56;
    border-radius: 1000px;
    position: absolute;
    top: 10px;
  }
  .luckdraw-zhongj-xia{
    width: 100%;
    position: absolute;
    top: 25px;
  }
  .luckdraw-list-centre{
    margin-left: 20px;
    width: 640px;
    float: left;
    background-color: #feec98;
  }
  .luckdraw-list-top{
    float: left;
    width: 100%;
    margin-top: 30px;
  }
  .luckdraw-list-top img{
    width: 640px;
    height: 100px;
  }
  .luckdraw-list-uldiv{
    float: left;
    width: 100%;
    height: 500px;
    overflow: hidden;
  }
  .luckdraw-list-ul{
    float: left;
    width: 100%;
    position: relative;
    transition: top 0.5s;
  }
  .luckdraw-list-li{
    width: 100%;
    float: left;
  }
  .luckdraw-listli-centre{
    width: 560px;
    margin: auto;
    height: 80px;
    border-bottom: 2px solid #ad7b26;
  }
  .luckdraw-listli-user{
    float: left;
    width: 340px;
  }
  .luckdraw-listli-user p{
    color: #d87929;
    font-size: 30px;
    line-height: 80px;
    width: 340px;
    margin: 0px;
  }
  .luckdraw-listli-right{
    line-height: 80px;
    float: right;
    color: #d87929;
    font-size: 30px;
    width: 200px;
    text-align: right;
  }
  .luckdraw-explain-zj-block{
    width: 100%;
    float: left;
    background-color: #fdf8da;
  }
  .luckdraw-explain{
    margin: auto;
    width: 680px;
  }
  .luckdraw-zjjj-block{
    width: 580px;
    height: 400px;
    border-radius: 20px !important;
  }
  .luckdraw-zjjj-img{
    width: 100%;
    height: 130px;
    float: left;
    margin-top: 50px;
  }
  .luckdraw-zjjj-img img{
    width: 130px;
    height: 130px;
    margin-left: 225px;
  }
  .luckdraw-zjjj-title{
    line-height: 80px;
    font-size: 40px;
    color: #414141;
    text-align: center;
    width: 100%;
  }
  .luckdraw-zjjj-buttomdiv{
    width: 100%;
    float: left;

  }
  .luckdraw-zjjj-buttom{
    width: 500px;
    margin-left: 40px;
    line-height: 80px;
    text-align: center;
    background-color: #ff7e7c;
    font-size: 35px;
    color: #FFFFFF;
    border-radius: 30px;
  }
</style>
