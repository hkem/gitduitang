<template>
  <div id="app">
    <div id="center">
        <router-view/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
  created() {
    //进来检查是否登录
    var white = this.$myStore.witlearr;//不检查名单
    var route_name = this.$route.name;
    var token = this.$store.state.token;
    console.log(token);
    if (white.indexOf(route_name) == -1) {
      console.log("检查token");
      if(token != ""){
        this.$axios
                .post(this.$myStore.url+"/api/token/inspect_token",this.$qs.stringify({
                   token: token,
                 }))
                .then(response=>{
                  var data = response.data;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('登录失败');
                   }else{
                     if(data.code == -1){
                       this.$store.commit('settoken',"");
                       this.$router.push({ name:'Signin'});
                     }
                     if(data.code == -2){
                       this.$store.commit('settoken',"");
                       this.$router.push({ name:'Signin'});
                     }
                   }

                })
                .catch(error=>{
                   this.$toast.fail('网络错误');
               });
      }else{
          this.$router.push({ name:'Signin'});
      }
 　　}
    // //请求接口

  }
}
</script>

<style>
  html,body,#app{
    height: 100%;
  }
#app {
  width: 100%;
  height: 100%;
}
#center{
  margin: auto;
  height: 100%;
}
</style>
