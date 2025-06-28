<template>
  <div>
    <h1>
      <RouterLink :to="`/owner/stocks`">在庫管理</RouterLink>
      /
      <span class="active-tab">在庫履歴</span>
    </h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">前へ</button>
        <span> {{ currentPage }} / {{ totalPages }} </span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">次へ</button>
      </div>
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
          <tr v-for="history in pagedHistories" :key="history.id">
            <td>{{ history.created_at }}</td>
            <td>{{ history.name }}</td>
            <td>{{ history.be_stored }}</td>
            <td>{{ history.stock_in }}</td>
            <td>{{ history.stock_out }}</td>
            <td>{{ history.description }}</td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">前へ</button>
        <span> {{ currentPage }} / {{ totalPages }} </span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">次へ</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { apiClient } from '@/utils/api.js'

const histories = ref([])
const loading = ref(true)
const error = ref(null)

// ページネーション用
const currentPage = ref(1)
const perPage = 20 // 1ページあたりの表示数

const pagedHistories = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return histories.value.slice(start, start + perPage)
})
const totalPages = computed(() => Math.ceil(histories.value.length / perPage))

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

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

onMounted(() => {
  fetchHistories()
})
</script>

<style scoped>
.active-tab {
  border-bottom: 3px solid #338fe5;
  font-weight: bold;
  padding-bottom: 2px;
}
table {
  margin-top: 2em;
}
.pagination {
  margin: 1em 0;
  text-align: center;
}
.pagination button {
  margin: 0 0.5em;
  padding: 0.3em 1em;
}
</style>