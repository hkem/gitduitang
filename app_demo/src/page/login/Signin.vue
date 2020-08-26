<template>
  <div class="signin">
    <div class="signin_centre">
      <!-- 设置 -->
      <div class="signin-top-block">
        <van-nav-bar
          title=""
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        />
      </div>
      <div class="signin-cebtre-block">
        <div class="signin-cebtre-title">
          <h1>登录堆糖</h1>
        </div>
        <van-form validate-first :show-error-message="false"  @submit="onSubmit">
        <div class="signin-cebtre-input">
          <div class="signin-input">
            <van-cell-group>
              <van-field v-model="phone" name="phone" label="" placeholder="请输入手机号码" :rules="[{ required:true, message: '请输入手机号码' }]"/>
            </van-cell-group>
            <van-cell-group>
              <van-field v-model="password" name="password" type="password" label="" placeholder="请输入密码" :rules="[{ required:true, message: '请输入昵称' }]"/>
            </van-cell-group>

          </div>
        </div>
        <div class="signin-cebtre-pass">
          <div class="signin-pass">
            <p>忘记密码？</p>
          </div>
        </div>
        <div class="signin-cebtre-bottum">
          <div class="signin-cebtre">
            <van-button color="#ff595b" native-type="submit" v-bind:disabled="budisabled" v-bind:loading="buloading" class="awdawd" type="info" loading-text="登录中..." block>登录</van-button>
          </div>
        </div>
        <div class="signin-cebtre-ts">
          <div class="signin-ts">
            <div class="signin-checkbox"><van-checkbox   v-model="radio"  checked-color="#fd5d51"></van-checkbox></div>
            <div class="signin-agreement">
              <p>同意《用户协议》和《隐私协议》</p>
            </div>
          </div>
        </div>
        </van-form>
      </div>
      <div class="signin-other-block">
        <div class="signin-other">
          <div class="signin-other-left"></div>
          <div class="signin-other-centre">
            <p>其他账号登录</p>
          </div>
          <div class="signin-other-right"></div>
        </div>
      </div>
      <div class="signin-qq-block">
        <div class="signin-qq">
          <div class="signin-qq-li">
            <div class="signin-qq-img">
              <img src="../../assets/img/microblog.png">
            </div>
            <div class="signin-qq-title">微博</div>
          </div>
          <div class="signin-qq-li">
            <div class="signin-qq-img">
              <img src="../../assets/img/QQ.png">
            </div>
            <div class="signin-qq-title">QQ</div>
          </div>
          <div class="signin-qq-li">
            <div class="signin-qq-img">
              <img src="../../assets/img/wechat.png">
            </div>
            <div class="signin-qq-title">微信</div>
          </div>
        </div>
      </div>
      <div class="signin-register-block">
        <div class="signin-register">
          <router-link to="/register">
            <p>没有账号？立即注册</p>
            </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Signin',
  data () {
    return {
      checked: true,
      radio: false,
      phone:"",
      password:"",
      buloading:false,
      budisabled:false,
      type:""
    }
  },
  components: {

  },
  created() {
    this.type = this.$route.query.type;
  },
  methods:{
    onClickLeft:function(event){
      console.log(this.type);
      if(this.type == "out"){
        this.$router.push({ name:'Recommend'});
      }else{
        this.$router.go(-1);
      }

    },
    //登录
    onSubmit(event){
      if(this.radio == false){
        this.$toast.fail('请阅读用户协议');
        return;
      }
      //判断手机格式
      var reg = 11 && /^((13|14|15|17|18)[0-9]{1}\d{8})$/;
      if (!reg.test(event.phone)) {
      	this.$toast.fail('手机号码格式不正确1');
        return;
      }
      this.buloading = true;
      this.budisabled = true;

      //请求接口
      this.$axios
           .post(this.$myStore.url+"/api/token/signin",this.$qs.stringify({
              phone: this.phone,
              password: this.password,
            }))
           .then(response=>{
              //成功
              if(response.status != 200){
                this.$toast.fail('登录失败');
              }else{
                var data = response.data;
                if(data.code == 1){
                  this.$toast.success('登录成功');
                  //保存token
                  var resdata = data.data;
                  this.$store.commit('settoken',resdata.token);
                  //跳转登录页
                  var that = this;
                   setTimeout(function(){
                     that.$router.push({ name:'My'});
                   },1000)
                }else{
                  this.$toast.fail(data.msg);
                }
              }
            this.buloading = false;
            this.budisabled = false;
           })
           .catch(error=>{
              this.$toast.fail('登录失败');
              this.buloading = false;
              this.budisabled = false;
          });
    },
  }

}
</script>

<style>
  .signin{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .signin-center{
    float: left;
    width: 100%;
  }
  .signin-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .awdawd{
    height: 80px;
  }
  .signin-cebtre-block{
    float: left;
    margin-top: 100px;
  }
  .signin-cebtre-title{
    float: left;
    width: 100%;
  }
  .signin-cebtre-title h1{
    float: left;
    line-height: 80px;
    font-size: 30px;
    text-align: center;
    width: 100%;
  }
  .signin-cebtre-input{
    height: 200px;
    float: left;
    width: 100%;
  }
  .signin-input{
    margin: auto;
    width: 600px;
  }
  .signin-cebtre-pass{
    width: 100%;
    float: left;
  }
  .signin-pass{
    width:600px;
    margin: auto;
  }
  .signin-pass p{
    line-height: 30px;
    font-size: 25px;
    float: right;
    color: #777777;
  }
  .signin-cebtre-bottum{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .signin-cebtre{
    width: 600px;
    margin: auto;
  }
  .signin-cebtre-ts{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .signin-ts{
    width: 600px;
    margin: auto;
  }
  .signin-checkbox{
    float: left;width: 50px;height: 40px;
    margin-left: 100px;
  }
  .signin-agreement{
    float: left;
  }
  .signin-agreement p{
    line-height: 40px;
    margin: 0px;
    margin-top: 5px;
  }
  .signin-other-block{
    float: left;
    width: 100%;
    margin-top: 150px;
  }
  .signin-other{
    width: 680px;
    margin: auto;
  }
  .signin-other-left{
    float: left;
    height: 2px;
    background-color: #ebebeb;
    width: 240px;
    margin-top: 14px;
  }
  .signin-other-centre{
    float: left;width: 200px;
  }
  .signin-other-centre p{
    margin: 0px;
    line-height: 30px;
    color: #646464;
    font-size: 25px;
    text-align: center;
  }
  .signin-other-right{
    float: left;
    height: 2px;
    background-color: #ebebeb;
    width: 240px;
    margin-top: 14px;
  }
  .signin-qq-block{
    float: left;
    width: 100%;
    margin-top: 50px;
  }
  .signin-qq{
    width: 600px;
    margin: auto;
  }
  .signin-qq-li{
    width: 100px;
    float: left;
    padding: 0px 50px;
  }
  .signin-qq-img{
    width: 100px;
    height: 100px;
    float: left;
    border: 2px solid #ff7c7b;
    border-radius: 1000px;
  }
  .signin-qq-img img{
    width: 70px;
    height: 70px;
    margin: 15px;
  }
  .signin-qq-title{
    float: left;
    width: 100px;
    text-align: center;
    line-height: 50px;
  }
  .signin-register-block{
    float: left;
    width: 100%;
    margin-top: 100px;
  }
  .signin-register{
    margin: auto;
    width: 300px;
  }
  .signin-register p{
    font-size: 25px;
    margin: 0px;
    text-align: center;
  }
  .signin-register a{
    color: #444444;
  }
</style>
