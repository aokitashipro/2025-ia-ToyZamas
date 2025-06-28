<script setup>
import { ref, computed, onMounted } from 'vue'
import { apiClient } from '@/utils/api'
import { useRouter } from 'vue-router'

const loading = ref(true)
const error = ref(null)
const toys = ref([])
const sort = ref(null)

// ページネーション用
const currentPage = ref(1)
const perPage = 20 // 1ページあたりの表示数

const pagedToys = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return toys.value.slice(start, start + perPage)
})
const totalPages = computed(() => Math.ceil(toys.value.length / perPage))

async function getToys() {
  try {
    loading.value = true
    error.value = null
    const response = await apiClient.get('/owner/toys')
    toys.value = response.data
    currentPage.value = 1 // 取得時に1ページ目に戻す
  } catch (err) {
    console.log('商品情報の取得に失敗:', err)
    error.value = '商品情報の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

async function sortDo(sort) {
  try {
    loading.value = true
    error.value = null
    const postData = { sort }
    const response = await apiClient.post('/owner/toys/sort', postData)
    toys.value = response.data
    currentPage.value = 1 // ソート時も1ページ目に戻す
  } catch (err) {
    console.log('商品情報の取得に失敗:', err)
    error.value = '商品情報の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

onMounted(() => getToys())
</script>

<template>
  <div class="page-container">
    <h1>登録済み商品一覧</h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">
      エラー：{{ error }}
    </div>
    <div v-else>
      <RouterLink :to="'/owner/toys/create'">商品を新規登録</RouterLink>
        <div class="sort-row">
        <label for="sort-select">ソート:</label>
        <select id="sort-select" v-model="sort">
            <option value="price_high">値段の高い順</option>
            <option value="price_low">値段の低い順</option>
            <option value="stock_much">在庫多い順</option>
            <option value="stock_little">在庫少ない順</option>
        </select>
        <input type="button" @click="sortDo(sort)" value="ソートを実行">
        </div>
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">前へ</button>
        <span> {{ currentPage }} / {{ totalPages }} </span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">次へ</button>
      </div>
      <table border="1">
        <tr class="culm_name">
          <td>商品名</td>
          <td>値段</td>
          <td>カテゴリ名</td>
          <td>シリーズ名</td>
          <td>在庫</td>
          <td>販売状況</td>
          <td>予約可否</td>
        </tr>
        <tr v-for="toy in pagedToys" :key="toy.id">
          <td>
            <RouterLink :to="`/owner/toys/${toy.id}`">
              {{ toy.name }}
            </RouterLink>
          </td>
          <td>{{ toy.price }}</td>
          <td>{{ toy.category }}</td>
          <td>{{ toy.series }}</td>
          <td>{{ toy.stock }}</td>
          <td>{{ toy.is_selling }}</td>
          <td>{{ toy.is_reserve }}</td>
        </tr>
      </table>
      <!-- ページネーション -->
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">前へ</button>
        <span> {{ currentPage }} / {{ totalPages }} </span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">次へ</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.pagination {
  margin-top: 1em;
  text-align: center;
}
.pagination button {
  margin: 0 0.5em;
  padding: 0.3em 1em;
}
.sort-row {
  display: flex;
  align-items: center;
  gap: 1em;
  margin-bottom: 1em;
}
.sort-row label {
  margin-bottom: 0;
}
table {
  margin-left: auto;
  margin-right: auto;
  width: 90%; /* 必要に応じて調整 */
}
</style>