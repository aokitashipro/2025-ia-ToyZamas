<template>
      <component :is="layoutComponent">
        <router-view /> <!-- ページごとに切り替わる部分 -->
      </component>
</template>

<script setup>
// import Header from './components/Header.vue';
// import Footer from './components/Footer.vue';
import OwnerLayout from '@/layouts/OwnerLayout.vue'
import UserLayout from '@/layouts/UserLayout.vue'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()


// ルートのmeta情報に応じてレイアウトを切り替え
const layoutComponent = computed(() => {
  const layout = route.meta.layout
  if (layout === 'owner') return OwnerLayout
  return UserLayout
})
</script>

<style scoped>
.container {
  padding: 20px;
}
</style>
