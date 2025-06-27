<template>
  <div>
    <h2>シリーズ編集</h2>

    <div v-if="isSubmitting">読み込み中...</div>

    <form @submit.prevent="submit" v-else>
      <div>
        <label for="name">シリーズ名:</label>
        <input type="text" id="name" v-model="form.name" required />
      </div>

      <div>
        <label for="sort_order">表示順:</label>
        <!-- <input type="number" id="sort_order" v-model="form.sort_order" required /> -->
          <input v-model="form.sort_order" type="number" min="1" max="100" required />
      </div>

      <button type="submit" :disabled="isSubmitting">更新</button>
      <p v-if="message">{{ message }}</p>
      <p v-if="errorMessage" style="color: red">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<script setup>
import { defineProps, reactive, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
})

const router = useRouter()

const form = reactive({
  name: '',
  sort_order: 1,
})

const isSubmitting = ref(false)
const message = ref('')
const errorMessage = ref('')

const fetchCategory = async () => {
  console.log('id:', props.id)
  isSubmitting.value = true
  try {
    const res = await apiClient.get(`/owner/categories/${props.id}`)
    form.name = res.data.name
    form.sort_order = res.data.sort_order
  } catch (error) {
    errorMessage.value = 'シリーズの取得に失敗しました'
  } finally {
    isSubmitting.value = false
  }
}

const submit = async () => {
  isSubmitting.value = true
  try {
    await apiClient.put(`/owner/categories/${props.id}`, {
      name: form.name,
      sort_order: form.sort_order
    })
    message.value = 'シリーズを更新しました'
    router.push('/owner/categories')
  } catch (error) {
    errorMessage.value = '更新に失敗しました'
  } finally {
    isSubmitting.value = false
  }
}

onMounted(fetchCategory)
</script>
<style scoped>
/* スタイルは必要に応じて追加 */
form {
  max-width: 400px;
  margin: auto;
} 
input {
  width: 100%;
  padding: 8px;
  margin: 8px 0;
}
button {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}
button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
p {
  margin-top: 10px;
}
</style>