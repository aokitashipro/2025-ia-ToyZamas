<template>
  <div class="toy-list-header">
    <h1>商品一覧</h1>
    <form @submit.prevent="loadToys" class="sort-form">
      <label class="switch-label">
        <input type="checkbox" v-model="isReserveOnly" />
        <span class="switch-slider"></span>
        予約対象商品一覧
      </label>
      <span class="select-group">
        カテゴリ名：
        <select v-model="category_id">
          <option v-for="category in categories" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </span>
      <span class="select-group">
        シリーズ名：
        <select v-model="series_id">
          <option v-for="a_series in series" :value="a_series.id">
            {{ a_series.name }}
          </option>
        </select>
      </span>
      <button type="submit">ソート実行</button>
      <a href="/toyzamas/toys" class="reset-link">ソートリセット</a>
    </form>
  </div>
  <div class="toy-list-container">
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else>
      <div class="pagination" v-if="totalPages > 1">
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="pagination-button"
        >
          前へ
        </button>
        <span class="current-page">{{ currentPage }} / {{ totalPages }}</span>
        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="pagination-button"
        >
          次へ
        </button>
      </div>
      <div class="toy-grid">
        <router-link
          v-for="toy in pagedToys"
          :key="toy.id"
          :to="`/toyzamas/toys/${toy.id}`"
          class="toy-card-link"
        >
          <div class="toy-card">
            <img
              v-if="toy.image_url"
              :src="laravel_base_url + toy.image_url"
              alt="商品画像"
              class="toy-image"
            />
            <div v-else class="no-image">画像がありません</div>
            <div class="toy-info">
              <p><strong>{{ toy.name }}</strong></p>
              <p>価格: ¥{{ toy.price }}</p>
            </div>
          </div>
        </router-link>
      </div>
      <div class="pagination" v-if="totalPages > 1">
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="pagination-button"
        >
          前へ
        </button>
        <span class="current-page">{{ currentPage }} / {{ totalPages }}</span>
        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="pagination-button"
        >
          次へ
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { apiClient } from '@/utils/api.js'

const laravel_base_url = 'http://127.0.0.1:8000/storage/'

const toys = ref([])
const sort = ref(0)
const categories = ref([])
const category_id = ref(0)
const series = ref([])
const series_id = ref(0)
const loading = ref(false)
const isReserveOnly = ref(false)
const currentPage = ref(1)
const perPage = 12 // 1ページあたりの商品数（お好みで変更）

const pagedToys = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return toys.value.slice(start, start + perPage)
})

const totalPages = computed(() => {
  return Math.ceil(toys.value.length / perPage)
})

function goToPage(page) {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}

// isReserveOnlyが変わったらsortも連動させる
watch(isReserveOnly, (val) => {
  sort.value = val ? 1 : 0
})

const loadToys = async () => {
  loading.value = true
  try {
    const response = await apiClient.get(
      `/toyzamas/toys?sort=${sort.value}&category_id=${category_id.value}&series_id=${series_id.value}`
    )
    toys.value = response.data
  } catch (error) {
    console.error("データの取得に失敗:", error)
  } finally {
    loading.value = false
  }
}

async function getCategories() {
  try {
    const response_categories = await apiClient.get('/owner/categories')
    categories.value = response_categories.data
  } catch (err) {
    console.log('カテゴリ名の取得に失敗:', err)
  }
}

async function getSeries() {
  try {
    const response_series = await apiClient.get('/owner/series')
    series.value = response_series.data
  } catch (err) {
    console.log('シリーズ名の取得に失敗:', err)
  }
}

onMounted(() => {
  loadToys()
  getCategories()
  getSeries()
})

// ソートやフィルタ時は1ページ目に戻す
watch([toys, sort, category_id, series_id, isReserveOnly], () => {
  currentPage.value = 1
})
</script>

<style scoped>
.toy-list-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 1.5em 0 1em 0;
  gap: 1em;
  position: relative;
}
.sort-form {
  display: flex;
  align-items: center;
  gap: 1em;
  flex-wrap: wrap;
}
.switch-label {
  display: flex;
  align-items: center;
  cursor: pointer;
}
.switch-slider {
  width: 34px;
  height: 20px;
  background-color: #ccc;
  border-radius: 34px;
  position: relative;
  transition: background-color 0.2s;
  margin-right: 0.5em;
}
input[type="checkbox"] {
  opacity: 0;
  width: 0;
  height: 0;
}
input[type="checkbox"]:checked + .switch-slider {
  background-color: #338fe5;
}
input[type="checkbox"]:checked + .switch-slider:before {
  transform: translateX(14px);
}
.switch-slider:before {
  content: "";
  width: 16px;
  height: 16px;
  background-color: white;
  border-radius: 50%;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: transform 0.2s;
}
.select-group {
  margin-left: 0.5em;
}
.sort-form select {
  margin-left: 0.3em;
  padding: 0.2em 0.5em;
  border-radius: 4px;
  border: 1px solid #ccc;
}
.sort-form button {
  padding: 0.3em 1em;
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}
.sort-form button:hover {
  background: #226bb3;
}
.reset-link {
  margin-left: 0.5em;
  color: #338fe5;
  text-decoration: underline;
  cursor: pointer;
}
.toy-list-container {
  padding: 20px;
}
.toy-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}
.toy-card-link {
  text-decoration: none;
  color: inherit;
  width: calc(25% - 20px);
  min-width: 220px;
}
.toy-card {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 12px;
  box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  cursor: pointer;
}
.toy-card:hover {
  transform: scale(1.03);
  box-shadow: 4px 4px 16px rgba(0,0,0,0.12);
}
.toy-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-bottom: 10px;
  display: block;
}
.no-image {
  width: 100px;
  height: 100px;
  background: #f0f0f0;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
  font-size: 0.95em;
  border: 1px dashed #ccc;
}
.toy-info p {
  margin: 4px 0;
}
.pagination {
  padding: 1em 1em;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1em;
}
.pagination-button {
  padding: 0.5em 1em;
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 0.5em;
  transition: background 0.2s;
}
.pagination-button:hover {
  background: #226bb3;
}
.current-page {
  margin: 0 1em;
  font-weight: bold;
}
</style>
