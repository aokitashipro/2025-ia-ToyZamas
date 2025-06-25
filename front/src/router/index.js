import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Login from '../views/user/LoginView.vue'
import Logout from '../views/user/LogoutView.vue'
import Register from '../views/user/RegisterView.vue'
<<<<<<< HEAD
import ToysIndex from '../views/toyzamas/toys/IndexView.vue'
=======

//管理者側のルートimport
import OwnerTop from '../views/owner/TopView.vue'

//一般ユーザ側のルートimport
import ToyzamasTop from '../views/toyzamas/TopView.vue'
>>>>>>> fb3fa018f3082f102dfa97ad0c41065f1cedce84

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/', name: 'home', component: HomeView,
    },
    {
      path: '/about', name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },

    //userディレクトリのルート設定
    {
      //ログイン用ルートの追加
      path: '/login', name: 'login', component: Login
    },
    {
      //ログアウト用ルートの追加
      path: '/logout', name: 'logout', component: Logout
    },
    {
      //登録用ルートの追加
      path: '/register', name: 'register', component: Register
    },
<<<<<<< HEAD
    //ユーザー側 ========================================================
    {
      //登録用ルートの追加
      path: '/toyzamas/:user_id/toys', name: 'toysIndex', component:ToysIndex
=======

    //Ownerディレクトリのルート設定
    {
      path: '/owner', name: 'OwnerTop', component: OwnerTop
>>>>>>> fb3fa018f3082f102dfa97ad0c41065f1cedce84
    },

    //Toyzamasディレクトリのルート設定
    {
      path: '/toyzamas/:id/top', name:'ToyzamasTop', component: ToyzamasTop
    }
  ],
})

export default router
