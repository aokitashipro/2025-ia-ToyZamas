<template>
  <footer class="footer">
    <nav v-if="isLoginRoute" class="mt-2 space-x-4">
    </nav>
    <nav v-else-if="!isOwnerRoute" class="mt-2 space-x-4">
      <router-link to="/toyzamas/toys">商品一覧</router-link>
      <router-link to="/toyzamas/favorites">お気に入り</router-link>
      <router-link to="/toyzamas/cart">カート</router-link>
    </nav>
    <div class="footer-row" v-if="isLoginRoute">
      <img src="@/assets/logo_toyzamas_frame.svg" alt="ToyZamasロゴ" class="footer-logo" />
      <p class="footer-copy">&copy; 2025 ToyZamas ログイン画面</p>
    </div>
    <div class="footer-row" v-else-if="!isOwnerRoute">
      <img src="@/assets/logo_toyzamas_frame.svg" alt="ToyZamasロゴ" class="footer-logo" />
      <p class="footer-copy">&copy; 2025 ToyZamas ECサイト - All rights reserved.</p>
    </div>
    <div v-else>
      <img src="@/assets/logo_toyzamas_frame.svg" alt="ToyZamasロゴ" class="footer-logo" />
      <p>2025 ToyZamas 管理画面</p>
    </div>
  </footer>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()

const isOwnerRoute = computed(() => {
  return route.path.startsWith('/owner')
})
const isLoginRoute = computed(() => {
  return route.path === '/login' || route.path === '/logout' || route.path === '/register'
})
</script>

<style scoped>
.footer {
  background-color: #338fe5;
  color: white;
  text-align: center;
  padding: 1em;
  margin-left: calc(50% - 50vw);
  box-sizing: border-box;
  margin-top: 4em;
}
.footer-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1em;
}
.footer-logo {
  height: 40px;
  margin-bottom: 0;
}
.footer-copy {
  margin: 0;
}
a {
  text-decoration: none;
  color: white;
}
a.router-link-active {
  font-weight: bold;
  border-bottom: 2px solid white;
}
</style>
