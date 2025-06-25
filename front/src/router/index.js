import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Login from '../views/user/LoginView.vue'
import Logout from '../views/user/LogoutView.vue'
import Register from '../views/user/RegisterView.vue'

//管理者側のルートimport
import OwnerTop from '../views/owner/TopView.vue'

//一般ユーザ側のルートimport
import ToyzamasTop from '../views/toyzamas/TopView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView, },
    { path: '/about', name: 'about', component: () => import('../views/AboutView.vue'),},

    //userディレクトリのルート設定
    { path: '/login', name: 'login', component: Login }, //ログイン用ルートの追加

    { path: '/logout', name: 'logout', component: Logout }, //ログアウト用ルートの追加
    
    { path: '/register', name: 'register', component: Register }, //登録用ルートの追加

    //Ownerディレクトリのルート設定
    { path: '/owner', name: 'OwnerTop', component: OwnerTop },

    //Toyzamasディレクトリのルート設定
    { path: '/toyzamas/:id/top', name:'ToyzamasTop', component: ToyzamasTop }
  ],
})

export default router
