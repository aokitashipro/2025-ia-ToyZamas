<template>
  <div>
    <h1>ショッピングカート</h1>
    <div v-if="loading">
      読み込み中...
    </div>
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    <div v-else>
      <ul v-if="cartItems.length">
        <div v-for="item in cartItems" :key="item.id">
          <hr>
          <h2>{{ item.name }}</h2>
          <p>
            {{ (item.quantity * (item.price ?? 0)).toLocaleString() }}円
            <span style="font-size: 0.8em;">(税込み)</span>
          </p>
          <p>
            数量:
            <span v-if="item.loading">変更中…</span>
            <select
              v-else
              :value="item.quantity"
              @change="updateQuantity(item, Number($event.target.value))"
            >
              <option v-for="n in item.stock" :key="n" :value="n">{{ n }}</option>
            </select>
            <button @click="handleDelete(cartId)" style="margin-left:1rem;">削除</button>
          </p>
          
        </div>
        <hr>
        <p>
          お支払い総額(税込)
          {{ cartItems.reduce((sum, item) => sum + (item.quantity * (item.price ?? 0)), 0).toLocaleString() }}円
        </p>
      </ul>
      <div v-else>カートに商品はありません。</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const cartItems = ref([])
const loading = ref(true)
const error = ref(null)

const handleDelete = async (cartId) => {
  if (!confirm('本当に削除しますか？')) return
  try {
    await apiClient.delete(`/toyzamas/carts/${cartId}`)
    alert('削除しました')
    // 削除後にリストから除外
    cartItems.value = cartItems.value.filter(item => item.id !== cartId)
  } catch (err) {
    alert('削除に失敗しました')
  }
}

onMounted(async () => {
  try {
    loading.value = true
    error.value = null
    const res = await apiClient.get('/toyzamas/carts')
    // 各アイテムにloadingフラグを追加
    cartItems.value = (res.data || res).map(item => ({ ...item, loading: false }))
  } catch (err) {
    console.error('カート情報の取得に失敗しました', err)
    error.value = '商品の取得に失敗しました'
  } finally {
    loading.value = false
  }
})

async function updateQuantity(item, newQuantity) {
  if (newQuantity < 1) return
  item.loading = true
  try {
    await apiClient.put(`/toyzamas/carts/${item.id}`, { quantity: newQuantity })
    item.quantity = newQuantity
  } catch (err) {
    alert('数量の更新に失敗しました')
  } finally {
    item.loading = false
  }
}
</script>