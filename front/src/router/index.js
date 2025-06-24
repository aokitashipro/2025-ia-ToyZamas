import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Login from '../views/user/LoginView.vue'
import Logout from '../views/user/LogoutView.vue'
import Register from '../views/user/RegisterView.vue'
import Top from '../views/owner/TopView.vue'

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
      path: '/register', name: 'register', component:Register
    },
    {
      path: '/top', name: 'top', component: Top
    },
  ],
})

export default router
