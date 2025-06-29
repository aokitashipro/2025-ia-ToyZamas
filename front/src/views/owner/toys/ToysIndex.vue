<script setup>
import { ref, computed, onMounted } from 'vue'
import { apiClient } from '@/utils/api'
import { useRouter } from 'vue-router'

const loading = ref(true)
const error = ref(null)
const toys = ref([])
const categories = ref([])
const series = ref([])

const sort = ref(null)
const category_filter = ref(null)
const series_filter = ref(null)

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

    //初期化
    sort.value = null
    category_filter.value = null
    series_filter.value = null

    //すべてのtoyを取得
    const response = await apiClient.get('/owner/toys')
    toys.value = response.data

    //すべてのカテゴリを取得
    const response_categories = await apiClient.get('/owner/categories')
    categories.value = response_categories.data

    //すべてのseriesを取得
    const response_series = await apiClient.get('/owner/series')
    series.value = response_series.data

    currentPage.value = 1 // 取得時に1ページ目に戻す
  } catch (err) {
    console.log('商品情報の取得に失敗:', err)
    error.value = '商品情報の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

async function sortDo(sort, category_filter, series_filter) {
  try {
    loading.value = true
    error.value = null
    const postData = { sort, category_filter, series_filter}
    console.log(postData)
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

onMounted(() => 
  getToys()
)
</script>

<template>
  <h1>登録済み商品一覧</h1>
  <div class="page-container">
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
        <input type="button" @click="sortDo(sort, category_filter, series_filter)" value="ソートを実行">
      </div>
      <div class="filter_row">
        <div>絞り込み:
          <div class="category_filter_row">
            <label for="categories_filter">カテゴリ名:</label> 
            <select id="categories_filter" v-model="category_filter" class="category_box">
              <option v-for="category in categories" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div class="series_filter_row">
            <label for="series_filter">シリーズ名:</label>
            <select id="series_filter" v-model="series_filter" class="series_box">
              <option v-for="a_series in series" :value="a_series.id">
                {{ a_series.name }}
              </option>
            </select>
            <input type="button" @click="sortDo(sort, category_filter, series_filter)" value="絞り込みを実行">
          </div>
        </div>
      </div>
      <div>
        <input type="button" @click="getToys()" value="ソート&絞り込み リセット">
      </div>
      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">前へ</button>
        <span> {{ currentPage }} / {{ totalPages }} </span>
        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">次へ</button>
      </div>
      <table border="1">
        <tr class="culm_name">
          <td align="center" class="title">商品名</td>
          <td align="center" class="title">値段</td>
          <td align="center" class="title">カテゴリ名</td>
          <td align="center" class="title">シリーズ名</td>
          <td align="center" class="title">在庫</td>
          <td align="center" class="title">販売状況</td>
          <td align="center" class="title">予約可否</td>
        </tr>
        <tr v-for="toy in pagedToys" :key="toy.id">
          <td align="center">
            <RouterLink :to="`/owner/toys/${toy.id}`">
              {{ toy.name }}
            </RouterLink>
          </td>
          <td align="center">{{ toy.price }}</td>
          <td align="center">{{ toy.category }}</td>
          <td align="center">{{ toy.series }}</td>
          <td align="center">{{ toy.stock }}</td>
          <td align="center">{{ toy.is_selling }}</td>
          <td align="center">{{ toy.is_reserve }}</td>
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

.filter_row{
  margin-bottom: 1em;
}
.category_filter_row{
  margin-left: 20px;
  margin-top: 6px;
  margin-bottom: 2px;
}
.category_box{
  margin-left: 16px;
}
.series_filter_row{
  margin-left: 20px;
}
.series_box{
  width: 154px;
  margin-left: 16px;
  margin-right: 16px;
}
.title{
  background-color: rgb(172, 223, 255);
}
</style>