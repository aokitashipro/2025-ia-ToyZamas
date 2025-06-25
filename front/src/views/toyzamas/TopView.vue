<script setup>
// ユーザーIDの取得
const user = JSON.parse(localStorage.getItem('user'));
// ログアウト処理
const handleLogout = async () => {
  try {
    // APIでログアウト
    await apiClient.post('/logout', {})
  } catch (error) {
    console.error('ログアウトAPIエラー:', error)
  } finally {
    // ローカルストレージをクリア
    localStorage.removeItem('token')
    localStorage.removeItem('user')

    // フォームを再表示
    isLoggedIn.value = false
    formData.email = ''
    formData.password = ''
    showMessage('ログアウトしました', false)
  }
}
</script>

<template>
    <header>
        
        <h1>ユーザートップ画面</h1>
        <div>ロゴ</div>
        <div>検索ボタン</div>
        <div>購入履歴ボタン</div>
        <div>お気に入りボタン</div>
        <div>カートボタン</div>
        <button @click="handleLogout">ログアウト</button>
        <router-link :to="`/toyzamas/toys`">商品一覧</router-link>
    </header>
    <footer>

    </footer>
</template>