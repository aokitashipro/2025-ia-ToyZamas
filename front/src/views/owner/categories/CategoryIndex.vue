<script setup>
import { ref, onMounted } from 'vue'
// import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const categories = ref([])

const fetchCategories = async () => {
  try {
    const responce = await fetch.get('/api/owner/categories')
    categories.value = responce.data.data // Laravel APIのJSON構造に合わせる
  } catch (error) {
    alert('カテゴリー一覧の取得に失敗しました')
  }
}

const deleteCategory = async (id) => {
  if (!confirm('本当に削除しますか？')) return
  try {
    await fetch.delete(`/api/owner/categories/${id}`)
    categories.value = categories.value.filter(c => c.id !== id)
  } catch (error) {
    alert('削除に失敗しました')
  }
}

onMounted(()=>{
fetchCategories()
})
</script>

<template>
 <div>
    <h2>カテゴリー一覧</h2>
    <router-link to="/owner/categories/create">新規作成</router-link>
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
        <tr v-for="category in categories" :key="category.id">
          <td>{{ category.id }}</td>
          <td>{{ category.name }}</td>
          <td>{{ category.sort_order }}</td>
          <td>
            <router-link :to="`/owner/categories/${category.id}/edit`">編集</router-link>
            <button @click="deleteCategory(category.id)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>