<template>
  <div>
    <Header v-if="isAuthenticated" />
    <main class="container">
  
      <div v-if="!isAuthenticated">
        <h2>といザます</h2>
        <RouterLink to="/login">機能を使うにはログインが必要です。</RouterLink>
      </div>
          <router-view /> <!-- ページごとに切り替わる部分 -->
    </main>
    <Footer v-if="isAuthenticated" />
  </div>
</template>

<script setup>
import Header from './components/OwnerHeader.vue';
import Footer from './components/OwnerFooter.vue';
// import { computed } from 'vue';
// import { useAuthStore } from './stores/auth';//Piniaで定義した「認証情報管理ストア」を呼び出す
// const authStore = useAuthStore();
// const isAuthenticated = computed(() => authStore.isLoggedIn);
// 現在ログイン中かどうか（トークンがあるか）を返すgetter
</script>

<style scoped>
.container {
  padding: 20px;
}
</style>
