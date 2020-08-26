<template>
  <div class="newimage">
    <div class="newimage-centre">
      <div class="newimage-top-block">
        <van-nav-bar
          title="图片"
          left-text=""
          right-text="发布"
          left-arrow
          @click-left="onClickLeft"
          @click-right="onClickRight"
        />
      </div>
      <div class="newimage-image-block">
        <div class="newimage-image">
          <van-uploader v-model="fileList" @oversize="headoversize" max-size="52428800" @delete="aftedelete" :after-read="afterRead" multiple :max-count="1"/>
        </div>
      </div>
      <div class="newimage-nanme-block">
        <van-field
          v-model="name"
          rows="4"
          autosize
          label=""
          type="textarea"
          placeholder="请输入标题"
          show-word-limit
        />
      </div>

      <div class="newimage-cate-block">
        <div class="newimage-cate" @click="cateshow = true">
          <div class="newimage-cate-img"><img src="../../assets/img/label.png"></div>
          <div class="newimage-cate-title">{{ cate_val }}</div>
        </div>
      </div>
      <div class="newimage-edit-block">
        <quill-editor
            v-model="content"
            ref="myQuillEditor"
            :options="editorOption"
            @focus="onEditorFocus($event)"
            @blur="onEditorBlur($event)"
            @change="onEditorChange($event)">
        </quill-editor>
      </div>
      <div class="newimage-explain-block">
        <div class="newimage-explain">
          <p>发布小提示：</p>
          <p>1,发布图片时请选择分类，方便推荐也方便查找</p>
          <p>2,禁止发布违规内容，包括色情淫秽图片等等</p>
        </div>
      </div>
    </div>
    <van-action-sheet v-model="cateshow" title="">
      <van-picker show-toolbar cancel-button-text=" " @confirm="cateconfirm" title="分类" :columns="columns" />
    </van-action-sheet>
  </div>
</template>

