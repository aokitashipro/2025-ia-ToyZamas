<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const users = ref([]) // usersをリアクティブに定義
const sort = ref(0);

const loading = ref(true)
const error = ref(null)

// ユーザー一覧を取得する関数
const loadUsers = async () => {
  try {
    loading.value = true

    const response = await apiClient.get(`/owner/users?sort=${sort.value}`); // Laravel APIからデータ取得
    users.value = response.data; // usersにデータを格納
    console.log("レスポンス内容:", response);
    console.log("データ部分:", response.data);
    console.log("users部分:", users.value);
  } catch (error) {
    console.error("データの取得に失敗:", error);
  }finally{
    loading.value = false
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
      <div v-if="loading">
        ユーザーデータを取得中...
      </div>
      <div v-else>
        <h1>ユーザー一覧</h1>
        <table border="1">
          <tr>
            <th>ID</th>
            <th>名前</th>
            <th>権限</th>
            <th>詳細</th>
            <th>削除</th>
          </tr>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.is_admin }}</td> 
            <td><a :href="`/owner/users/${user.id}`">詳細</a></td>
            <td><button @click="deleteUser(user.id)">削除</button></td>
          </tr>
        </table>        
      </div>
    </main>
  </div>
</template>

<style scoped>
    table {
    width: 100%;
    border-collapse: collapse;
    } 
    th, td {
    border: 1px solid #ddd;
    padding: 8px;
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