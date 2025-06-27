<template>
  <div>
    <h1>在庫管理</h1>
    <div v-if="loading">読み込み中...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
          <tr>
            <th>おもちゃ名</th>
            <th>在庫数</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="toy in stocks" :key="toy.id">
            <td>
            <a :href="`/owner/stocks/${toy.id}`">{{ toy.name }}</a>
            </td>
            <td>{{ toy.stock }}</td>
          </tr>
        </tbody>
      </table>
      <!-- ページ送りUIを削除 -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const stocks = ref([])
const loading = ref(true)
const error = ref(null)

const fetchStocks = async () => {
  try {
    loading.value = true
    error.value = null
    const res = await apiClient.get('/owner/stocks')
    stocks.value = Array.isArray(res.data) ? res.data : res.data.data
  } catch (err) {
    error.value = '在庫情報の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchStocks()
})
</script>