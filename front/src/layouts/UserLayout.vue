<template>
  <div class="layout-root">
    <Header v-if="isLoggedIn" />
    <main class="main-content">
      <slot />
    </main>
    <Footer v-if="isLoggedIn" />
  </div>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import { computed } from 'vue'
const isLoggedIn = computed(() => !!localStorage.getItem('token'))
</script>

<style scoped>
.layout-root {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.main-content {
  flex: 1;
}
</style>