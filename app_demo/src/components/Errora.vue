<template>
  <div class="error-block">
    <div class="error-div">
      <div class="error-img"><img src="../assets/img/error.png"></div>
      <div class="error-title"><p>网络错误</p></div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Errora',
    data () {
      return {
        msg: 'Welcome to Your Vue.js App'
      }
    }
  }
</script>

<style>
  .error-block{
    width: 100%;
    float: left;
    margin-top: 400px;
  }
  .error-div{
    width: 300px;
    margin: auto;

  }
  .error-img{
    width: 100px;
    height: 100px;
    float: left;
  }
  .error-img img{
    width: 100px;
    height: 100px;
  }
  .error-title{
    width: 180px;
    height: 100px;
    margin-left: 20px;
    float: left;
  }
  .error-title p{
    margin: 0px;
    line-height: 100px;
    font-size: 35px;
    color: #cdcdcd;
  }
</style>
