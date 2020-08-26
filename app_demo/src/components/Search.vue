<template>
  <div class="search">
    <div class="search-centre">
      <div class="search-top-block">
        <div class="search-top">
          <div class="search-top-left" @click="onClickLeft">
              <img src="../assets/img/left.png">
          </div>
          <div class="search-top-right">
              <van-search
                v-model="value"
                show-action
                label=""
                placeholder="请输入搜索关键词"
                @search="onSearch"
              >
                <template #action>
                  <div @click="onSearch">搜索</div>
                </template>
              </van-search>
          </div>
        </div>
      </div>
      <div class="search-zj-block" v-if="lslist.length != 0">
        <div class="search-zj">
          <div class="search-zj-title-div">
          <div class="search-zj-title">搜索历史</div>
          <div class="search-zj-delete" @click="lishidelete()">
            <img src="../assets/img/delete.png">
          </div>
          </div>
          <div class="search-zj-ul">
            <div class="search-zj-li" v-for="(item,name) in lslist" @click="searchli(item)">{{item}}</div>
          </div>
        </div>
      </div>
      <div class="search-rm-block">
        <div class="search-rm">
          <div class="search-rm-title">热门搜索</div>
          <div class="search-rm-ul">
            <div class="search-rm-li" v-for="item in list" @click="searchli(item.name)">{{item.name}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Search',
    data () {
      return {
        msg: 'Welcome to Your Vue.js App',
        value:"",
        list:[],
        lslist:[]
      }
    },
    created() {
      this.$axios
           .post(this.$myStore.url+"/api/index/getsearch",this.$qs.stringify({type:1}))
           .then(response=>{
              //成功
              var data = response.data;
              if(data.code == 1){
                var resdata = data.data;
                this.list = resdata.list;
                //根据
              }

              //渲染数据
           })
           .catch(error=>{
              //失败
              if(type == "refresh"){
                this.$toast('失败');
              }
       });
       this.getlishi("");
    },
    methods:{
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(event){
        this.edit = this.edit ? false : true;
      },
      clickhomepage:function(event){
        this.edit = false;
      },
      onSearch:function(){
        this.$router.push({ name: 'Articlesearchlist', query: { val:this.value}});
        this.getlishi(this.value);
      },
      searchli:function(val){
        this.$router.push({ name: 'Articlesearchlist', query: { val:val}});
        this.getlishi(val);
      },
      getlishi:function(vv){
        var dtsearch = this.$store.state.dtsearch;
        if(vv == ""){
          this.lslist = dtsearch;
        }else{
          var ind = dtsearch.indexOf(vv);
          if(ind == -1){
            dtsearch.push(vv);
          }
          console.log(dtsearch);
          this.$store.commit('setdtsearch',dtsearch);
        }
      },
      lishidelete:function(){
        this.lslist = [];
        this.$store.commit('setdtsearch',[]);
      }
    }
  }
</script>

<style>
  .wadawd{
    border: 1px solid red;
    width: 680px;
  }
  .search{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .search-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .search-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .search-top{
    width: 100%;
    margin: auto;
  }
  .search-top-left{
    float: left;
    width: 75px;
    height: 95px;
  }
  .search-top-left img{
    width: 35px;
    height: 35px;
    margin: 35px 20px 0px 30px;
  }
  .search-top-right{
    width: 650px;
    float:left ;
  }
  .search-rm-block{
    width: 100%;
    float: left;
    margin-top: 20px;
  }
  .search-rm{
    width: 680px;
    margin: auto;
  }
  .search-rm-title{
    width: 200px;
    line-height: 50px;
    float: left;
    color: #727272;
    font-size: 30px;
  }

  .search-rm-ul{
    width: 100%;
    float: left;
    margin-top: 0px;
  }
  .search-rm-li{
    float: left;
    line-height: 50px;
    text-align: center;
    padding: 0px 20px;
    color: #4b4b4b;
    font-size: 25px;
    background-color: #ededed;
    border-radius: 1000px;
    margin: 20px 20px;
  }

  .search-zj-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .search-zj{
    width: 680px;
    margin: auto;
  }
  .search-zj-title-div{
    float: left;
    width: 100%;
  }
  .search-zj-title{
    width: 200px;
    line-height: 50px;
    float: left;
    color: #727272;
    font-size: 30px;
  }
  .search-zj-delete{
    float: right;
    width: 50px;
    height: 50px;
  }
  .search-zj-delete img{
    width: 30px;
    height: 30px;
    margin: 10px;
  }
  .search-zj-ul{
    width: 100%;
    float: left;
    margin-top: 0px;
  }
  .search-zj-li{
    float: left;
    line-height: 50px;
    text-align: center;
    padding: 0px 20px;
    color: #4b4b4b;
    font-size: 25px;
    background-color: #ededed;
    border-radius: 1000px;
    margin: 20px 20px;
  }
</style>
