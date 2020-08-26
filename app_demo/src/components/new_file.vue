<div class="homepage-centre-block" @click="clickcentre(item.id,item.type)">
               <div class="homepage-centre">
                 {{item.name}}
               </div>
               <!-- 丹徒 -->
               <div class="homepage-centre-img" v-if="item.type == 2">
                 <van-image class="homepage-centre-li-img" fit="cover" :src="item.image" />
               </div>

               <!-- 多图 -->
               <div class="homepage-all-img" v-if="item.type == 1 && item.imgcount != 4">
                 <div class="homepage-all-li" v-for="(value, name) in item.listimage">
                   <van-image class="all-li-img" fit="cover" :src="value" />
                   <div class="homepage-all-mumber" v-if="item.imgjiu == 1">
                     <div class="all-mumber-centre">
                       <div class="all-mumber-img"><img src="../assets/img/munmber.png"></div>
                       <div class="all-mumber-shu">{{item.imgjiucount}}</div>
                     </div>
                   </div>
                 </div>
               </div>

               <!--四张图片 -->
               <div class="homepage-si-img" v-if="item.type == 1 && item.imgcount == 4">
                 <div class="homepage-si-li" v-for="(value, name) in item.listimage">
                   <van-image class="si-all-li-img" fit="cover" :src="value" />
                 </div>
               </div>
             </div>