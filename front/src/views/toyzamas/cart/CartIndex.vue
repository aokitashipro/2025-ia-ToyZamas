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
      <template v-if="isPurchased">
        <h2>購入ありがとうございました</h2>
        <ul>
          <li v-for="item in purchasedItems" :key="item.cart_id">
            {{ item.name }} 数量:{{ item.quantity }} {{ (item.quantity * (item.price ?? 0)).toLocaleString() }}円
          </li>
        </ul>
        <p>
          合計金額：{{ purchasedItems.reduce((sum, item) => sum + (item.quantity * (item.price ?? 0)), 0).toLocaleString() }}円
        </p>
      </template>
      <template v-else>
        <ul v-if="cartItems.length">
          <div v-for="item in cartItems" :key="item.cart_id">
            <hr>
            <br>
            <img :src="item.image_url" alt="" width="100" height="100">
            <h2>
              <RouterLink :to="`/toyzamas/toys/${item.toy_id}`">{{ item.name }}</RouterLink>
            </h2>
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
                :disabled="isBuying"
              >
                <option v-for="n in item.stock" :key="n" :value="n">{{ n }}</option>
              </select>
              <button @click="handleDelete(item.cart_id)" style="margin-left:1rem;" :disabled="isBuying || isChanging">削除</button>
            </p>
            <br>
          </div>
          <hr>
          <p>
            お支払い総額(税込)
            {{ cartItems.reduce((sum, item) => sum + (item.quantity * (item.price ?? 0)), 0).toLocaleString() }}円
            <button @click="handleBuy()" style="margin-left:1rem;" :disabled="isBuying || isChanging">購入</button>
          </p>
        </ul>
        <div v-else>カートに商品はありません。
          <br>
          <RouterLink to="/toyzamas/toys">お買い物を続ける</RouterLink>
          <br>
          <RouterLink to="/toyzamas/buy/history">購入履歴を見る</RouterLink>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { apiClient } from '@/utils/api.js'

const cartItems = ref([])
const loading = ref(true)
const error = ref(null)
const isPurchased = ref(false)
const purchasedItems = ref([])
const isBuying = ref(false)
const isChanging = computed(() => cartItems.value.some(item => item.loading))

async function updateQuantity(item, newQuantity) {
  if (isBuying.value) return // 購入中は数量変更不可
  if (newQuantity < 1) return
  item.loading = true
  try {
    await apiClient.put(`/toyzamas/carts/${item.cart_id}`, { quantity: newQuantity })
    item.quantity = newQuantity
  } catch (err) {
    alert('数量の更新に失敗しました')
  } finally {
    item.loading = false
  }
}

const handleDelete = async (cartId) => {
  if (isBuying.value) return // 購入中は削除不可
  if (isChanging.value) return // 数量変更中は削除不可
  if (!confirm('本当に削除しますか？')) return
  try {
    await apiClient.delete(`/toyzamas/carts/${cartId}`)
    alert('削除しました')
    cartItems.value = cartItems.value.filter(item => item.cart_id !== cartId)
  } catch (err) {
    alert('削除に失敗しました')
  }
}

const handleBuy = async () => {
  if (isBuying.value) return
  if (isChanging.value) return
  if (!confirm('購入しますか？')) return
  try {
    isBuying.value = true
    await apiClient.post('/toyzamas/buy', { cart_ids: cartItems.value.map(item => item.cart_id) })
    purchasedItems.value = [...cartItems.value]
    cartItems.value = []
    isPurchased.value = true
  } catch (err) {
    alert('購入に失敗しました')
  } finally {
    isBuying.value = false
  }
}

onMounted(async () => {
  try {
    loading.value = true
    error.value = null
    const res = await apiClient.get('/toyzamas/carts')
    cartItems.value = (res.data || res).map(item => ({ ...item, loading: false }))
  } catch (err) {
    console.error('カート情報の取得に失敗しました', err)
    error.value = '商品の取得に失敗しました'
  } finally {
    loading.value = false
  }
})
</script>