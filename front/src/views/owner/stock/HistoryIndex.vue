<template>
  <div>
    <h1><RouterLink :to="`/owner/stocks`">在庫管理</RouterLink> / 在庫履歴</h1>
    <div v-if="loading">読み込み中...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
          <tr>
            <th>日時</th>
            <th>おもちゃ名</th>
            <th>在庫数</th>
            <th>入荷数</th>
            <th>出庫数</th>
            <th>備考</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="history in histories" :key="history.id">
            {{ history }}
            <td>{{ history.created_at }}</td>
            <td>{{ history.name }}</td>
            <td>{{ history.be_stored }}</td>
            <td>{{ history.stock_in }}</td>
            <td>{{ history.stock_out }}</td>
            <td>{{ history.description }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const histories = ref([])
const loading = ref(true)
const error = ref(null)

const fetchHistories = async () => {
  try {
    loading.value = true
    error.value = null
    const res = await apiClient.get('/owner/history')
    histories.value = Array.isArray(res.data) ? res.data : res.data.data
  } catch (err) {
    error.value = '在庫履歴の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchHistories()
})
</script>