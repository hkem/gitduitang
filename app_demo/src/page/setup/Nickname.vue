<template>
  <div class="nickname">
    <div class="nickname-centre">
      <div class="nickname-top-block">
        <van-nav-bar
          title="昵称"
          left-text=""
          right-text="确定"
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        />
      </div>
      <div class="nickname-input-block">
        <div class="nickname-input">
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
      this.value = this.$route.query.nickname;
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
                     type:1,
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
  .nickname{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .nickname-center{
    float: left;
    width: 100%;
  }
  .nickname-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .nickname-input-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .nickname-input{
    width: 680px;
    margin: auto;
  }
</style>
