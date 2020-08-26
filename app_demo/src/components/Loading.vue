<template>
  <div class="loading-block">
    <div class="loading-div">
      <van-loading class="" type="spinner"/>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Loading',
    data () {
      return {
        msg: 'Welcome to Your Vue.js App'
      }
    }
  }
</script>

<style>
  .loading-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .loading-div{
    width: 100px;
    height: 100px;
    margin: auto;
  }
</style>
