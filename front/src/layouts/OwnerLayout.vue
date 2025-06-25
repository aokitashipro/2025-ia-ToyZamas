<template>
  <div>
    <Header />
    <main class="min-h-screen p-4 bg-gray-100">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
</script>
