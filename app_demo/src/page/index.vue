<template>
  <div class="index">
    <div class="index-center">
      <!-- 搜索 -->
      <div class="index-search-block">
        <div class="index-search" >
          <div class="index-search-background" @click="Searchcli()">
            <img src="../assets/img/search.png"/>
            <div class="search-title">大家都在搜<q>柯南</q></div>
          </div>
          <div class="index-search-add-to" @click="show = true">
            <img src="../assets/img/release.png"/>
          </div>
         </div>
      </div>
      <!-- 分类 -->
      <div class="index-classify-block">
        <Classify></Classify>
      </div>
      <!--  内容 -->
      <div class="index-list">
        <router-view/>
      </div>
    </div>
    <div class="index-nav">
      <BottomNavigation></BottomNavigation>
    </div>
    <van-overlay class-name="index-overlay-css" :show="show" >
      <div class="index-overlay">
        <div class="index-overlay-img" v-on:click="new_show"><img src="../assets/img/close.png"></div>
      </div>
      <div class="index-overlay-ul">
        <div class="index-overlay-title">发布新的</div>
        <div class="index-overlay-block">
          <div class="index-overlay-kk">
            <div class="index-overlay-li">
              <div class="index-overlay-centre" v-on:click="Newimage">
                <div class="overlay-centre-img"><img src="../assets/img/image.png"></div>
                <div class="overlay-centre-title">图片</div>
              </div>
            </div>
            <div class="index-overlay-li">
              <div class="index-overlay-centre" v-on:click="Newwenz">
                <div class="overlay-centre-img"><img src="../assets/img/article.png"></div>
                <div class="overlay-centre-title">文章</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </van-overlay>
  </div>
</template>

<script>

  import BottomNavigation from '../components/BottomNavigation.vue';
  import Classify from '../components/Classify.vue'

export default {
  name: 'Index',
  data () {
    return {
      onclassify: 1,
      show:false
    }
  },
  components: {
      BottomNavigation,
      Classify
  },
  created() {

  },
  methods:{
    new_show:function(){
      this.show = false;
    },
    Newimage:function(){
      this.$router.push({ name:'Newimage'});
    },
    Newwenz:function(){
      this.$router.push({ name:'Newsarticle'});
    },
    Searchcli:function(){
      this.$router.push({ name:'Search'});
    }
  }
}

</script>

<style>
  /* 搜索 */
  .index{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .index-nav{
    width: 100%;
    position: fixed;
    bottom: 0px;
    border-top: 1px gray solid;
  }
  .index-center{
    padding-bottom: 100px;
  }
  .index-search-block{
    width: 100%;
    height: 100px;
    float: left;
    margin-top: 25px;
  }
  .index-search{
    width: 680px;
    margin: auto;
    height: 50px;
  }
  .index-search-background {
    background-color: #f5f5f5;
    border-radius: 5px;
    width: 600px;
    height: 50px;
    float: left;
  }
  .index-search-background img{
    width: 30px;
    height: 30px;
    float: left;
    margin-left: 20px;
    margin-top: 10px;
  }
  .index-search-background .search-title{
    color: #989898;
    margin-left: 10px;
    float: left;
    line-height: 50px;
  }
  .index-search-add-to{
    float: left;
    height: 50px;
    width: 50px;
    margin-left: 20px;
  }
  .index-search-add-to img{
    height: 50px;
    width: 50px;
  }
  /* 分类 */
  .index-classify-block{
    width: 100%;
    height: 80px;
    float: left;
    border-bottom: 2px solid #d8d8d8;
  }
  .index-overlay{
    width: 100%;
    height: 100px;
    float: left;
  }
  .index-overlay-img{
    float: right;
    height: 80px;
    width: 80px;
    margin-right: 20px;
    margin-top: 20px;
  }
  .index-overlay-img img{
    height: 50px;
    width: 50px;
    margin: 15px 15px;
  }
  .index-overlay-title{
    text-align: center;
    line-height: 50px;
    width: 100%;
    font-size: 40px;
  }
  .index-overlay-ul{
    width: 100%;
    float: left;
    margin-top: 300px;
  }
  .index-overlay-block{
    margin-top: 100px;
  }
  .index-overlay-kk{
    width: 680px;
    margin: auto;
  }
  .index-overlay-li{
    width: 340px;
    float: left;
  }
  .index-overlay-centre{
    width: 150px;
    margin: auto;
  }
  .overlay-centre-img{
    float: left;
    width: 150px;
    height: 150px;
    background-color: #ff7f7e;
    border-radius: 1000px;

  }
  .overlay-centre-img img{
    width: 90px;
    height: 90px;
    margin: 30px 30px;
  }
  .overlay-centre-title{
    float: left;
    width: 150px;
    text-align: center;
    font-size: 30px;
    line-height: 50px;
    margin-top: 20px;
  }
  .index-overlay-css{
    background-color: #FFFFFF !important;
  }
</style>
