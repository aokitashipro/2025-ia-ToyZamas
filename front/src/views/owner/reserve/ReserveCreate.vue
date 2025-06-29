<template>
  <div>
    <h1>電話予約管理</h1>

    <!-- 新規予約フォーム -->
    <form @submit.prevent="submitReserve">
      <fieldset :disabled="isSubmitting" style="border: none; padding: 0;">
        <div class="form-row">
          <label>ユーザー</label>
          <input v-model="form.user_id" type="number" min="1" required @change="updateUserName" />
          <span class="user-name">
            <template v-if="form.user_id">
              <template v-if="selectedUserName">{{ selectedUserName }}</template>
              <template v-else>ユーザーがいません</template>
            </template>
          </span>
        </div>
        <div class="form-row">
          <label>商品</label>
          <input v-model="form.toy_id" type="number" min="1" required @change="updateToyName" />
          <span v-if="selectedToyName" class="user-name">{{ selectedToyName }}</span>
        </div>
        <div class="form-row">
          <label>予約日</label>
          <input v-model="form.reserve_date" type="date" required />
        </div>
        <div class="form-row">
          <label>予約数</label>
          <input v-model="form.reserve_num" type="number" min="1" max="20" required />
          <button type="submit" :disabled="isSubmitting || loading" class="inline-btn">
            {{ isSubmitting ? '送信中...' : '登録' }}
          </button>
        </div>
      </fieldset>
    </form>

    <hr />

    <!-- ローディング中はローダーを表示 -->
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <table v-else>
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
          <td v-if="isEdit === true && reserve_id === reserve.id">
            <input type="date" v-model="reserve.reserve_date" required>
          </td>
          <td v-else>{{ reserve.reserve_date }}</td>
          <td v-if="isEdit === true && reserve_id === reserve.id">
            <input type="number" v-model="reserve.reserve_num" min="1" max="20" required>
          </td>
          <td v-else>{{ reserve.reserve_num }}</td>
          <td>
            <button @click="editReserve(reserve)" :disabled="isSubmitting || loading">
              {{ isEdit && reserve_id === reserve.id ? '予約登録' : '予約編集' }}
            </button>
            <button @click="deleteReserve(reserve.id)" :disabled="isSubmitting || loading">削除</button>
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
const users = ref([])
const toys = ref([])
const selectedUserName = ref('')
const selectedToyName = ref('')
const form = ref({
  user_id: '',
  toy_id: '',
  reserve_date: '',
  reserve_num: ''
})
const isEdit = ref(false)
const editingId = ref(null)
const isSubmitting = ref(false)
const loading = ref(false)
const reserve_id = ref(null)

const fetchReserves = async () => {
  loading.value = true
  try {
    const res = await apiClient.get('/owner/reserves')
    reserves.value = res.data
  } catch (e) {
    alert('予約一覧の取得に失敗しました')
  } finally {
    loading.value = false
    isSubmitting.value = false
  }
}

const submitReserve = async () => {
  isSubmitting.value = true
  loading.value = true
  try {
    await apiClient.post('/owner/reserves', form.value)
    await fetchReserves()
    resetForm()
  } catch (e) {
    alert(e.response?.data?.message || '予約の登録に失敗しました')
  } finally {
    isSubmitting.value = false
    loading.value = false
  }
}

async function editReserve(reserve) {
  if (isEdit.value === false){
    isEdit.value = true
    reserve_id.value = reserve.id
  } else if (isEdit.value === true){
    isSubmitting.value = true
    loading.value = true
    form.value.user_id = reserve.user_id
    form.value.toy_id = reserve.toy_id
    form.value.reserve_date = reserve.reserve_date
    form.value.reserve_num = reserve.reserve_num
    try {
      await apiClient.put(`/owner/reserves/${reserve.id}`, form.value)
      await fetchReserves()
      resetForm()
    } catch (e) {
      alert('予約の編集に失敗しました')
    } finally {
      isSubmitting.value = false
      loading.value = false
    }
  }
}

const deleteReserve = async (id) => {
  if (confirm('本当に削除しますか？')) {
    isSubmitting.value = true
    loading.value = true
    try {
      await apiClient.delete(`/owner/reserves/${id}`)
      await fetchReserves()
    } catch (e) {
      alert('削除に失敗しました')
    } finally {
      isSubmitting.value = false
      loading.value = false
    }
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
  selectedUserName.value = ''
  selectedToyName.value = ''
}

// ユーザー一覧を取得
const fetchUsers = async () => {
  try {
    const res = await apiClient.get('/owner/users')
    users.value = res.data
  } catch (e) {
    console.error('ユーザー一覧取得に失敗', e)
  }
}

// 商品一覧を取得
const fetchToys = async () => {
  try {
    const res = await apiClient.get('/owner/toys')
    toys.value = res.data
  } catch (e) {
    console.error('商品一覧取得に失敗', e)
  }
}

// ユーザーID変更時に名前を表示
function updateUserName() {
  const user = users.value.find(u => String(u.id) === String(form.value.user_id))
  selectedUserName.value = user ? user.name : ''
}

// 商品ID変更時に商品名を表示
function updateToyName() {
  const toy = toys.value.find(t => String(t.id) === String(form.value.toy_id))
  selectedToyName.value = toy ? toy.name : ''
}

onMounted(() => {
  fetchReserves()
  fetchUsers()
  fetchToys()
})
</script>

<style scoped>
h1 {
  margin-bottom: 0.5em;
}
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
.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
}
.form-row input[type="number"] {
  width: 120px; /* 必要に応じて調整 */
  flex: unset;
}
.form-row input[type="date"] {
  width: 120px; /* 他の入力欄と同じ幅に揃える */
  flex: unset;
}
.form-row label {
  width: 90px;      /* ラベルの幅を揃える */
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.form-row input {
  flex: 1;
}
.user-name {
  margin-left: 0.5em;
  font-weight: bold;
}
.inline-btn {
  display: inline-block;
  padding: 0.3rem 0.6rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-left: 0.5rem;
}
.form-row .inline-btn {
  margin-left: 3em;
  padding: 0.3rem 1.2em;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  height: 32px;
  line-height: 1;
}
td > button {
  margin-right: 0.5em;
}

th:nth-child(6), td:nth-child(6) { width: 150px; }

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
