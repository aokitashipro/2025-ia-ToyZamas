<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'
    import { useRoute } from 'vue-router'

    const loading = ref(true)
    const error = ref(null)
    const toy = ref(null)

    const route = useRoute()
    const toyId = route.params.id

    const laravel_base_url = 'http://127.0.0.1:8000/storage/'
    const img = ref(null)

    async function getToys(toyId){  
        console.log(toyId)
      try{
            loading.value = true
            error.value = null

            const response = await apiClient.get(`/owner/toys/${toyId}`)
            toy.value = response.data

            img.value = laravel_base_url + toy.value.image_url
            
        }catch(err){
            console.log('商品情報の取得に失敗:', err)
            error.value = '商品情報の取得に失敗しました'
        }finally{
            loading.value = false
        }
    }

    // async function getImage(){
    //     try{
    //         const 
    //     }catch{
    //         console.log('画像情報の取得に失敗:', err)
    //         error.value = '画像情報の取得に失敗しました'
    //     }finally{
    //         loading.value = false
    //     }
    // }

    onMounted(() => {
        getToys(toyId) 
        }
    )
</script>

<template>
  <div class="toy-detail-container">
    <h1 class="title">商品詳細</h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-else-if="error">
      <div class="error-message">エラー：{{ error }}</div>
    </div>
    <div v-else>
      <RouterLink to="/owner/toys" class="back-link">← 商品一覧へ戻る</RouterLink>
      <div class="toy-detail-flex">
        <div class="toy-image-area">
          <img v-if="toy.image_url" :src="img" alt="toy image" class="toy-image" />
          <div v-else class="no-image">登録されている画像はありません</div>
        </div>
        <div class="toy-info">
          <table class="info-table">
            <tbody>
              <tr>
                <th>商品名</th>
                <td>{{ toy.name }}</td>
              </tr>
              <tr>
                <th>値段</th>
                <td>¥{{ toy.price }}</td>
              </tr>
              <tr>
                <th>在庫</th>
                <td>{{ toy.stock }}</td>
              </tr>
              <tr>
                <th>商品説明</th>
                <td>{{ toy.information }}</td>
              </tr>
              <tr>
                <th>カテゴリ名</th>
                <td>{{ toy.category_name }}</td>
              </tr>
              <tr>
                <th>カテゴリ人気度</th>
                <td>{{ toy.category_pop }}</td>
              </tr>
              <tr>
                <th>シリーズ名</th>
                <td>{{ toy.series_name }}</td>
              </tr>
              <tr>
                <th>シリーズ人気度</th>
                <td>{{ toy.series_pop }}</td>
              </tr>
              <tr>
                <th>販売状況</th>
                <td>{{ toy.is_selling }}</td>
              </tr>
              <tr>
                <th>予約可否</th>
                <td>{{ toy.is_reserve }}</td>
              </tr>
              <tr>
                <th>発売日</th>
                <td>{{ toy.release_date }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="edit-link-area">
        <RouterLink :to="`/owner/toys/${toyId}/edit`" class="edit-link">商品データ編集</RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.toy-detail-container {
  max-width: 700px;
  margin: 2em auto;
  padding: 0em 1.5em;
}
.title {
  text-align: center;
  color: #338fe5;
  margin-bottom: 1.5em;
}
.back-link {
  display: inline-block;
  margin-bottom: 1em;
  color: #338fe5;
  text-decoration: none;
}
.back-link:hover {
  text-decoration: underline;
}
.toy-detail-flex {
  display: flex;
  gap: 2em;
  align-items: flex-start;
}
.toy-image-area {
  flex-shrink: 0;
}
.toy-image {
  width: 150px;
  height: auto;
  border-radius: 8px;
  border: 1px solid #e0e7ef;
  background: #f8fafc;
}
.no-image {
  width: 150px;
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  color: #aaa;
  border: 1px solid #e0e7ef;
  border-radius: 8px;
  font-size: 0.95em;
}
.toy-info {
  flex: 1;
}
.info-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0.5em;
  background: #fafcff;
}
.info-table th, .info-table td {
  border: 1px solid #e0e7ef;
  padding: 8px 12px;
  text-align: left;
  vertical-align: top;
}
.info-table th {
  background: #eaf4fc;
  color: #338fe5;
  width: 30%;
  font-weight: bold;
}
.info-table td {
  background: #fff;
}
.edit-link-area {
  text-align: right;
}
.edit-link {
  background: #338fe5;
  color: #fff;
  padding: 0.5em 1.2em;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.2s;
}
.edit-link:hover {
  background: #1c6fc1;
}
.error-message {
  color: #f44336;
  text-align: center;
  margin: 1em 0;
  font-weight: bold;
}
.loader {
  border: 6px solid #eee;
  border-top: 6px solid #338fe5;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
  margin: 2em auto;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>