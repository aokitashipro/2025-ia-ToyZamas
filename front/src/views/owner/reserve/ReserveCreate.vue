<template>
  <div>
    <h2>電話予約管理</h2>

    <!-- 新規予約フォーム -->
    <form @submit.prevent="submitReserve">
          <fieldset :disabled="isSubmitting" style="border: none; padding: 0;">
      <!-- //送信中はフォームを無効化 -->
      <div>
        <label>ユーザーID:</label>
        <input v-model="form.user_id" type="number" min="1"  required />
      </div>
      <div>
        <label>商品ID:</label>
        <input v-model="form.toy_id" type="number" min="1" required />
      </div>
      <div>
        <label>予約日:</label>
        <input v-model="form.reserve_date" type="date" required />
      </div>
      <div>
        <label>予約数:</label>
        <input v-model="form.reserve_num" type="number" min="1" max="20" required />
      </div>
      <!-- <button type="submit">予約登録</button> -->
      <button type="submit" :disabled="isSubmitting">
        <!-- {{ isEdit ? '予約更新' : '予約登録' }} -->
       {{ isSubmitting ? '送信中...' : '登録' }}
      </button>
      <br />
          </fieldset>
    </form>

    <hr />

    <!-- 予約一覧表示 -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>ユーザー</th>
          <th>商品</th>
          <th>予約日</th>
          <th>個数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="reserve in reserves" :key="reserve.id">
          <td>{{ reserve.id }}</td>
          <td>{{ reserve.user?.name || reserve.user_id }}</td>
          <td>{{ reserve.toy?.name || reserve.toy_id }}</td>
          <!-- // isEditがtrueの場合のみinputにする -->
          <td v-if="isEdit === true && reserve_id === reserve.id">
            <input type="date" v-model="reserve.reserve_date" required>
          </td>
          <td v-else-if="isEdit === false || reserve_id !== reserve.id">{{ reserve.reserve_date }}</td>

          <td v-if="isEdit === true && reserve_id === reserve.id">
            <input type="number" v-model="reserve.reserve_num" min="1" max="20" required> 
          </td>
          <td v-else-if="isEdit === false || reserve_id !== reserve.id">{{ reserve.reserve_num }}</td>
          
          <td>
            <button @click="editReserve(reserve)">
                {{ isEdit ? '予約登録' : '予約編集' }}
            </button>
            <button @click="deleteReserve(reserve.id)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const reserves = ref([])
const form = ref({
  user_id: '',
  toy_id: '',
  reserve_date: '',
  reserve_num: ''
})
const isEdit = ref(false)
const editingId = ref(null)
const isSubmitting = ref(false);
const reserve_id = ref(null)

const fetchReserves = async () => {
  //   //  if (isSubmitting.value) return; // すでに送信中なら何もしない
  //isSubmitting.value = true; // 送信開始でボタン無効化
  try {
    const res = await apiClient.get('/owner/reserves')
      console.log('取得成功:', res.data)
    reserves.value = res.data
  } catch (e) {
        console.error('予約一覧取得に失敗', e)
    alert('予約一覧の取得に失敗しました')
  } finally {
    // 送信完了でボタン再有効化
    isSubmitting.value = false;
  }
}

const submitReserve = async () => {
  try {
      await apiClient.post('/owner/reserves', form.value)
      alert('予約を登録しました')
    
      await fetchReserves()
      resetForm()
  } catch (e) {
    alert(e.response?.data?.message || '予約の登録に失敗しました')
  }
}

// const editReserve = (reserve) => {
//   form.value = {
//     user_id: reserve.user_id,
//     toy_id: reserve.toy_id,
//     reserve_date: reserve.reserve_date,
//     reserve_num: reserve.reserve_num
//   }
//   isEdit.value = true
//   editingId.value = reserve.id
// }


async function editReserve(reserve){
  if(isEdit.value === false){
    isEdit.value = true
    reserve_id.value = reserve.id
  }else if(isEdit.value === true){
    form.value.user_id = reserve.user_id
    form.value.toy_id = reserve.toy_id
    form.value.reserve_date = reserve.reserve_date
    form.value.reserve_num = reserve.reserve_num

    console.log(form.value)
    await apiClient.put(`/owner/reserves/${reserve.id}`, form.value)
    resetForm()
  }
}

const deleteReserve = async (id) => {
  if (confirm('本当に削除しますか？')) {
    await apiClient.delete(`/owner/reserves/${id}`)
    await fetchReserves()
  }
}

const resetForm = () => {
  form.value = {
    user_id: '',
    toy_id: '',
    reserve_date: '',
    reserve_num: ''
  }
  isEdit.value = false
  editingId.value = null
}

onMounted(() => {
  fetchReserves()
})
</script>

<style scoped>
h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}
form {
  display: flex;
  flex-direction: column;
  max-width: 400px;
}
input {
  margin-bottom: 0.5rem;
  padding: 0.25rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  padding: 0.3rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
table {
  border-collapse: collapse;
  width: 100%;
  margin-top: 1rem;
}
th, td {
  border: 1px solid #ddd;
  padding: 0.5rem;
}
form > div {
  margin: 0.5rem 0;
}
</style>
