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
      <template v-if="isOwnerRoute">
        <!-- 管理者ヘッダー -->
        <router-link to="/owner">トップ</router-link>
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
// import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router'
import { computed } from 'vue'

//	現在のルート情報を取得する
const route = useRoute()
// 管理者用ルートのとき true
const isOwnerRoute = computed(() => {
  return route.path.startsWith('/owner')//現在のURLが /owner で始まる場合は管理者と判定させる
})

</script>

<style scoped>
.header {
  background-color: #338fe5;
  color: rgb(211, 196, 196);
  padding: 1em;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

nav a,
nav button {
  margin: 0 10px;
  color: #222;
  background: none;
  border: none;
  cursor: pointer;
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
</style>