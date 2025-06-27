<script setup>
import { ref, onMounted } from "vue";
import { apiClient } from "@/utils/api.js";

const favorites = ref([]); // Ref to store favorites data

// Load favorites data
const loadFavorites = async () => {
  try {
    const response = await apiClient.get("/toyzamas/favorites");
    favorites.value = response.data; // Populate the ref with the response data
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("favorites部分:", favorites.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
  }
};

// Delete a favorite item
const deleteFavorite = async (favoriteId) => {
  if (!confirm("本当にお気に入りを削除しますか？")) {
    return;
  }

  try {
    await apiClient.delete(`/toyzamas/favorites/${favoriteId}`);
    alert("お気に入りを削除しました");
    // Reload the favorites list after deletion
    await loadFavorites();
  } catch (error) {
    console.error("削除エラー:", error);
    alert("削除に失敗しました");
  }
};

// Fetch favorites when the component is mounted
onMounted(() => {
  loadFavorites();
});
</script>

<template>
  <div>
    <main>
      <h1>お気に入り一覧</h1>
      <div v-for="favorite in favorites" :key="favorite.id">
        <img :src="favorite.image_url" alt="" width="100" height="100">
        商品名: {{ favorite.name }} 価格: ¥{{ favorite.price }}
        <a :href="`/toyzamas/toys/${favorite.id}`">詳細</a>
        <button @click="deleteFavorite(favorite.id)">削除</button>
      </div>
    </main>
  </div>
</template>
