<template>
  <div>
    <h1>
      <span class="active-tab">在庫管理</span>
      /
      <RouterLink :to="`/owner/stocks/history`">在庫履歴</RouterLink>
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
      <table border="1" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th>おもちゃ名</th>
            <th>在庫数</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="toy in pagedStocks" :key="toy.id">
            <td>
              <RouterLink :to="`/owner/stocks/${toy.id}`">{{ toy.name }}</RouterLink>
            </td>
            <td>{{ toy.stock }}</td>
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

const stocks = ref([])
const loading = ref(true)
const error = ref(null)

// ページネーション用
const currentPage = ref(1)
const perPage = 20 // 1ページあたりの表示数

const pagedStocks = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return stocks.value.slice(start, start + perPage)
})
const totalPages = computed(() => Math.ceil(stocks.value.length / perPage))

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

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

onMounted(() => {
  fetchStocks()
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
  width: 500px;
  max-width: 100%;
  margin-left: auto;
  margin-right: auto;
  border-collapse: collapse;
  table-layout: fixed; /* 列幅を固定 */
}
th, td {
  padding: 8px;
  border: 1px solid #ccc;
}
th:nth-child(1), td:nth-child(1) {
  width: 350px; /* おもちゃ名の列幅 */
}
th:nth-child(2), td:nth-child(2) {
  width: 150px; /* 在庫数の列幅 */
  text-align: right;
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