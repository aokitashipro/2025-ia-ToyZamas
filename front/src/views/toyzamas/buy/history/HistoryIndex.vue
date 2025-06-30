<template>
  <div class="history-container">
    <h1 class="history-title">注文履歴</h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <div v-if="histories.length">
        <div v-for="history in histories" :key="history.sale_id" class="history-block">
          <hr>
          <p class="history-date">{{ history.purchased_at }}</p>
          <table class="history-table">
            <thead>
              <tr>
                <th>商品</th>
                <th>数量</th>
                <th>小計</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in history.items" :key="item.toy_name + item.quantity">
                <td class="history-item-info">
                  <img
                    v-if="item.image_url"
                    :src="laravel_base_url + item.image_url"
                    alt="商品画像"
                    class="history-image"
                  >
                  <span v-else class="no-image">画像がありません</span>
                  <RouterLink :to="`/toyzamas/toys/${item.toy_id}`">{{ item.toy_name }}</RouterLink>
                </td>
                <td>{{ item.quantity }}</td>
                <td>{{ (parseInt(item.subtotal) || 0).toLocaleString() }}円</td>
              </tr>
            </tbody>
          </table>
          <p class="history-total">合計金額: {{ history.total }}</p>
        </div>
      </div>
      <div v-else class="history-empty">
        購入履歴がありません。
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const histories = ref([])
const loading = ref(true)
const error = ref(null)
const laravel_base_url = 'http://127.0.0.1:8000/storage/'

onMounted(async () => {
  try {
    loading.value = true
    error.value = null
    const res = await apiClient.get('/toyzamas/history')
    histories.value = res.data ? res.data : res
  } catch (err) {
    error.value = '購入履歴の取得に失敗しました'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.history-container {
  max-width: 800px;
  margin: 2em auto;
  background: #f9f9fc;
  border: 1px solid #e0e0e0;
  padding: 2em 1.5em;
}
.history-title {
  text-align: center;
  color: #338fe5;
  margin-bottom: 1.5em;
}
.history-block {
  margin-bottom: 2em;
  padding-bottom: 1em;
  border-bottom: 1px solid #ccc;
}
.history-date {
  font-weight: bold;
  color: #338fe5;
  margin-bottom: 0.5em;
}
.history-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 0.5em;
}
.history-table th,
.history-table td {
  padding: 0.5em;
  border-bottom: 1px solid #e0e0e0;
  text-align: left;
}
.history-item-info {
  display: flex;
  align-items: center;
  gap: 0.7em;
}
.history-image {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border: 1px solid #ccc;
  background: #fff;
}
.no-image {
  width: 70px;
  height: 70px;
  background: #f0f0f0;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95em;
  border: 1px dashed #ccc;
}
.history-total {
  text-align: right;
  font-weight: bold;
  margin-top: 0.5em;
}
.history-empty {
  text-align: center;
  margin: 2em 0;
}
</style>