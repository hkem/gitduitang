<template>
  <div class="introduction">
    <div class="introduction-centre">
      <div class="introduction-top-block">
        <van-nav-bar
          title="签名"
          left-text=""
          right-text="确定"
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        />
      </div>
      <div class="introduction-input-block">
        <div class="introduction-input">
          <van-cell-group>
            <van-field v-model="value" type="textarea" rows="4" label="" placeholder="请输入用户名" />
          </van-cell-group>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Setup',
    data () {
      return {
        value:""
      }
    },
    created(){
      this.value = this.$route.query.introduction;
    },
    methods:{
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        var token = this.$store.state.token;
        console.log(this.value);
        if(this.value == ""){
          this.$toast.fail('请填写昵称');
          return;
        }
          this.$axios
                  .post(this.$myStore.url+"/api/token/update_data",this.$qs.stringify({
                     token: token,
                     type:2,
                     val:this.value
                   }))
                  .then(response=>{
                    var data = response.data;
                     //成功
                     if(response.status != 200){
                       this.$toast.fail('更改失败');
                     }else{
                       if(data.code == 1){
                          this.$toast.success('更改成功');
                          var that = this;
                           setTimeout(function(){
                             that.$router.go(-1);
                           },1000)
                       }
                        if(data.code == -1){
                          this.$toast.fail(data.msg);
                        }
                     }

                  })
                  .catch(error=>{
                    if(error.response){
                      this.$toast.fail('失败');
                    }
                 });
      }
    },
  }
</script>

<style>
  .introduction{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .introduction-center{
    float: left;
    width: 100%;
  }
  .introduction-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .introduction-input-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .introduction-input{
    width: 680px;
    margin: auto;
  }
</style>
