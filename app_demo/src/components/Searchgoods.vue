<template>
  <div class="searchgoods">
    <div class="searchgoods-centre">
      <div class="searchgoods-top-block">
        <div class="searchgoods-top">
          <div class="searchgoods-top-left" @click="onClickLeft">
              <img src="../assets/img/left.png">
          </div>
          <div class="searchgoods-top-right">
              <van-search
                v-model="value"
                show-action
                label=""
                placeholder="请输入搜索关键词"
                @search="onsearch"
              >
                <template #action>
                  <div @click="onsearch">搜索</div>
                </template>
              </van-search>
          </div>
        </div>
      </div>
      <div class="searchgoods-zj-block" v-if="lslist.length != 0">
        <div class="searchgoods-zj">
          <div class="searchgoods-zj-title-div">
          <div class="searchgoods-zj-title">搜索历史</div>
          <div class="searchgoods-zj-delete" @click="lishidelete()">
            <img src="../assets/img/delete.png">
          </div>
          </div>
          <div class="searchgoods-zj-ul">
            <div class="searchgoods-zj-li" v-for="(item,name) in lslist" @click="searchgoodsli(item)">{{item}}</div>
          </div>
        </div>
      </div>
      <div class="searchgoods-rm-block">
        <div class="searchgoods-rm">
          <div class="searchgoods-rm-title">热门搜索</div>
          <div class="searchgoods-rm-ul">
            <div class="searchgoods-rm-li" v-for="item in list" @click="searchgoodsli(item.name)">{{item.name}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Searchgoods',
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
           .post(this.$myStore.url+"/api/index/getsearch",this.$qs.stringify({type:2}))
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
      onsearch:function(){
        this.$router.push({ name: 'Goodslistsear', query: { val:this.value}});
        this.getlishi(this.value);
      },
      searchgoodsli:function(val){
        this.$router.push({ name: 'Goodslistsear', query: { val:val}});
        this.getlishi(val);
      },
      getlishi:function(vv){
        var goodssearch = this.$store.state.goodssearch;
        if(vv == ""){
          this.lslist = goodssearch;
        }else{
          var ind = goodssearch.indexOf(vv);
          if(ind == -1){
            goodssearch.push(vv);
          }
          this.$store.commit('setgoodssearch',goodssearch);
        }
      },
      lishidelete:function(){
        this.lslist = [];
        this.$store.commit('setgoodssearch',[]);
      }
    }
  }
</script>

<style>
  .wadawd{
    border: 1px solid red;
    width: 680px;
  }
  .searchgoods{
    width: 100%;
    height: 100%;
    position: relative;

  }
  .searchgoods-center{
    float: left;
    width: 100%;
    background-color: #f5f5f5;
  }
  .searchgoods-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .searchgoods-top{
    width: 100%;
    margin: auto;
  }
  .searchgoods-top-left{
    float: left;
    width: 75px;
    height: 95px;
  }
  .searchgoods-top-left img{
    width: 35px;
    height: 35px;
    margin: 35px 20px 0px 30px;
  }
  .searchgoods-top-right{
    width: 650px;
    float:left ;
  }
  .searchgoods-rm-block{
    width: 100%;
    float: left;
    margin-top: 20px;
  }
  .searchgoods-rm{
    width: 680px;
    margin: auto;
  }
  .searchgoods-rm-title{
    width: 200px;
    line-height: 50px;
    float: left;
    color: #727272;
    font-size: 30px;
  }

  .searchgoods-rm-ul{
    width: 100%;
    float: left;
    margin-top: 0px;
  }
  .searchgoods-rm-li{
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

  .searchgoods-zj-block{
    width: 100%;
    float: left;
    margin-top: 10px;
  }
  .searchgoods-zj{
    width: 680px;
    margin: auto;
  }
  .searchgoods-zj-title-div{
    float: left;
    width: 100%;
  }
  .searchgoods-zj-title{
    width: 200px;
    line-height: 50px;
    float: left;
    color: #727272;
    font-size: 30px;
  }
  .searchgoods-zj-delete{
    float: right;
    width: 50px;
    height: 50px;
  }
  .searchgoods-zj-delete img{
    width: 30px;
    height: 30px;
    margin: 10px;
  }
  .searchgoods-zj-ul{
    width: 100%;
    float: left;
    margin-top: 0px;
  }
  .searchgoods-zj-li{
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
