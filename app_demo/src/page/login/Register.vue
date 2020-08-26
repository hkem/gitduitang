<template>
  <div class="register">
    <div class="register_centre">
      <!-- 设置 -->
      <div class="register-top-block">
        <van-nav-bar
          title=""
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        />
      </div>
      <div class="register-cebtre-block">
        <div class="register-cebtre-title">
          <h1>注册账号，加入糖糖</h1>
        </div>
        <van-form validate-first :show-error-message="false"  @submit="onSubmit">

          <div class="register-cebtre-input">
            <div class="register-input">
              <van-cell-group>
                <van-field  label="手机号码:" name="phone"  v-model="phone" placeholder="请输入手机号码" :rules="[{ required:true, message: '请输入手机号码' }]"/>
              </van-cell-group>
              <van-cell-group>
                <van-field  label="昵　　称:" name="username"  v-model="username" placeholder="请输入昵称" :rules="[{ required:true, message: '请输入昵称' }]"/>
              </van-cell-group>
              <van-cell-group>
                <van-field  label="密　　码:" type="password" name="password" v-model="password" placeholder="请输入密码" :rules="[{ required:true, message: '请输入密码' }]"/>
              </van-cell-group>
              <van-cell-group>
                <van-field  label="确认密码:" type="password" name="confirm_password" v-model="confirm_password" placeholder="请输入确认密码" :rules="[{ required:true, message: '请输入确认密码' }]"/>
              </van-cell-group>
            </div>
          </div>

          <div class="register-cebtre-bottum">
            <div class="register-cebtre">
              <van-button color="#ff595b" native-type="submit" v-bind:disabled="budisabled" v-bind:loading="buloading" class="awdawd" type="info" loading-text="注册中..." block>注册</van-button>
            </div>
          </div>

          <div class="register-cebtre-ts">
            <div class="register-ts">
              <div class="register-checkbox"><van-checkbox v-model="radio" checked-color="#fd5d51"></van-checkbox></div>
              <div class="register-agreement">
                <p>同意《用户协议》和《隐私协议》</p>
              </div>
            </div>
          </div>
          </van-form>
        </div>
      <div class="register-other-block">
        <div class="register-other">
          <div class="register-other-left"></div>
          <div class="register-other-centre">
            <p>其他账号注册</p>
          </div>
          <div class="register-other-right"></div>
        </div>
      </div>
      <div class="register-qq-block">
        <div class="register-qq">
          <div class="register-qq-li">
            <div class="register-qq-img">
              <img src="../../assets/img/microblog.png">
            </div>
            <div class="register-qq-title">微博</div>
          </div>
          <div class="register-qq-li">
            <div class="register-qq-img">
              <img src="../../assets/img/QQ.png">
            </div>
            <div class="register-qq-title">QQ</div>
          </div>
          <div class="register-qq-li">
            <div class="register-qq-img">
              <img src="../../assets/img/wechat.png">
            </div>
            <div class="register-qq-title">微信</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Register',
  data () {
    return {
      radio: false,
      value:"",
      phone:"",
      username:"",
      password:"",
      confirm_password:"",
      buloading:false,
      budisabled:false
    }
  },
  components: {

  },
  created() {


  },
  methods:{
    // 返回上一页
    onClickLeft:function(event){
      this.$router.go(-1);
    },
    onSubmit(event){
      if(this.radio == false){
        this.$toast.fail('请阅读用户协议');
        return;
      }
      //判断手机格式
      var reg = 11 && /^((13|14|15|17|18)[0-9]{1}\d{8})$/;
      if (!reg.test(event.phone)) {
      	this.$toast.fail('手机号码格式不正确');
        return;
      }

      //判断确认密码是否一致
      if(this.password !== this.confirm_password){
        this.$toast.fail('确认密码不一致');
        return;
      }

      this.buloading = true;
      this.budisabled = true;

      //请求接口
      this.$axios
           .post(this.$myStore.url+"/api/token/register",this.$qs.stringify({
              phone: this.phone,
              username: this.username,
              password: this.password,
            }))
           .then(response=>{
              //成功
              if(response.status != 200){
                this.$toast.fail('注册失败');
              }else{
                var data = response.data;
                if(data.code == 1){
                  this.$toast.success('注册成功');
                  //跳转登录页
                  var that = this;
                   setTimeout(function(){
                     that.$router.push({ name:'Signin'});
                   },1000)
                }else{
                  this.$toast.fail(data.msg);
                }
              }
            this.buloading = false;
            this.budisabled = false;
           })
           .catch(error=>{
              this.$toast.fail('注册失败');
              this.buloading = false;
              this.budisabled = false;
          });
    },

  }

}
</script>

<style>
  .register{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .register-center{
    float: left;
    width: 100%;
  }
  .register-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .awdawd{
    height: 80px;
  }
  .register-cebtre-block{
    float: left;
    margin-top: 100px;
  }
  .register-cebtre-title{
    float: left;
    width: 100%;
  }
  .register-cebtre-title h1{
    float: left;
    line-height: 80px;
    font-size: 30px;
    text-align: center;
    width: 100%;
  }
  .register-cebtre-input{
    height: 400px;
    float: left;
    width: 100%;
  }
  .register-input{
    margin: auto;
    width: 600px;
  }
  .register-cebtre-bottum{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .register-cebtre{
    width: 600px;
    margin: auto;
  }
  .register-cebtre-ts{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .register-ts{
    width: 600px;
    margin: auto;
  }
  .register-checkbox{
    float: left;width: 50px;height: 40px;
    margin-left: 100px;
  }
  .register-agreement{
    float: left;
  }
  .register-agreement p{
    line-height: 40px;
    margin: 0px;
    margin-top: 5px;
  }
  .register-other-block{
    float: left;
    width: 100%;
    margin-top: 150px;
  }
  .register-other{
    width: 680px;
    margin: auto;
  }
  .register-other-left{
    float: left;
    height: 2px;
    background-color: #ebebeb;
    width: 240px;
    margin-top: 14px;
  }
  .register-other-centre{
    float: left;width: 200px;
  }
  .register-other-centre p{
    margin: 0px;
    line-height: 30px;
    color: #646464;
    font-size: 25px;
    text-align: center;
  }
  .register-other-right{
    float: left;
    height: 2px;
    background-color: #ebebeb;
    width: 240px;
    margin-top: 14px;
  }
  .register-qq-block{
    float: left;
    width: 100%;
    margin-top: 50px;
  }
  .register-qq{
    width: 600px;
    margin: auto;
  }
  .register-qq-li{
    width: 100px;
    float: left;
    padding: 0px 50px;
  }
  .register-qq-img{
    width: 100px;
    height: 100px;
    float: left;
    border: 2px solid #ff7c7b;
    border-radius: 1000px;
  }
  .register-qq-img img{
    width: 70px;
    height: 70px;
    margin: 15px;
  }
  .register-qq-title{
    float: left;
    width: 100px;
    text-align: center;
    line-height: 50px;
  }
</style>
