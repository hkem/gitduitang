<template>
  <div class="nav-bottom">
    <div class="nav-bottom_back">
      <div class="nav-bottom_centre" v-bind:class="{ choice_class: choice == 0 }" v-on:click="nav_click(0)">
        <img v-if="choice == 0" src="../assets/img/choice_index.png"/>
        <img v-if="choice != 0" src="../assets/img/index.png"/>
        <div class="nav-bottom_title">首页</div>
      </div>
    </div>
    <div class="nav-bottom_back">
      <div class="nav-bottom_centre" v-bind:class="{ choice_class: choice == 1 }" v-on:click="nav_click(1)">
        <img v-if="choice == 1" src="../assets/img/choice_find.png"/>
        <img v-if="choice != 1" src="../assets/img/find.png"/>
        <div class="nav-bottom_title">发现</div>
      </div>
    </div>
    <div class="nav-bottom_back">
      <div class="nav-bottom_centre" v-bind:class="{ choice_class: choice == 2 }" v-on:click="nav_click(2)">
        <img v-if="choice == 2" src="../assets/img/choice_worth.png"/>
        <img v-if="choice != 2" src="../assets/img/worth.png"/>
        <div class="nav-bottom_title">值得</div>
      </div>
    </div>
    <div class="nav-bottom_back">
      <div class="nav-bottom_centre" v-bind:class="{ choice_class: choice == 3 }" v-on:click="nav_click(3)">
        <img v-if="choice == 3" src="../assets/img/choice_my.png"/>
        <img v-if="choice != 3" src="../assets/img/my.png"/>
        <div class="nav-bottom_title">我的</div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'BottomNavigation',
    data () {
      return {
        choice: 0
      }
    },
    created() {
      switch(this.$route.name)
      {
          case "Index":
              this.choice = 0;
              break;
          case "Find":
              this.choice = 1;
              break;
          case "Worth":
              this.choice = 2;
              break;
          case "My":
              this.choice = 3;
              break;
          default:
      }
    },
    methods:{
      nav_click:function(event){
        switch(event)
        {
            case 0:
                this.$router.push({ name:'Recommend'});
                break;
            case 1:
                this.$router.push({ name:'Find'});
                break;
            case 2:
                this.$router.push({ name:'Worth'});
                break;
            case 3:
                this.$router.push({ name:'My'});
                break;
            default:
        }
      }
    }
  }

</script>

<style>
  .nav-bottom{
    height: 100px;
    background-color: #ffffff;
  }
  .nav-bottom_back{
    float: left;
    width: 25%;
    height: 100px;
  }
  .nav-bottom_centre{
    width: 80px;
    height: 100px;
    margin: auto;
    color: #cdcdcd;
  }
  .nav-bottom_centre img{
    margin: 5px 20px;
    width: 45px;
    height: 45px;
  }
  .nav-bottom_title{
    width: 80px;
    height: 10px;
    font-size: 25px;
    text-align: center;
  }
  .choice_class{
    color: #ff5959;
  }
</style>
