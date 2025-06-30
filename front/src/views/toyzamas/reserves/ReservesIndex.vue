<script setup>
import { ref, onMounted } from "vue";
import { apiClient } from "@/utils/api.js";

const laravel_base_url = 'http://127.0.0.1:8000/storage/'

const reserves = ref([]); // Ref to store reserves data

// Load reserves data
const loadFavorites = async () => {
  try {
    const response = await apiClient.get("/toyzamas/reserves");
    reserves.value = response.data; // Populate the ref with the response data
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("reserves部分:", reserves.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
  }
};

// Delete a reserve item
const deleteReserve = async (reserveId) => {
  if (!confirm("本当に予約を取り消しますか？")) {
    return;
  }

  try {
    await apiClient.delete(`/toyzamas/reserves/${reserveId}`);
    alert("予約を取り消しました");
    // Reload the reserves list after deletion
    await loadFavorites();
  } catch (error) {
    console.error("削除エラー:", error);
    alert("予約取り消しに失敗しました");
  }
};

// Fetch reserves when the component is mounted
onMounted(() => {
  loadFavorites();
});
</script>

<template>
  <div class="toy-show-container">
    <div class="toy-main-row">
      <main>
      <h1 class="toy-title">予約商品一覧</h1>
      <div class="toy-image-col" v-for="reserve in reserves" :key="reserve.id">
        <img 
            v-if="reserve.image_url"
            :src="laravel_base_url + reserve.image_url"
            alt="商品画像"
            class="toy-image"
        />
        <div class="toy-info">
          <div class="info-row">
            <label>商品名:</label>
            <span>{{ reserve.name }}</span>
          </div>
          <div class="info-row">
            <label>価格:</label>
            <span>{{ reserve.price?.toLocaleString() }}円</span>
          </div>
          <div class="info-row">
            <label>個数:</label>
            <span>{{ reserve.reserve_num }}コ</span>
          </div>
          <div class="info-row">
            <label>合計金額:</label>
            <span>{{ reserve.total?.toLocaleString() }}円</span>
          </div>
          <div class="info-row">
            <label>お渡し日:</label>
            <span>{{ reserve.reserve_date }}</span>
          </div>
          <div class="info-row">
            <label>発売日:</label>
            <span><a :href="`/toyzamas/toys/${reserve.id}`">詳細</a></span>
          </div>
          <div class="info-row">
            <button @click="deleteReserve(reserve.id)">予約取り消し</button>
          </div>
        </div>
        <!-- 商品名: {{ reserve.name }} 価格: ¥{{ reserve.price }} 
        予約個数: {{ reserve.reserve_num }}個 合計金額: ¥{{ reserve.total }} 
        お渡し日: {{ reserve.reserve_date }}
        <a :href="`/toyzamas/toys/${reserve.id}`">詳細</a> -->
        
      </div>
    </main>
    </div>
  </div>
</template>

<style scoped>
.toy-show-container {
  max-width: 700px;
  margin: 2em auto;
  background: #f9f9fc;
  border: 1px solid #e0e0e0;
  padding: 2em 1.5em;
}
.toy-main-row {
  display: flex;
  gap: 2em;
  align-items: flex-start;
}
.toy-image-col {
  flex: 0 0 320px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.toy-info-col {
  flex: 1;
  min-width: 0;
}
.toy-title {
  color: #338fe5;
  margin: 0 0 1em 0;
  font-size: 1.5em;
  text-align: left;
}
.toy-image {
  width: 300px;
  height: auto;
  display: block;
  margin-bottom: 1em;
  border: 1px solid #ccc;
  background: #fff;
}
.no-image-text {
  width: 300px;
  height: 200px;
  background: #f0f0f0;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1em;
  font-size: 1em;
  border: 1px dashed #ccc;
}
.toy-info {
  margin-bottom: 2em;
}
.info-row {
  display: flex;
  align-items: center;
  margin-bottom: 0.7em;
}
.info-row label {
  width: 90px;
  font-weight: bold;
  color: #338fe5;
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.info-row span {
  flex: 1;
}
.action-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.7em;
  margin-top: 1.5em;
}
.btn, .btn-secondary {
  padding: 0.5em 1.5em;
  background: #338fe5;
  color: #fff;
  border: none;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-secondary {
  background: #eee;
  color: #338fe5;
}
.btn:hover:not(.btn-secondary) {
  background: #226bb3;
}
.btn-secondary:hover {
  background: #dbeafe;
}
.num-select {
  padding: 0.4em;
  border: 1px solid #ccc;
  border-radius: 4px;
}
@media (max-width: 700px) {
  .toy-main-row {
    flex-direction: column;
    gap: 1em;
  }
  .toy-image-col {
    align-items: center;
    margin-bottom: 1em;
  }
  .toy-info-col {
    width: 100%;
  }
}
</style>