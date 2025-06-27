<template>
  <!-- <footer class="footer"> -->
     <footer class="bg-gray-100 text-center p-4 mt-10 text-sm text-gray-600">
       <div v-if="isOwnerRoute">
      <p> 2025 ToyZamas 管理画面</p>
    </div>
    <div v-else>
      <p>&copy; 2025 ToyZamas ECサイト - All rights reserved.</p>
      <nav class="mt-2 space-x-4">
        <router-link to="/toyzamas/toys">商品一覧</router-link>
        <router-link to="/toyzamas/favorites">お気に入り</router-link>
        <router-link to="/toyzamas/cart">カート</router-link>
      </nav>
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
</script>

<style scoped>
.footer {
  background-color: #d03737;
  padding: 1em;
  text-align: center;
  margin-top: 2em;
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
