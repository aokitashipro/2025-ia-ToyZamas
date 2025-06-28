<template>
  <div class="layout-root">
    <Header />
    <main class="main-content">
      <div class="page-container">
        <slot />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
</script>

<style scoped>
.layout-root {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.page-container {
  max-width: 420px;
  margin: 0 auto;
  padding: 2em 0;
}
.main-content {
  flex: 1;
}
</style>