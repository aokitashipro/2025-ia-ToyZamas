<template>
  <form @submit.prevent="submit">
    <fieldset :disabled="isSubmitting" style="border: none; padding: 0;">
      <button type="button" class="back-btn" @click="router.back()">← 前のページに戻る</button>
      <div class="form-row">
        <label for="name">カテゴリー</label>
        <input id="name" v-model="form.name" type="text" required />
      </div>
      <div class="form-row">
        <label for="sort_order">表示順</label>
        <input v-model="form.sort_order" type="number" min="1" max="100" required />
      </div>
      <div class="form-row">
        <button type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? '送信中...' : '登録' }}
        </button>
      </div>
    </fieldset>

    <p v-if="message" style="color: green;">{{ message }}</p>
    <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
  </form>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'
import { ref } from 'vue'
const form = reactive({
  name: '',
  sort_order: 1,
})
const isSubmitting = ref(false);
const message = ref('');
const errorMessage = ref('');
const router = useRouter()

const submit = async () => {
  console.log("送信内容：", JSON.stringify(form));
  console.log(form)
  if (isSubmitting.value) return;
  isSubmitting.value = true;
  try {
    await apiClient.post('/owner/categories', {
      name: form.name,
      sort_order: form.sort_order
    })
    alert('カテゴリの登録に成功しました。カテゴリ一覧に戻ります。')
    router.push('/owner/categories')
  } catch (error) {
    alert('登録に失敗しました')
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<style scoped>
form {
  max-width: 400px;   /* ここでフォーム全体の横幅を調整 */
  margin: 0 auto;
}
.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 1em;
}
.form-row label {
  width: 90px;
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.form-row input {
  flex: 1;
  padding: 0.25rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.back-btn {
  background: #eee;
  color: #333;
  margin-bottom: 1em;
  margin-right: 1em;
  padding: 6px 14px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
}
.back-btn:hover {
  background: #ddd;
}
</style>