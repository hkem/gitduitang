<template>
  <div class="Classify">
    <div class="index-classify">
      <div class="index-classify-li" v-on:click="onswitch(0)">
          <div class="index-classify-center" v-bind:class="{ classify_on:onclassify == 0}">
            关注
            <div class="index-classify-select" v-if="onclassify == 0"></div>
          </div>
      </div>
      <div class="index-classify-li" v-on:click="onswitch(1)">
        <div class="index-classify-center" v-bind:class="{ classify_on:onclassify == 1}">
          推荐
          <div class="index-classify-select" v-if="onclassify == 1"></div>
        </div>
      </div>
      <div class="index-classify-li" v-on:click="onswitch(2)">
        <div class="index-classify-center" v-bind:class="{ classify_on:onclassify == 2}">
          热榜
          <div class="index-classify-select" v-if="onclassify == 2"></div>
        </div>
      </div>
      <div class="index-classify-li" v-on:click="onswitch(3)">
        <div class="index-classify-center" v-bind:class="{ classify_on:onclassify == 3}">
          少女感
          <div class="index-classify-select" v-if="onclassify == 3"></div>
        </div>
      </div>
      <div class="index-classify-li" v-on:click="onswitch(4)">
        <div class="index-classify-center" v-bind:class="{ classify_on:onclassify == 4}">
          新鲜
          <div class="index-classify-select" v-if="onclassify == 4"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Classify',
    data () {
      return {
        onclassify: 1
      }
    },
    components: {

    },
    created() {
      switch(this.$route.name)
      {
          case "Follow":
              this.onclassify = 0;
              break;
          case "Recommend":
              this.onclassify = 1;
              break;
          case "Hotlist":
              this.onclassify = 2;
              break;
          case "Girl":
              this.onclassify = 3;
              break;
          case "Fresh":
              this.onclassify = 4;
              break;
          default:
              this.$router.push({ name:'Recommend'});
              this.onclassify = 1;
              break;
      }
    },
    methods:{
      onswitch:function(event){
        switch(event)
        {
            case 0:
                this.$router.push({ name:'Follow'});
                this.onclassify = 0;
                break;
            case 1:
                this.$router.push({ name:'Recommend'});
                this.onclassify = 1;
                break;
            case 2:
                this.$router.push({ name:'Hotlist'});
                this.onclassify = 2;
                break;
            case 3:
                this.$router.push({ name:'Girl'});
                this.onclassify = 3;
                break;
            case 4:
                this.$router.push({ name:'Fresh'});
                this.onclassify = 4;
                break;
            default:
        }
      }
    }
  }
</script>

<style>

  .index-classify{
    width: 100%;
    height: 80px;
  }
  .index-classify-li{
    width: 20%;
    float: left;
  }
  .index-classify-center{
    width: fit-content;
    margin:0 auto;
    position: relative;
    height: 80px;
    font-size: 30px;
    color: #aaaaaa;
  }
  .index-classify-select{
    position: absolute;
    bottom: 0px;
    width: 100%;
    height: 5px;
    background-color: #ff5959;
  }
  .classify_on{
    color: #ff5959;
  }
  /* 内容 */
  .index-list{

    float: left;
    width: 100%;
    margin-top: 20px;
    padding-bottom: 150px;
  }
</style>
