<script setup>
import { reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
const router = useRouter()
const route = useRoute()

const form = reactive({
  name: '',
  sort_order: 0,
})

const fetchCategory = async () => {
  try {
    const res = await fetch.get(`/api/owner/categories/${route.params.category}`)
    const data = res.data.data
    form.name = data.name
    form.sort_order = data.sort_order
  } catch (error) {
    alert('カテゴリーの取得に失敗しました')
  }
}

const submit = async () => {
  try {
    await fetch.put(`/api/owner/categories/${route.params.category}`, form)
    router.push('/owner/categories')
  } catch (error) {
    alert('更新に失敗しました')
  }
}

onMounted(fetchCategory)
</script>

<template>
 <div>
    <h2>カテゴリー編集</h2>
    <form @submit.prevent="submit">
      <div>
        <label>名前</label>
        <input v-model="form.name" required />
      </div>
      <div>
        <label>表示順</label>
        <input type="number" v-model.number="form.sort_order" required />
      </div>
      <button type="submit">更新</button>
    </form>
  </div>
</template>