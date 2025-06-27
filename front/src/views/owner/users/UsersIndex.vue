<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const users = ref([]) // usersをリアクティブに定義
const sort = ref(0);

// ユーザー一覧を取得する関数
const loadUsers = async () => {
  try {
    const response = await apiClient.get(`/owner/users?sort=${sort.value}`); // Laravel APIからデータ取得
    users.value = response.data; // usersにデータを格納
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("users部分:", users.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
  }
}

// Delete a favorite item
const deleteUser = async (userId) => {
  if (!confirm("本当にユーザーを削除しますか？")) {
    return;
  }

  try {
    await apiClient.delete(`/owner/users/${userId}`);
    alert("ユーザーを削除しました");
    // Reload the favorites list after deletion
    await loadUsers();
  } catch (error) {
    console.error("削除エラー:", error);
    alert("ユーザー削除に失敗しました");
  }
};

// コンポーネントのマウント時にデータをロード
onMounted(() => {
  loadUsers();
})
</script>

<template>

  <div>
    <main>
      <h1>ユーザー一覧</h1>
      <div v-for="user in users" :key="user.id">
        ID: {{ user.id }} 名前: {{ user.name }} 権限: {{ user.is_admin }} 
        <a :href="`/owner/users/${user.id}`">詳細</a>
        <button @click="deleteUser(user.id)">削除</button>
      </div>
    </main>
  </div>
</template>
