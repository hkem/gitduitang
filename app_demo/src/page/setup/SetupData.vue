<template>
  <div class="setupdata">
    <div class="setupdata-centre">
      <div class="setupdata-top-block">
        <van-nav-bar
          title="编辑资料"
          left-text=""
          right-text=""
          left-arrow
          @click-left="onClickLeft"
        >
        </van-nav-bar>
      </div>
      <!-- 错误 -->
      <div class="recommend-error-block" v-if="isError">
        <Errora></Errora>
      </div>
      <div class="recommend-loading-block" v-if="request_Loading">
        <Loading></Loading>
      </div>
      <div v-if="isCentre">
        <div class="setupdata-cover-block">
          <div class="setupdata-cover-image" v-bind:style="cover_ba">
            <div class="setupdata-data-block">
              <div class="setupdata-data-li">
                <div class="setupdata-img-li">
                  <div class="setupdata-img">
                    <van-image class="setupdata-userimg" round fit="cover" v-bind:src="list.head" />
                  </div>
                </div>
                <div class="setupdata-title-li">{{list.nickname}}</div>
                <div class="setupdata-fensi-ul">
                    <div class="setupdata-follow"><van-uploader @oversize="headoversize" result-type="dataUrl" :after-read="headretun" max-size="10485760" accept="image/png, image/jpeg">修改头像</van-uploader></div>
                  <div class="setupdata-gege">
                  </div>
                  <div class="setupdata-fans"><van-uploader @oversize="headoversize" result-type="dataUrl" :after-read="coverretun" max-size="10485760" accept="image/png, image/jpeg">修改封面</van-uploader></div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="setupdata-means-block">
          <van-cell is-link v-on:click="editnickname">
            <template #title>
              <div class="setupdata-means-title">昵称</div>
              <div class="setupdata-means-name">{{list.nickname}}</div>
            </template>
          </van-cell>
          <van-cell is-link  v-on:click="editIntroduction">
            <template #title>
              <div class="setupdata-means-title">简介</div>
              <div class="setupdata-means-name">{{list.introduction}}</div>
            </template>
          </van-cell>
          <van-cell is-link @click="show = true">
            <template #title>
              <div class="setupdata-means-title">性别</div>
              <div class="setupdata-means-name">{{genderval}}</div>
            </template>
          </van-cell>
          <van-cell is-link @click="Areashow = true">
            <template #title>
              <div class="setupdata-means-title">地区</div>
              <div class="setupdata-means-name">{{list.region}}</div>
            </template>
          </van-cell>
          <van-cell is-link @click="birthdayshow = true">
            <template #title>
              <div class="setupdata-means-title">生日</div>
              <div class="setupdata-means-name">{{list.birthday}}</div>
            </template>
          </van-cell>
        </div>
      </div>
    </div>
    <van-action-sheet v-model="show" :actions="actions" @select="onSelect" close-on-click-action cancel-text="取消"/>
    <van-action-sheet v-model="Areashow" close-on-click-action cancel-text="取消">
      <van-area title="" v-bind:value="area_value" :area-list="areaList" cancel-button-text=" " @confirm="regionconfirm"/>
    </van-action-sheet>
    <van-action-sheet v-model="birthdayshow" close-on-click-action cancel-text="取消">
        <van-datetime-picker cancel-button-text=" " v-model="currentDate" type="date" @confirm="birthdayconfirm" title="选择年月日" :min-date="minDate" :max-date="maxDate"/>
    </van-action-sheet>
  </div>
</template>