<script>
export default {
    name: 'Newsarticle',
    data () {
      return {
        name:"",
        fileList: [],
        cate:0,
        cateshow:false,
        content:"",
        editorOption: {
          placeholder: '请输入正文......',
                      modules: {
                          toolbar:[
                            ['bold', 'italic', 'underline', 'strike'],    //加粗，斜体，下划线，删除线
                            ['blockquote', 'code-block'],     //引用，代码块


                            [{ 'header': 1 }, { 'header': 2 }],        // 标题，键值对的形式；1、2表示字体大小
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],     //列表
                            [{ 'script': 'sub'}, { 'script': 'super' }],   // 上下标
                            [{ 'indent': '-1'}, { 'indent': '+1' }],     // 缩进
                            [{ 'direction': 'rtl' }],             // 文本方向


                            [{ 'size': ['small', false, 'large', 'huge'] }], // 字体大小
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],     //几级标题


                            [{ 'color': [] }, { 'background': [] }],     // 字体颜色，字体背景颜色
                            [{ 'font': [] }],     //字体
                            [{ 'align': [] }],    //对齐方式

                            ['clean'],    //清除字体样式
                          ]
                       },
                       theme: 'snow'
                 },
        cate_val:"选择分类",
        columns: [
                {
                  text: '浙江',
                  children: [
                    {
                      text: '杭州',
                      children: [{ text: '西湖区' }, { text: '余杭区' }],
                    },
                    {
                      text: '温州',
                      children: [{ text: '鹿城区' }, { text: '瓯海区' }],
                    },
                  ],
                },
                {
                  text: '福建',
                  children: [
                    {
                      text: '福州',
                      children: [{ text: '鼓楼区' }, { text: '台江区' }],
                    },
                    {
                      text: '厦门',
                      children: [{ text: '思明区' }, { text: '海沧区' }],
                    },
                  ],
                },
              ],
      }
    },
    created(){
      var token = this.$store.state.token;
       this.$axios
               .post(this.$myStore.url+"/api/index/get_cate",this.$qs.stringify({
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
                       var resdata = data.data;
                       console.log(resdata);
                       this.columns = resdata.list;
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
    },
    methods:{
        onEditorReady(editor) { // 准备编辑器
        },
        onEditorBlur(){}, // 失去焦点事件
        onEditorFocus(){}, // 获得焦点事件
        onEditorChange(e){

        }, // 内容改变事件
        saveHtml:function(event){
          alert(this.content);
        },
        headoversize:function(item){
          this.$toast.fail('图片最大50M');
        },
      onClickLeft:function(event){
        this.$router.go(-1);
      },
      onClickRight:function(){

        //判断名称
        if(this.name == ""){
          this.$toast.fail('请填写名称');
          return;
        }
        //图片
        if(this.fileList.length <= 0){
          this.$toast.fail('请选择图片');
          return;
        }
        //分类
        if(this.cate == 0){
          this.$toast.fail('请选择分类');
          return;
        }
        var thiscate = this.cate;
        //处理图片
        if(this.content == ""){
          this.$toast.fail('请填写内容');
          return;
        }
        var thiscentre = this.content;
        var str = this.fileList[0]["imgname"];
         var token = this.$store.state.token;
          this.$axios
                  .post(this.$myStore.url+"/api/index/translate_articele",this.$qs.stringify({
                     token: token,
                     name:this.name,
                     image:str,
                     centre:thiscentre,
                     classify_id:thiscate
                   }))
                  .then(response=>{
                    var data = response.data;

                     //成功
                     if(response.status != 200){
                       this.$toast.fail('发布失败');
                     }else{
                       if(data.code == 1){
                          var resdata = data.data;
                          var suee = this.$toast.success('发布成功');
                          var that = this;
                            setTimeout(function(){
                              that.$router.push({ name:'Recommend'});
                            })
                       }else if(data.code == -2){
                         // this.$store.commit('settoken',"");
                         this.$router.push({ name:'Signin',query: { type:"out"}});
                       }
                        if(data.code == -1){
                          this.$toast.fail('发布失败');
                        }
                     }

                  })
                  .catch(error=>{
                    if(error.response){
                      this.$toast.fail('发布失败');
                    }
                 });



      },
      afterRead(item) {
        item.status = 'uploading';
        item.message = '上传中...';
        item.imgname = '';
        console.log(item);
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
               item.status = 'failed';
               item.message = '上传失败';
             }else{
               var resdata = data.data;
               if(data.code == 1){
                 var resdata = data.data;
                  item.status = 'done';
                  item.message = '上传成功';
                  item.imgname = resdata.name;
              }
              if(data.code == -1){
                item.status = 'failed';
                item.message = '上传失败';
              }
             }
          })
          .catch(error=>{
            if(error.response){
              item.status = 'failed';
              item.message = '上传失败';
            }
         });
          },
          aftedelete:function(item){

          },
          cateconfirm:function(item){
            console.log(item);
            this.cate_val = item[0]+"-"+item[1]+"-"+item[2];
            //循环获取id
            var list = this.columns;
            for(var i = 0;i<list.length;i++){
              if(list[i]["text"] == item[0]){
                var children1 = list[i]["children"];

                for(var a=0;a<children1.length;a++){
                  if(children1[a]["text"] == item[1]){
                    var children2 = children1[a]["children"];

                    for(var b=0;b<children2.length;b++){
                      if(children2[b]["text"] == item[2]){
                        this.cate = children2[b]["id"];
                      }
                    }

                  }
                }
              }
            }
            console.log(this.cate);
            this.cateshow = false;
          }
    },
  }
</script>

<style>
.newimage{
    width: 100%;
    height: 100%;
    position: relative;
    background-color: #f5f5f5;
  }
  .newimage-center{
    float: left;
    width: 100%;
  }
  .newimage-top-block{
    float: left;
    width: 100%;
    border-bottom: 2px solid #e0e0e0;
  }
  .newimage-nanme-block{
    float: left;
    width: 100%;
  }
  .newimage-image-block{
    float: left;
    width: 100%;
    margin-top: 30px;
  }
  .newimage-image{
    margin: auto;
    width: 150px;
  }
  .newimage-cate-block{
    float: left;
    width: 100%;
    height: 80px;margin-top: 20px;
  }
  .newimage-cate{
    width: 100%;
    height: 80px;
    background-color: #FFFFFF;
  }
  .newimage-cate-img{
    float: left;
    margin-left: 20px;
    height: 80px;
    width: 80px;
  }
  .newimage-cate-img img{
    width: 50px;
    height: 50px;
    margin: 15px;
  }
  .newimage-cate-title{
    line-height: 80px;
    font-size: 25px;
    float: left;
    color: #a7a7a7;
  }
  .newimage-explain-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
  .newimage-explain{
    margin: auto;
    width: 680px;
  }
  .newimage-explain p{
    color: #757575;
    line-height: 30px;
    font-size: 30px;
  }
  .newimage-edit-block{
    float: left;
    width: 100%;
    margin-top: 20px;
  }
</style>
