<template>
  <div>
    <h1>注文履歴</h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <div v-if="histories.length">
        <div v-for="history in histories" :key="history.sale_id" style="margin-bottom:2em;">
          <hr>
          <p>{{ history.purchased_at }}</p>
          <table border="0" style="border-collapse: collapse; width: 100%;">
            <tbody>
            <tr v-for="item in history.items" :key="item.toy_name + item.quantity">
              <td>
                <img :src="item.image_url" alt="" width="100" height="100" style="vertical-align:middle; margin-right:8px;">
                <RouterLink :to="`/toyzamas/toys/${item.toy_id}`">{{ item.toy_name }}</RouterLink>
              </td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.subtotal }}</td>
            </tr>
            </tbody>
          </table>
          <p>合計金額: {{ history.total }}</p>
        </div>
      </div>
      <div v-else>
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