<script>
    import Errora from '../../components/Errora.vue';
    import Loading from '../../components/Loading.vue';
    import aaadata from '../../assets/js/area.js';
  export default {
    name: 'SetupData',
    data () {
      return {
          list:{
            birthday: "1594201979",
            gender: "1",
            head: "",
            id: 10,
            introduction: "开心每一天",
            nickname: "哈哈哈",
            region: "",
            cover:"../../assets/img/cover.jpg"
          },
          cover_ba:{
            backgroundImage: "url(" + require("../../assets/img/cover.jpg") + ")",
            backgroundRepeat: "no-repeat",
            backgroundSize: "100% 100%",
          },
          isError:false,
          isCentre:false,
          request_Loading:true,
          show: false,
          actions: [{ name: '男' }, { name: '女' }],
          Areashow:false,
          areaList:aaadata,
          area_value:"",
          birthdayshow:false,
          minDate: new Date(1950, 1, 1),
          maxDate: new Date(2025, 10, 1),
          currentDate: new Date("1950-01-01"),
      }
    },
     created () {
       var token = this.$store.state.token;
        this.$axios
                .post(this.$myStore.url+"/api/token/get_data",this.$qs.stringify({
                   token: token,
                 }))
                .then(response=>{
                  var data = response.data;
                  this.request_Loading = false;
                  console.log("成功");
                  this.isError = false;
                   //成功
                   if(response.status != 200){
                     this.$toast.fail('获取失败');
                   }else{
                     if(data.code == 1){
                        this.isCentre = true;
                        var datalist = data.data.list;
                        console.log(datalist.head);
                        this.cover_ba.backgroundImage = "url(" + datalist.cover + ")";
                        this.list.head = datalist.head;
                        this.list.introduction = datalist.introduction;
                        this.list.region = datalist.region;
                        this.list.birthday = datalist.birthday;
                        this.list.nickname = datalist.nickname;
                        this.list.gender = datalist.gender;
                        //判断地区是否有三级
                        var region = datalist.region;
                        var regionarr = region.split("-");
                          var city_list = aaadata.city_list;
                          for(var key in city_list){
                            if(city_list[key] == regionarr[1]){
                              this.area_value = key;
                              var citykey = this.spite_str(key);
                            }
                          }
                          if(regionarr.length == 3){
                            var county_list = aaadata.county_list;
                            for(var key in county_list){
                              if(county_list[key] == regionarr[2]){

                               var countykey = this.spite_str(key);
                               if(countykey[1] == citykey[0]){
                                 this.area_value = key;
                               }
                              }
                            }
                          }
                          //处理生日
                          this.currentDate = new Date(datalist.birthday)
                     }
                      if(data.code == -1){
                        this.$toast.fail('退出失败');
                      }
                   }

                })
                .catch(error=>{
                  if(error.response){
                    this.isError = true;
                    this.request_Loading = false;
                  }


               });
               console.log(aaadata);


     },
      methods:{
        onClickLeft:function(event){
          this.$router.go(-1);
        },
        editnickname:function(event){
          this.$router.push({ name: 'Nickname', query: { nickname:this.list.nickname}})
        },
        editIntroduction:function(event){
          this.$router.push({ name: 'Introduction', query: { introduction:this.list.introduction}})
        },
        onSelect:function(item) {
              this.show = false;
              var gender = item.name == "男" ? 1 : 2;
              this.submit_form(3,gender);
        },
        regionconfirm:function(item){

          if(item[2]){
            var region = item[0]["name"]+"-"+item[1]["name"]+"-"+item[2]["name"];
          }else{
            var region = item[0]["name"]+"-"+item[1]["name"]
          }
          this.submit_form(4,region);
        },
        birthdayconfirm:function(item){

          var year = item.getFullYear();
          var month = item.getMonth() + 1;
          var day = item.getDate();
          var birthday = year+"-"+month+"-"+day;
          this.submit_form(5,birthday);
        },
        submit_form(type,val){
          var token = this.$store.state.token;
          this.$axios
                  .post(this.$myStore.url+"/api/token/update_data",this.$qs.stringify({
                     token: token,
                     type:type,
                     val:val
                   }))
                  .then(response=>{
                    var data = response.data;
                     //成功
                     if(response.status != 200){
                       this.$toast.fail('更改失败');
                     }else{
                       if(data.code == 1){
                          this.$toast.success('更改成功');
                          if(type == 3){
                            this.list.gender = val;
                          }
                          if(type == 4){
                            this.list.region = val;
                            this.Areashow = false;
                          }
                          if(type == 5){
                            this.list.birthday = val;
                            this.birthdayshow = false;
                          }
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
        },
        spite_str(str){
          var str = String(str).split('');
          return [str[0]+str[1],str[2]+str[3],str[4]+str[5]];
        },
        headoversize:function(item){
          this.$toast.fail('图片最大10M');
        },
        headretun:function(item){
          var file = item.file;
          var token = this.$store.state.token;
          var param = new FormData();  // 创建form对象
          param.append('file', file)  // 通过append向form对象添加数据
          param.append('token',token) // 添加form表单中其他数据
          var config = {
            headers: {'Content-Type': 'multipart/form-data'}
          }
          this.$axios
            .post(this.$myStore.url+"/api/image/upload_image",param,config)
            .then(response=>{
              var data = response.data;
              console.log(data);
               //成功
               if(response.status != 200){
                 this.$toast.fail('上传失败');
               }else{
                 var resdata = data.data;
                 if(data.code == 1){
                    this.submit_form(6,resdata.name);
                    this.list.head = resdata.url_name;
                }
                if(data.code == -1){
                  this.$toast.fail(data.msg);
                }
               }

            })
            .catch(error=>{
              if(error.response){
                this.$toast.fail('上传失败');
              }
           });
        },
        coverretun:function(item){
          var file = item.file;
          var token = this.$store.state.token;
          var param = new FormData();  // 创建form对象
          param.append('file', file)  // 通过append向form对象添加数据
          param.append('token',token) // 添加form表单中其他数据
          var config = {
            headers: {'Content-Type': 'multipart/form-data'}
          }
          this.$axios
            .post(this.$myStore.url+"/api/image/upload_image",param,config)
            .then(response=>{
              var data = response.data;
              console.log(data);
               //成功
               if(response.status != 200){
                 this.$toast.fail('上传失败');
               }else{
                 var resdata = data.data;
                 if(data.code == 1){
                    this.submit_form(7,resdata.name);
                    console.log(resdata.url_name);
                    this.cover_ba.backgroundImage = "url(" + resdata.url_name + ")";
                }
                if(data.code == -1){
                  this.$toast.fail(data.msg);
                }
               }

            })
            .catch(error=>{
              if(error.response){
                this.$toast.fail('上传失败');
              }
           });
        }


      },
      components: {
          Errora,
          Loading
      },
      computed:{
        genderval:function(){
          return this.list.gender == 1 ? "男" : "女";
        },
        areareset:function(){
        },
      },
  }
</script>

<style>
  .setupdata{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .setupdata-center{
    float: left;
    width: 100%;
  }
  .setupdata-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .setupdata-cover-block{
    float: left;
    width: 100%;
    height: 600px;
  }
  .setupdata-cover-image{
    width: 100%;
    height: 600px;
    position: relative;
  }
  .setupdata-data-block{
    position: absolute;
    margin-top: 200px;
    width: 100%;
  }
  .setupdata-data-li{
    width: 680px;
    margin: auto;
  }
  .setupdata-img-li{
    float: left;
    width: 100%;
    height: 150px;
  }
  .setupdata-img{
    width: 150px;
    height: 150px;
    margin-left: 265px;
    float: left;
    border-radius: 10000px;
    border: 2px solid #FFFFFF;
  }
  .setupdata-img .setupdata-userimg{
    width: 150px;
    height: 150px;
    border-radius: 10000px;
  }
  .setupdata-title-li{

    width: 680px;
    margin: auto;
    font-size: 35px;
    color: #FFFFFF;
    line-height: 100px;
    text-align: center;
  }
  .setupdata-fensi-ul{
    float: left;
    width: 680px;
    margin: auto;
  }
  .setupdata-follow{
    width: 300px;
    text-align: right;
    color: #FFFFFF;
    font-size: 25px;
    float: left;
  }
  .setupdata-gege{
    float: left;
    width: 80px;
    height: 50px;
  }
  .setupdata-fans{
    width: 300px;
    text-align: left;
    color: #FFFFFF;
    font-size: 25px;
    float: left;
  }
  .setupdata-means-block{
    float: left;
    width: 100%;
  }
  .setupdata-means-title{
    color: #aaaaaa;
    font-size: 30px;
    float: left;
    margin-left: 20px;
  }
  .setupdata-means-name{
    color: #000000;
    font-size: 30px;
    float: left;
    margin-left: 30px;
    width: 500px;
  }
</style>
