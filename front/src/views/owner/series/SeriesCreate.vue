<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'
import { ref } from 'vue'
const form = reactive({
  name: 'シリーズ名',
  sort_order: 1,
})
const isSubmitting = ref(false);
const message = ref('');
const errorMessage = ref('');



const submit = async () => {
  console.log("送信内容：", JSON.stringify(form));
  console.log(form)
    if (isSubmitting.value) return; // すでに送信中なら何もしない
   isSubmitting.value = true; // 送信開始でボタン無効化
  try {
    await apiClient.post('/owner/series', {
  name: form.name,
  sort_order: form.sort_order
})
    router.push('/owner/series')
  } catch (error) {
    alert('登録に失敗しました')
  } finally {
    isSubmitting.value = false; // 送信完了でボタン再有効化
  }
}
const router = useRouter()
</script>

<template>
  <form @submit.prevent="submit">
    <fieldset :disabled="isSubmitting" style="border: none; padding: 0;">
      <!-- //送信中はフォームを無効化 -->
      <div>
        <label for="name">シリーズ名</label>
        <input id="name" v-model="form.name" type="text" required />
      </div>
      <div>
        <label for="sort_order">表示順</label>
        <!-- <input id="sort_order" v-model.number="form.sort_order" type="number" required /> -->
        <input v-model="form.sort_order" type="number" min="1" max="100" required />
      </div>
      <button type="submit" :disabled="isSubmitting">
        {{ isSubmitting ? '送信中...' : '登録' }}
      </button>
    </fieldset>

    <p v-if="message" style="color: green;">{{ message }}</p>
    <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
  </form>
</template>

<!-- <script setup>
const submit = async () => {
  if (isSubmitting.value) return;

  isSubmitting.value = true;
  message.value = '';
  errorMessage.value = '';

  try {
    const response = await apiClient.post('/owner/series', {
      name: form.value.name,
      sort_order: form.value.sort_order,
    });

    message.value = 'カテゴリーが登録されました！';
    setTimeout(() => {
      router.push('/owner/series');
    }, 1000);
  } catch (error) {
    errorMessage.value = '登録に失敗しました。';
  } finally {
    isSubmitting.value = false;
  }
}; -->
<!-- </script> -->