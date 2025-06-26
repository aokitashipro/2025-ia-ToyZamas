<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const toys = ref([]) // toysをリアクティブに定義
const sort = ref(0);

// 商品一覧を取得する関数
const loadToys = async () => {
  try {
    const response = await apiClient.get(`/toyzamas/toys?sort=${sort.value}`); // Laravel APIからデータ取得
    toys.value = response.data; // toysにデータを格納
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("toys部分:", toys.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
  }
}

// コンポーネントのマウント時にデータをロード
onMounted(() => {
  loadToys();
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
        <p>現在のソート値: {{ sort }}</p>
        <button type="submit">ソート実行</button>
      </form>
      <p><a href="/toyzamas/toys">ソートリセット</a></p>
    </main>
  </div>
</template>
