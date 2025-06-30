<script setup>
// ユーザーIDの取得
const user = JSON.parse(localStorage.getItem('user'));
// // ログアウト処理
// const handleLogout = async () => {
//   try {
//     // APIでログアウト
//     await apiClient.post('/logout', {})
//   } catch (error) {
//     console.error('ログアウトAPIエラー:', error)
//   } finally {
//     // ローカルストレージをクリア
//     localStorage.removeItem('token')
//     localStorage.removeItem('user')

//     // フォームを再表示
//     isLoggedIn.value = false
//     formData.email = ''
//     formData.password = ''
//     showMessage('ログアウトしました', false)
//   }
// }
</script>

<template>
  <div>
    <main>
      <h1>商品一覧</h1>
      <div v-for="toy in toys" :key="toy.id">
        <img :src="toy.image_url" alt="" width="100" height="100">
        ID: {{ toy.id }} 商品名: {{ toy.name }} 価格: ¥{{ toy.price }} 
        <a :href="`/toyzamas/toys/${toy.id}`">詳細</a>
      </div>
      <form @submit.prevent="loadToys">
        <label>
          <input type="radio" name="sort" value=1 v-model="sort"> 予約対象商品一覧
        </label>
        <p>現在のソート値: {{ sort }}</p>
        <button type="submit">ソート実行</button>
      </form>
      <p><a href="/toyzamas/toys">ソートリセット</a></p>
    </main>
  </div>
</template>