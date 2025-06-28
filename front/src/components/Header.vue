共通ヘッダ

<template>
  <header class="header">
    <div class="flex items-center space-x-3">
      <!-- ロゴとタイトルをクリック可能にする -->
      <router-link :to="isOwnerRoute ? '/owner' : '/toyzamas/toys'" class="flex items-center space-x-2">
        <!-- ロゴ画像 -->
        <img src="@/assets/logo_toyzamas_frame.svg" alt="ToyZamas Logo" style="height: 80px;" />
        <!-- タイトル
        <h1 class="text-xl font-bold">
          {{ isOwnerRoute ? 'ToyZamas 管理画面' : 'ToyZamas ECサイト' }}
        </h1> -->
      </router-link>
    </div>
    <nav>
      <template v-if="isLoginRoute">
        <!-- ログイン用ヘッダー -->
        <router-link to="/login">ログイン</router-link>
        <router-link to="/register">新規登録</router-link>
      </template>
      <template v-else-if="isOwnerRoute">
        <!-- 管理者ヘッダー -->
        <span class="user-name" v-if="userName">管理者：{{ userName }}</span>
        <router-link to="/owner/toys">商品</router-link>
        <router-link to="/owner/categories">カテゴリ/シリーズ</router-link>
        <router-link to="/owner/reserve">電話予約</router-link>
        <router-link to="/owner/stocks">在庫</router-link>
        <router-link to="/owner/sales-analysis">売上分析</router-link>
        <router-link to="/owner/users">ユーザー管理</router-link>
        <router-link to="/logout">ログアウト</router-link>
      </template>
      <template v-else>
        <!-- ユーザーヘッダー -->
        <span class="user-name" v-if="userName">ログイン:{{ userName }}さん</span>
        <router-link to="/toyzamas/toys">商品一覧</router-link>
        <router-link to="/toyzamas/cart">カート</router-link>
        <router-link to="/toyzamas/buy/history">購入履歴</router-link>
        <router-link to="/toyzamas/favorites">お気に入り</router-link>
        <router-link to="/logout">ログアウト</router-link>
      </template>
    </nav>
  </header>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import { useRoute } from 'vue-router'
import { computed, ref, onMounted } from 'vue'

//	現在のルート情報を取得する
const route = useRoute()
// 管理者用ルートのとき true
const isOwnerRoute = computed(() => {
  return route.path.startsWith('/owner')//現在のURLが /owner で始まる場合は管理者と判定させる
})
const isLoginRoute = computed(() => {
  return route.path === '/login' || route.path === '/logout' || route.path === '/register'
})

// ユーザーネーム取得（localStorageの"user"を想定）
const userName = ref('')
onMounted(() => {
  try {
    const user = JSON.parse(localStorage.getItem('user'))
    userName.value = user?.name || ''
  } catch {
    userName.value = ''
  }
})
</script>

<style scoped>
.header {
  background-color: #338fe5;
  color: rgb(211, 196, 196);
  padding: 0em 1em;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2em;
  min-height: 36px;
}

nav a,
nav button {
  margin: 0 10px;
  color: #ffffff;
  background: none;
  border: none;
  cursor: pointer;
}

.header img {
  margin-top: 1em;
  height: 50px !important;
}

nav a:hover,
nav button:hover {
  text-decoration: underline;
}

a {
  text-decoration: none;
  color: white;
}

a.router-link-active {
  font-weight: bold;
  /* border-bottom: 2px solid white; */
}

.user-name {
  margin-right: 1em;
  color: #fff;
  font-weight: bold;
}
</style>