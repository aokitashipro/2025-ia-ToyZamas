<template>
  <div>
    <AdminHeader />
    <main class="p-4">
      <router-view />
    </main>
    <AdminFooter />
  </div>
</template>

<script setup>
import AdminHeader from '@/components/AdminHeader.vue'
import AdminFooter from '@/components/AdminFooter.vue'
</script>