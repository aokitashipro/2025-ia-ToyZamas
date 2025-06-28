<template>
  <div>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">エラー: {{ error }}</div>
    <div v-else-if="toy">
    <h2>ID:{{ toy.id }} 「{{ toy.name }}」</h2>
      <p>在庫数：{{ toy.stock }} => {{ toy.stock + changeNum }}</p>
      <div style="margin: 1em 0;">
        <label>仕入数：</label>
        <select v-model.number="changeNum">
          <option v-for="n in 100" :key="n" :value="n">{{ n }}</option>
        </select>
        <button @click="addStock">入荷</button>
      </div>
    </div>
    <div v-else>
      <p>おもちゃ情報が見つかりませんでした。</p>
    </div>
    <button @click="goBack">在庫一覧に戻る</button>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'

const route = useRoute()
const router = useRouter()
const toy = ref(null)
const loading = ref(true)
const error = ref(null)
const changeNum = ref(1)

const fetchToy = async () => {
  try {
    loading.value = true
    error.value = null
    const toyId = route.params.id
    const response = await apiClient.get(`/owner/stocks/${toyId}`)
    toy.value = response.data || response
  } catch (err) {
    error.value = 'おもちゃ情報の取得に失敗しました'
    toy.value = null
  } finally {
    loading.value = false
  }
}

const addStock = async () => {
  if (!toy.value) return
  try {
    loading.value = true
    await apiClient.put(`/owner/stocks/${toy.value.id}`, { increment: changeNum.value })
    alert('在庫を増やしました')
    await fetchToy()
  } catch (err) {
    alert('在庫の増加に失敗しました')
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.push('/owner/stocks')
}

fetchToy()
</script>