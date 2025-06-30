<script setup>
import { ref, onMounted } from "vue";
import { apiClient } from "@/utils/api.js";

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
  <div>
    <main>
      <h1>予約商品一覧</h1>
      <div v-for="reserve in reserves" :key="reserve.id">
        <img :src="reserve.image_url" alt="" width="100" height="100">
        商品名: {{ reserve.name }} 価格: ¥{{ reserve.price }} 
        予約個数: {{ reserve.reserve_num }}個 合計金額: ¥{{ reserve.total }} 
        お渡し日: {{ reserve.reserve_date }}
        <a :href="`/toyzamas/toys/${reserve.id}`">詳細</a>
        <button @click="deleteReserve(reserve.id)">予約取り消し</button>
      </div>
    </main>
  </div>
</template>
