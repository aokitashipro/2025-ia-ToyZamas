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
  <div>
    <main>
      <h1>商品一覧</h1>
      <div v-for="toy in toys" :key="toy.id">
        <img :src="toy.image_url" alt="" width="100" height="100">
        ID: {{ toy.id }} 商品名: {{ toy.name }} 価格: ¥{{ toy.price }} 
        <a :href="`/toyzamas/toys/${toy.id}`">詳細</a>
      </div>
      <form @submit.prevent="loadToys">
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
    </main>
  </div>
</template>

<<style>
main {
  font-family: 'Arial', sans-serif;
  background-color: #f9f9f9; /* 背景色を明るい灰色に */
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);

  max-width: 1200px; /* 最大幅を設定 */
  margin: 0 auto; /* 中央揃え */
  padding: 20px; /* 余白を設定 */
}

h1 {
  text-align: center;
  color: #333; /* タイトルの色を濃い灰色に */
  margin-bottom: 20px;
}

div[v-for] {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #fff; /* 商品カードの背景を白に */
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  width: 80%; /* 横幅を指定（全体の80%） */
  max-width: 1200px; /* 最大幅を指定（例: 1200px） */
  margin: 15px auto; /* 自動で中央揃え */
}


img {
  border-radius: 5px; /* 画像の角を丸く */
  margin-right: 15px;
}

a {
  color: #007bff; /* リンク色を青に */
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

form {
  margin-top: 20px;
}

button {
  background-color: #007bff; /* ボタンの背景色を青に */
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px 15px;
  font-size: 14px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3; /* ボタンホバー時の濃い青 */
}

p {
  margin-top: 10px;
  color: #666;
  font-size: 14px;
}
</style>

