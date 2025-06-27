<template>
  <div>
    <Header v-if="isLoggedIn" />
    <main class="min-h-screen p-4 bg-gray-100">
      <slot />
    </main>
    <Footer v-if="isLoggedIn" />
  </div>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'

import { computed } from 'vue'
const isLoggedIn = computed(() => !!localStorage.getItem('token')) // ユーザー情報があればログインと判断
</script>
