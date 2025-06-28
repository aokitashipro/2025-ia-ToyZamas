<template>
  <div class="favorites-container">
    <main>
      <h1 class="favorites-title">お気に入り一覧</h1>
      <div v-if="loading">
        <div class="loader"></div>
      </div>
      <div v-else-if="favorites.length">
        <div v-for="favorite in favorites" :key="favorite.id" class="favorite-row">
          <img
            v-if="favorite.image_url"
            :src="laravel_base_url + favorite.image_url"
            alt="商品画像"
            class="favorite-image"
          >
          <span v-else class="no-image">画像がありません</span>
          <span class="favorite-info">
            商品名: {{ favorite.name }}
            価格: ¥{{ favorite.price?.toLocaleString() }}
          </span>
          <a :href="`/toyzamas/toys/${favorite.toy_id}`" class="details-link">再購入</a>
          <button @click="deleteFavorite(favorite.id)" class="delete-btn">削除</button>
        </div>
      </div>
      <div v-else class="favorites-empty">
        お気に入りはありません。
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { apiClient } from "@/utils/api.js";

const favorites = ref([]);
const loading = ref(true);
const laravel_base_url = 'http://127.0.0.1:8000/storage/';

// Load favorites data
const loadFavorites = async () => {
  loading.value = true;
  try {
    const response = await apiClient.get("/toyzamas/favorites");
    favorites.value = response.data;
  } catch (error) {
    console.error("データの取得に失敗:", error);
  } finally {
    loading.value = false;
  }
};

// Delete a favorite item
const deleteFavorite = async (favoriteId) => {
  if (!confirm("本当にお気に入りを削除しますか？")) {
    return;
  }
  try {
    await apiClient.delete(`/toyzamas/favorites/${favoriteId}`);
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

<style scoped>
.favorites-container {
  max-width: 700px;
  margin: 2em auto;
  background: #f9f9fc;
  border: 1px solid #e0e0e0;
  padding: 2em 1.5em;
}
.favorites-title {
  text-align: center;
  color: #338fe5;
  margin-bottom: 1.5em;
}
.favorite-row {
  display: flex;
  align-items: center;
  gap: 1em;
  border-bottom: 1px solid #ccc;
  padding: 1em 0;
}
.favorite-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border: 1px solid #ccc;
  background: #fff;
}
.no-image {
  width: 80px;
  height: 80px;
  background: #f0f0f0;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95em;
  border: 1px dashed #ccc;
}
.favorite-info {
  flex: 1;
}
.details-link {
  color: #338fe5;
  text-decoration: underline;
  margin-left: 1em;
}
.delete-btn {
  background: #eee;
  color: #338fe5;
  border: 1px solid #338fe5;
  padding: 0.3em 1em;
  cursor: pointer;
  font-weight: bold;
  margin-left: 1em;
}
.delete-btn:hover {
  background: #dbeafe;
}
.favorites-empty {
  text-align: center;
  margin: 2em 0;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>
