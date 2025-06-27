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
<h2><router-link to="/owner/categories">カテゴリ一管理はこちら</router-link></h2>
    <h1>シリーズ一覧画面</h1>
        <div v-if="loading">
      読み込み中...
    </div>
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
        <div v-else>
    <router-link to="/owner/series/create">新規作成</router-link>
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
.space{
margin-left:20px;
}
table {
  width: 100%;
  border-collapse: collapse;
} 
th, td {
  border: 1px solid #ddd;
  padding: 6px;
}

button {
  background-color: #f44336; /* 赤色 */
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}
button:hover {
  background-color: #d32f2f; /* 濃い赤色 */
}
</style>