<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'

const router = useRouter()

const logout = async () => {
  try {
    await apiClient.post('/logout', {})
  } catch (e) {
    // エラー時も続行
    console.error('ログアウトAPIエラー:', e)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}

onMounted(() => {
  logout()
})
</script>

<template>
  <div>
    ログアウト中...
  </div>
</template>