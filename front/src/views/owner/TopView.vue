<script setup>

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
    <div>
        a
    </div>
</template>