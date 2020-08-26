<template>
  <div class="notloggedin">
    <div class="notloggedin-centre">
      <!-- 设置 -->
      <div class="notloggedin-top-block">
        <van-nav-bar
          title=""
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        />
      </div>
      <!-- 内容 -->
      <div class="notloggedin-centre-block">
        <div class="notloggedin-logo">
          <img src="../../assets/img/class.png">
        </div>
        <div class="notloggedin-title">
          <h1>轻松注册即可永久保存，换了手机也找得到哦</h1>
        </div>
        <div class="notloggedin-buttom">
          <div class="notloggedin-register">
            <button>注册</button>
          </div>
          <div class="notloggedin-sigin">
            <button>登录</button>
          </div>
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
        msg: 'Welcome to Your Vue.js App'
      }
    },
    components: {

    },

  }
</script>

<style>
  .notloggedin{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .notloggedin-center{
    float: left;
    width: 100%;
  }
  .notloggedin-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  
  /* 内容 */
  .notloggedin-centre-block{
    float: left;
    margin-top: 300px;
    width: 100%;
  }
  .notloggedin-logo{
    width: 100%;
    height: 200px;

  }
  .notloggedin-logo img{
    width: 200px;
    height: 200px;
    float: left;
    margin-left: 300px;
  }
  .notloggedin-title{
    width: 100%;

  }
  .notloggedin-title h1{
    line-height: 50px;
    text-align: center;
    font-size: 40px;
    width: 600px;
    margin-left: 80px;
  }
  .notloggedin-buttom{
    width: 100%;
  }
  .notloggedin-register{
    width: 250px;
    float: left;
    margin-left: 100px;
  }
  .notloggedin-register button{
    border: 1px solid #ff5959;
    background-color: #ff5959;
    width: 250px;
    height: 100px;
    font-size: 40px;
    border-radius: 10px;
  }
  .notloggedin-sigin{
    width: 300px;
    margin-left: 50px;
    float: left;
  }
  .notloggedin-sigin button{
    border: 1px solid #ff5959;
    width: 250px;
    height: 100px;
    font-size: 40px;
    border-radius: 10px;
  }
</style>
