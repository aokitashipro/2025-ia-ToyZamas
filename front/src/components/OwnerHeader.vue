共通ヘッダ

<template>
  <!-- <header class="header"> -->
  <!-- <header class="header"> -->
  <header class="bg-blue-600 text-white p-4 flex justify-between">
    <h1>トいざ舛管理者ページ</h1>
    <nav>
      <RouterLink to="/owner">ホーム</RouterLink>
      <RouterLink to="/owner/toys/create">商品管理</RouterLink>
      <RouterLink to="/owner/stocks">仕入れ管理</RouterLink>
      <RouterLink to="/owner/categories">カテゴリ管理</RouterLink>
      <RouterLink to="/owner/reserve">電話予約</RouterLink>
      <RouterLink to="/owner/users">ユーザ一覧</RouterLink>
      <RouterLink to="/owner/sales-analysis">売上分析</RouterLink>
      <button @click="handleLogout">ログアウト</button>
    </nav>
  </header>
</template>

<script setup>
import { RouterLink } from 'vue-router';
// import { useRouter } from 'vue-router';


//useAuthStore:認証状態（トークン・ログイン）の管理用ストア
const handleLogout = async () => {
  try {
    await fetch('/api/logout', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
    });
  } catch (error) {
    console.error('ログアウト失敗:', error);
  } finally {
    authStore.logout();
    router.push('/login');
  }
};
</script>

<style scoped>
.header {
  background-color: #333;
  color: white;
  padding: 1em;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

nav a,
nav button {
  margin: 0 10px;
  color: white;
  background: none;
  border: none;
  cursor: pointer;
}

nav a:hover,
nav button:hover {
  text-decoration: underline;
}
</style>