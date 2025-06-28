<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'


const router = useRouter()
// const series = ref([])
const loading = ref(true)
const error = ref(null)

const series = ref({
  // data: [],
  // meta: {
  //   current_page: 1,
  //   last_page: 1
  // }
})
// axios の利点
// 自動的に JSON.parse() してくれる
// HTTPステータスでエラーハンドリングがしやすい
const fetchseries = async () => {
    loading.value = true
    error.value = null
  try {
    const res = await apiClient.get('/owner/series');
    console.log(res);
    // console.log(res.data);
    series.value = res.data // 
  } catch (error) {
    alert('シリーズ一覧の取得に失敗しました')
      error.value = '商品の取得に失敗しました'
  }finally {
    loading.value = false
  }
}


const confirmDelete = async (id) => {
  if (confirm('本当に削除しますか？')) {
    try {
      await apiClient.delete(`/owner/series/${id}`)
      alert('シリーズを削除しました')
      // // 削除後のリスト再取得、またはローカル更新
      // series.value = series.value.filter(c => c.id !== id)
           // 配列部分だけ更新
      series.value.data = series.value.data.filter(c => c.id !== id)
    } catch (error) {
      alert('削除に失敗しました')
      console.error(error)
    }
  }
}
onMounted(() => {
    // fetchPage()

  fetchseries()
})
 </script>


<!-- <script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const loading = ref(true)
const error = ref(null)

const series = ref({
  data: [],
  current_page: 1,
  last_page: 1
})

const fetchPage = async (page = 1) => {
  loading.value = true
  error.value = null
  try {
    const res = await apiClient.get(`/owner/series?page=${page}`)
    series.value = res.data
  } catch (e) {
    error.value = 'シリーズの取得に失敗しました'
  } finally {
    loading.value = false
  }
} -->

<!-- const confirmDelete = async (id) => {
  if (confirm('本当に削除しますか？')) {
    try {
      await apiClient.delete(`/owner/series/${id}`)
      alert('シリーズを削除しました')
      series.value.data = series.value.data.filter(c => c.id !== id)
    } catch (error) {
      alert('削除に失敗しました')
      console.error(error)
    }
  }
}

onMounted(() => {
  fetchPage()
})
</script> -->

<template>
  <div>
    <h1>
      <RouterLink to="/owner/categories">カテゴリ一覧</RouterLink>
      /
      <span class="active-tab">シリーズ一覧</span>
    </h1>
    <router-link to="/owner/series/create">新規作成</router-link>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    <div v-else>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>名前</th>
            <th>表示順</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in series" :key="category.id">
            <td>{{ category.id }}</td>
            <td>{{ category.name }}</td>
            <td>{{ category.sort_order }}</td>
            <td>
              <router-link :to="`/owner/series/${category.id}/edit`">編集</router-link>
              <span class="space"></span>
              <button @click="confirmDelete(category.id)">削除</button>
              <p v-if="message">{{ message }}</p>
              <p v-if="errorMessage" style="color: red">{{ errorMessage }}</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.active-tab {
  border-bottom: 3px solid #338fe5;
  font-weight: bold;
  padding-bottom: 2px;
}
.space {
  margin-left: 20px;
}
table {
  width: 500px;           /* テーブル全体の幅を固定（必要に応じて調整） */
  max-width: 100%;
  margin: 2em auto 0 auto;
  border-collapse: collapse;
  table-layout: fixed;    /* 列幅を固定 */
}
th, td {
  border: 1px solid #ddd;
  padding: 6px;
  text-align: center;
  word-break: break-all;
}
th:nth-child(1), td:nth-child(1) { width: 50px; }   /* ID */
th:nth-child(2), td:nth-child(2) { width: 200px; }  /* 名前 */
th:nth-child(3), td:nth-child(3) { width: 80px; }   /* 表示順 */
th:nth-child(4), td:nth-child(4) { width: 150px; }   /* 操作 */

button {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}
button:hover {
  background-color: #d32f2f;
}
</style>