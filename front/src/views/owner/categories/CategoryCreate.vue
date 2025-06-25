<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
const form = reactive({
  name: '',
  sort_order: 0,
})

const submit = async () => {
  try {
    await fetch.post('/api/owner/categories', form)
    router.push('/owner/categories')
  } catch (error) {
    alert('登録に失敗しました')
  }
}
const router = useRouter()
</script>

<template>
 <div>
    <h2>カテゴリー新規作成</h2>
    <form @submit.prevent="submit">
      <div>
        <label>名前</label>
        <input v-model="form.name" required />
      </div>
      <div>
        <label>表示順</label>
        <input type="number" v-model.number="form.sort_order" required />
      </div>
      <button type="submit">登録</button>
    </form>
  </div>
</template>