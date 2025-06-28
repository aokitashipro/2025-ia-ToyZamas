<template>
  <h1>商品一覧</h1>
  <div class="toy-list-container">
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else>
      <div class="toy-grid">
        <div v-for="toy in toys" :key="toy.id" class="toy-card">
          <img :src="toy.image_url" alt="商品画像" class="toy-image" />
          <div class="toy-info">
            <p><strong>{{ toy.name }}</strong></p>
            <p>価格: ¥{{ toy.price }}</p>
            <a :href="`/toyzamas/toys/${toy.id}`" class="details-link">詳細を見る</a>
          </div>
        </div>
        <form @submit.prevent="loadToys" class="sort-form">
          <label>
            <input type="radio" name="sort" value=1 v-model="sort"> 予約対象商品一覧
          </label>
          <button type="submit">ソート実行</button>
          <p>
            カテゴリ名：
            <select v-model="category_id">
              <option v-for="category in categories" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </p>
          <p>
            シリーズ名：
            <select v-model="series_id">
              <option v-for="a_series in series" :value="a_series.id">
                {{ a_series.name }}
              </option>
            </select>
          </p>
        </form>
        <p><a href="/toyzamas/toys">ソートリセット</a></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const toys = ref([])
const sort = ref(0)
const categories = ref([])
const category_id = ref(0)
const series = ref([])
const series_id = ref(0)
const loading = ref(false) // 追加

const loadToys = async () => {
  loading.value = true // 追加
  try {
    const response = await apiClient.get(`/toyzamas/toys?sort=${sort.value}&category_id=${category_id.value}&series_id=${series_id.value}`)
    toys.value = response.data
  } catch (error) {
    console.error("データの取得に失敗:", error)
  } finally {
    loading.value = false // 追加
  }
}

async function getCategories(){
  try{
    const response_categories = await apiClient.get('/owner/categories')
    categories.value = response_categories.data
  }catch(err){
      console.log('カテゴリ名の取得に失敗:', err)
  }
}

async function getSeries(){
  try{
    const response_series = await apiClient.get('/owner/series')
    series.value = response_series.data
  }catch(err){
    console.log('シリーズ名の取得に失敗:', err)
  }
}

onMounted(() => {
  loadToys()
  getCategories()
  getSeries()
})
</script>

<style scoped>
.toy-list-container {
  padding: 20px;
}

.toy-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.toy-card {
  width: calc(25% - 20px); /* 4列に並べる */
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 12px;
  box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.toy-card:hover {
  transform: scale(1.03);
}

.toy-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-bottom: 10px;
}

.toy-info p {
  margin: 4px 0;
}

.details-link {
  display: inline-block;
  margin-top: 8px;
  color: #007bff;
  text-decoration: underline;
}

.sort-form {
  margin-top: 30px;
}
</style>
