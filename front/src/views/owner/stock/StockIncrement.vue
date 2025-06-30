<template>
  <div class="stock-increment-container">
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error" class="error-msg">エラー: {{ error }}</div>
    <div v-else-if="toy">
      <h2>ID:{{ toy.id }} 「{{ toy.name }}」</h2>
      <p class="stock-info">在庫数：<span class="stock-old">{{ toy.stock }}</span> <span class="arrow">→</span> <span class="stock-new">{{ toy.stock + changeNum }}</span></p>
      <div class="input-row">
        <label>仕入数：</label>
        <select v-model.number="changeNum">
          <option v-for="n in 100" :key="n" :value="n">{{ n }}</option>
        </select>
        <button class="add-btn" @click="addStock">入荷</button>
      </div>
    </div>
    <div v-else>
      <p class="error-msg">おもちゃ情報が見つかりませんでした。</p>
    </div>
    <button class="back-btn" @click="goBack">在庫一覧に戻る</button>
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

<style scoped>
.stock-increment-container {
  max-width: 420px;
  margin: 2em auto;
  padding: 2em 1.5em;
  border-radius: 10px;
  text-align: center;
}
h2 {
  margin-bottom: 1em;
  color: #338fe5;
}
.stock-info {
  font-size: 1.1em;
  margin-bottom: 1.5em;
}
.stock-old {
  color: #888;
  font-weight: bold;
}
.arrow {
  margin: 0 0.5em;
  color: #338fe5;
  font-weight: bold;
}
.stock-new {
  color: #2baf2b;
  font-weight: bold;
}
.input-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1em;
  margin-bottom: 2em;
}
.input-row label {
  font-weight: bold;
}
.input-row select {
  padding: 0.2em 0.5em;
  border-radius: 4px;
  border: 1px solid #ccc;
}
.add-btn {
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.4em 1.2em;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}
.add-btn:hover {
  background: #226bb3;
}
.back-btn {
  margin-top: 1.5em;
  background: #eee;
  color: #338fe5;
  border: none;
  border-radius: 4px;
  padding: 0.5em 1.5em;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}
.back-btn:hover {
  background: #dbeafe;
}
.error-msg {
  color: #d32f2f;
  margin: 1em 0;
}
.loader {
  border: 6px solid #eee;
  border-top: 6px solid #338fe5;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
  margin: 2em auto;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>