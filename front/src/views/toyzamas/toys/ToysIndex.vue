<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const toys = ref([]) // toysをリアクティブに定義
const sort = ref(0);

const categories = ref([])
const category_id = ref(0)
const series = ref([])
const series_id = ref(0)


// 商品一覧を取得する関数
const loadToys = async () => {
  console.log(category_id.value)
  console.log(series_id.value)
  try {
    // const toyData = {
    //   category_id: category_id.value,
    //   series_id: series_id.value,
    // }
    const response = await apiClient.get(`/toyzamas/toys?sort=${sort.value}&category_id=${category_id.value}&series_id=${series_id.value}`); // Laravel APIからデータ取得
    toys.value = response.data; // toysにデータを格納
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("toys部分:", toys.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
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


// コンポーネントのマウント時にデータをロード
onMounted(() => {
  loadToys();
  getCategories();
  getSeries();
})
</script>

<template>
  <div class="toy-list-container">
  <h1 class="text-2xl font-bold mb-4">商品一覧</h1>
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
        <!-- <p>現在のソート値: {{ sort }}</p> -->
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
</template>

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
