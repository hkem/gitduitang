import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/page/index'
import Worth from '@/page/worth'
import My from '@/page/my'
import Find from '@/page/find'
import Follow from '@/components/Follow.vue'
import Recommend from '@/components/Recommend.vue'
import Hotlist from '@/components/Hotlist.vue'
import Girl from '@/components/Girl.vue'
import Fresh from '@/components/Fresh.vue'
import Notloggedin from '@/page/login/Notloggedin.vue'
import Signin from '@/page/login/Signin.vue'
import Register from '@/page/login/Register.vue'
import Setup from '@/page/setup/Setup.vue'
import SetupData from '@/page/setup/SetupData.vue'
import Homepage from '@/page/setup/Homepage.vue'
import Nickname from '@/page/setup/Nickname.vue'
import Introduction from '@/page/setup/Introduction.vue'
import Newimage from '@/page/release/Newimage.vue'
import Newsarticle from '@/page/release/Newsarticle.vue'
import Articledateils from '@/components/Articledateils.vue'
import Goodsdateils from '@/components/Goodsdateils.vue'
import Allcomment from '@/components/Allcomment.vue'
import Grasslist from '@/components/Grasslist.vue'
import Catelist from '@/components/Catelist.vue'
import Goodslist from '@/components/Goodslist.vue'
import Grassdatelis from '@/components/Grassdatelis.vue'
import Articlelist from '@/components/Articlelist.vue'
import Mycollection from '@/components/Mycollection.vue'
import Articlewen from '@/components/Articlewen.vue'
import Articleimg from '@/components/Articleimg.vue'
import Myfollow from '@/components/Myfollow.vue'
import Mynews from '@/components/Mynews.vue'
import Search from '@/components/Search.vue'
import Articlesearchlist from '@/components/Articlesearchlist.vue'
import Goodslistsear from '@/components/Goodslistsear.vue'
import Searchgoods from '@/components/Searchgoods.vue'
import Goodsexplain from '@/components/Goodsexplain.vue'
import Aboutus from '@/components/Aboutus.vue'
import Task from '@/components/Task.vue'
import Taskrecord from '@/components/Taskrecord.vue'
import Strategy from '@/components/Strategy.vue'
import Luckdraw from '@/components/Luckdraw.vue'
import Prizerecord from '@/components/Prizerecord.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Index',
      component: Index,
      children: [
        {
          path: 'follow',
          name:'Follow',
          component:Follow
        },
        {
          path: 'recommend',
          name:'Recommend',
          component:Recommend
        },
        {
          path: 'hotlist',
          name:'Hotlist',
          component:Hotlist
        },
        {
          path: 'girl',
          name:'Girl',
          component:Girl
        },
        {
          path: 'fresh',
          name:'Fresh',
          component:Fresh
        },
      ]
    },
    {
      path: '/find',
      name: 'Find',
      component: Find
    },
    {
      path: '/worth',
      name: 'Worth',
      component: Worth
    },
    {
      path: '/my',
      name: 'My',
      component: My
    },

    {
      path: '/notloggedin',
      name: 'Notloggedin',
      component: Notloggedin
    },
    {
      path: '/signin',
      name: 'Signin',
      component: Signin
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/setup',
      name: 'Setup',
      component: Setup
    },
    {
      path: '/setupdata',
      name: 'SetupData',
      component: SetupData
    },
    {
      path: '/homepage',
      name: 'Homepage',
      component: Homepage
    },
    {
      path: '/nickname',
      name: 'Nickname',
      component: Nickname
    },
    {
      path: '/introduction',
      name: 'Introduction',
      component: Introduction
    },
    {
      path: '/newimage',
      name: 'Newimage',
      component: Newimage
    },
    {
      path: '/newsarticle',
      name: 'Newsarticle',
      component: Newsarticle
    },
    {
      path: '/articledateils',
      name: 'Articledateils',
      component: Articledateils
    },
    {
      path: '/goodsdateils',
      name: 'Goodsdateils',
      component: Goodsdateils
    },
    {
      path: '/allcomment',
      name: 'Allcomment',
      component: Allcomment
    },
    {
      path: '/grasslist',
      name: 'Grasslist',
      component: Grasslist
    },
    {
      path: '/catelist',
      name: 'Catelist',
      component: Catelist
    },
    {
      path: '/goodslist',
      name: 'Goodslist',
      component: Goodslist
    },
    {
      path: '/grassdatelis',
      name: 'Grassdatelis',
      component: Grassdatelis
    },
    {
      path: '/articlelist',
      name: 'Articlelist',
      component: Articlelist
    },
    {
      path: '/mycollection',
      name: 'Mycollection',
      component: Mycollection
    },
    {
      path: '/articlewen',
      name: 'Articlewen',
      component: Articlewen
    },
    {
      path: '/articleimg',
      name: 'Articleimg',
      component: Articleimg
    },
    {
      path: '/myfollow',
      name: 'Myfollow',
      component: Myfollow
    },
    {
      path: '/mynews',
      name: 'Mynews',
      component: Mynews
    },
    {
      path: '/search',
      name: 'Search',
      component: Search
    },
    {
      path: '/articlesearchlist',
      name: 'Articlesearchlist',
      component: Articlesearchlist
    },
    {
      path: '/searchgoods',
      name: 'Searchgoods',
      component: Searchgoods
    },
    {
      path: '/goodslistsear',
      name: 'Goodslistsear',
      component: Goodslistsear
    },
    {
      path: '/goodsexplain',
      name: 'Goodsexplain',
      component: Goodsexplain
    },
    {
      path: '/aboutus',
      name: 'Aboutus',
      component: Aboutus
    },
    {
      path: '/task',
      name: 'Task',
      component: Task
    },
    {
      path: '/taskrecord',
      name: 'Taskrecord',
      component: Taskrecord
    },
    {
      path: '/strategy',
      name: 'Strategy',
      component: Strategy
    },
    {
      path: '/luckdraw',
      name: 'Luckdraw',
      component: Luckdraw
    },
    {
      path: '/prizerecord',
      name: 'Prizerecord',
      component: Prizerecord
    },


  ]
})


const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}
