<template>
  <div>
    <main>
      <h1>商品一覧</h1>
      <div v-for="toy in toys" :key="toy.id">
        <img :src="toy.image_url" alt="" width="100" height="100">
        商品名: {{ toy.name }} 価格: ¥{{ toy.price }} 
        <a :href="`/toyzamas/toys/${toy.id}`">詳細</a>
        <!-- <a :href="`/toys/edit.html?id=${toy.id}`">編集</a> -->
      </div>
    </main>
  </div>
</template>

<script>
import { apiClient } from "../../../utils/api.js";

export default {
  data() {
    return {
      toys: [], // データ格納用
    };
  },

  async mounted() {
    await this.loadtoys();
  },
  methods: {
    async loadtoys() {
      try {
        const response = await apiClient.get("/toyzamas/toys"); // LaravelのAPIエンドポイントに一致させる
        this.toys = response.data; // JSONレスポンスのdataプロパティを参照
        console.log("レスポンス内容:", response); // レスポンス全体を確認
        console.log("データ部分:", response.data); // JSONデータを確認
        console.log("toys部分:", this.toys); // JSONデータを確認

      } catch (error) {
        console.error("データの取得に失敗:", error);
      }
    },
  },
};
</script>

