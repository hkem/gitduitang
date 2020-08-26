<template>
  <div class="setup">
    <div class="setup-centre">
      <div class="setup-top-block">
        <van-nav-bar
          title="设置"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        />
      </div>
      <div class="setup-account-block" style="display: none;">
        <van-cell is-link title="账号与安全"/>
      </div>
      <div class="setup-image-block" @click="guanyu()">
        <van-cell is-link title="关于我们"/>
      </div>
      <div class="setup-image-block" @click="huanc()">
        <van-cell is-link title="清除缓存"/>
      </div>
      <div class="setup-out-block">
        <div class="setup-out-button" v-on:click="out">退出登录</div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Setup',
    data () {
      return {

      }
    },
    methods:{
      guanyu:function(){
        this.$router.push({ name:'Aboutus'});
      },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      out:function(){
        this.$dialog.confirm({
          title: '确认消息',
          message: '是否退出登录？',
        })
          .then(() => {
                var token = this.$store.state.token;
            // on confirm
            this.$axios
                    .post(this.$myStore.url+"/api/token/out_token",this.$qs.stringify({
                       token: token,
                     }))
                    .then(response=>{
                      var data = response.data;
                       //成功
                       if(response.status != 200){
                         this.$toast.fail('登录失败');
                       }else{
                         if(data.code == 1){
                           this.$store.commit('settoken',"");
                           this.$router.push({ name:'Signin'});
                         }
                          if(data.code == -1){
                            this.$toast.fail('退出失败');
                          }
                       }

                    })
                    .catch(error=>{
                       this.$toast.fail('网络错误');
                   });
          })
          .catch(() => {
            // on cancel

          });
      }
    }
  }
</script>

<style>
  .setup{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .setup-center{
    float: left;
    width: 100%;
  }
  .setup-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .setup-account-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .setup-image-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .setup-out-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .setup-out-button{
    color: #ff5959;
    font-size: 30px;
    width: 100%;
    line-height: 100px;
    text-align: center;
    background-color: #ffffff;
  }
</style>
