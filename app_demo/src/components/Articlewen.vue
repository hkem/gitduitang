<template>
  <div class="articlewen">
    <div class="articlewen-centre">
      <van-list v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
             :error.sync="error"
              error-text="请求失败，点击重新加载"
              :immediate-check="false"
            @load="onLoad"
          >
          <div class="van-clearfix">
      <div class="articlewen-top-block">
        <van-nav-bar
          title="文章"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        >

        </van-nav-bar>
      </div>
      <div class="articlewen-error-block" v-if="isError">
        <van-empty image="error" description="网络错误,请刷新" />
      </div>
      <!-- 请求中 -->
      <div class="artdateils-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>

        <div class="articlewen-comment-block">
          <div class="articlewen-comment">
        <van-pull-refresh v-if="isCentre" v-model="isLoading" success-text="刷新成功" @refresh="onRefresh">
            <div class="articlewen-commentlist-ul">
              <div class="articlewen-commentlist-li" v-for="(item,index) in list" :key="item.id" @click="clickcentre(item.id)">
                <van-swipe-cell :name="index" :before-close="beforeClose">
                  <div class="articlewen-commentlist-li-centre">
                    <div class="articlewen-commentlist-time">{{item.ctime}}</div>
                    <div class="articlewen-commentlist-user-img">
                      <van-image class="articlewenall-li-img" fit="cover" :src="item.image" />
                    </div>
                    <div class="articlewen-commentlist-user-ul">
                      <h6 class="van-multi-ellipsis--l2">{{item.name}}</h6>
                    </div>
                  </div>
                  <template #right>
                      <van-button square text="删除" type="danger"  class="articlewen-delete-button" />
                  </template>
                  </van-swipe-cell>
              </div>
            </div>
            </van-pull-refresh>
          </div>
        </div>
        </div>
</van-list>
<div class="articlewen-ddi-block"></div>
    </div>
  </div>
</template>

<script>
  import Errora from './Errora.vue';
  import Loading from './Loading.vue';
  export default {
    name: 'Articlewen',
    data () {
      return {

        edit: false,
        request_Loading:true,
        isLoading: false,
        isCentre:true,
        isError:false,
        id:0,
        list:[],
        page:1,
        allpage:0,
        loading: false,
        finished: false,
        error:false,
        commentval:""
      }
    },
    components: {
        Errora,
        Loading
    },
    created() {
      this.id = this.$route.query.id;
       this.get_list(1);
    },
    methods:{
      clickcentre:function(cen){
        this.$router.push({ name: 'Articledateils', query: { id:cen}})
      },
      beforeClose({ name,position, instance }) {
        var that = this;
        console.log(name);
            switch (position) {
              case 'right':
                this.$dialog.confirm({
                  message: '确定删除吗？',
                }).then(() => {
                  var event = that.list[name]["id"];
                  var key = name;
                  var token = this.$store.state.token;
                   if(token == ""){
                     this.$router.push({ name:'Signin'});
                     return;
                   }
                  this.$axios
                          .post(this.$myStore.url+"/api/index/articledelete",this.$qs.stringify({
                             token: token,
                             id:event,
                           }))
                          .then(response=>{
                            var data = response.data;
                             //成功
                             if(response.status != 200){
                               this.$toast.fail('删除失败');
                             }else{
                               if(data.code == 1){
                                  this.$toast.success('删除成功');
                                  var jiulist = this.list;
                                  jiulist.splice(key, 1);
                                  this.list = jiulist;
                               }
                                if(data.code == -1){
                                  this.$toast.fail("删除失败");
                                }
                                if(data.code == -2){
                                   this.$router.push({ name:'Signin'});
                                }
                             }

                          })
                          .catch(error=>{
                            if(error.response){
                              this.$toast.fail('删除失败');
                            }
                         });
                }).catch(() => {
                  // on cancel
                  instance.close();
                });
                break;
            }
          },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        this.edit = this.edit ? false : true;
      },
      clickhomepage:function(event){
        this.edit = false;
      },
      onLoad:function(){

        if(this.page <= this.allpage){
          var p = this.page + 1;
          this.get_list(p,"");
        }
      },
      get_list(p,type){
        var that = this;
        var token = this.$store.state.token;
        this.$axios
             .post(this.$myStore.url+"/api/index/allarticlewen",this.$qs.stringify({id:that.id,token:token,p:p}))
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
                    if(p == 1){
                      // that.isCentre = true;
                      this.request_Loading = false;
                      this.list = resdata.list;
                      this.allpage = resdata.listcount;
                    }else{
                      var jiulist = this.list;
                      this.list = jiulist.concat(resdata.list);
                    }
                    if(this.allpage == p){
                      this.finished = true;
                    }
                    this.loading = false;
                    // this.page = p + 1;
                    this.page = p;
                  }else{
                    this.isError = true;
                    if(p != 1){
                      this.error = true;
                    }
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
      onRefresh:function(){
        this.get_list(1,"refresh");
      },
    }
  }
</script>

<style>
  .articlewen-delete-button{
    height: 200px;
  }
  .articlewen-ddi-block{
    float: left;
    width: 100%;
    height: 150px;
  }
  .articlewen-error-block{
    float: left;
    width: 100%;
  }
  .articlewen{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .articlewen-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .articlewen-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .articlewen-commentlist-ul{
    width: 100%;
    float: left;
  }
  .articlewen-commentlist-li{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .articlewen-commentlist-time{
    width: 100%;
    font-size: 30px;
    line-height: 50px;
    color: #cbcbcb;
  }
  .articlewen-commentlist-user-img{
    float: left;
    width: 150px;
    height: 150px;
  }
  .articlewen-commentlist-user-img .articlewenall-li-img{
    width: 150px;
    height: 150px;
  }
  .articlewen-commentlist-user-ul{
    width: 510px;
    float: left;
    margin-left: 20px;
  }
  .articlewen-commentlist-user-ul h6{
    width: 510px;
    float: left;
    margin: 0px;
    font-size: 30px;
  }






  .articlewen-comment-block{
    float: left;
    width: 100%;
  }
  .articlewen-comment{
    margin: auto;
    width: 100%;
  }
  .articlewen-commentlist-li-centre{
    margin: auto;
    width: 680px;
  }

</style>
