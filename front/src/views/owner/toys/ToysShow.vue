<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'
    import { useRoute } from 'vue-router'

    const loading = ref(true)
    const error = ref(null)
    const toy = ref(null)

    const route = useRoute()
    const toyId = route.params.toy

    const laravel_base_url = 'http://127.0.0.1:8000/storage/'
    const img = ref(null)

    async function getToys(toyId){  
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
    <div>
        <h1>商品詳細</h1>
        <div v-if="loading">
            商品情報を読み取り中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
            <RouterLink :to="'/owner/toys'">商品一覧へ戻る</RouterLink>
          <p>商品名: {{ toy.name }}</p>
          <p>値段: {{ toy.price }}</p>
          <p>在庫: {{ toy.stock }}</p>
          <p>商品説明: {{ toy.information }}</p>
          <p>カテゴリ名: {{ toy.category_name }}</p>
          <p>カテゴリ人気度: {{ toy.category_pop }}</p>
          <p>シリーズ名: {{ toy.series_name }}</p>
          <p>シリーズ人気度: {{ toy.series_pop }}</p>
          <p>販売状況: {{ toy.is_selling }}</p>
          <p>予約可否: {{ toy.is_reserve }}</p>
          <p>発売日:{{ toy.release_date }}</p>
          <p>
            <img v-if="toy.image_url" :src="img" alt="toy image">
          </p>
          <p>
            <RouterLink :to="`/owner/toys/${toyId}/edit`">商品データ編集</RouterLink>
          </p>
        </div>
    </div>
</template>