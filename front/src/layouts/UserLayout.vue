<template>
  <div class="layout-root">
    <Header v-if="isLoggedIn" />
    <main class="main-content">
      <div class="page-container">
        <slot />
      </div>
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
.page-container {
  max-width: 1200px;
  margin: 0 auto;
  padding-left: 2em;
  padding-right: 2em;
}
.main-content {
  flex: 1;
}
</style>