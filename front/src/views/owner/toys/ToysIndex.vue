<<<<<<< HEAD
<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'

    const loading = ref(true)
    const error = ref(null)

    const toys = ref([])

    async function getAllToys(){
        try{
            loading.value = true
            error.value = null

            const response = await apiClient.get('/owner/toys')
            toys.value = response.data
        }catch(err){
            console.log('商品情報の取得に失敗:', err)
            error.value = '商品情報の取得に失敗しました'
        }finally{
            loading.value = false
        }
    }

    onMounted(() => 
        getAllToys() 
    )
</script>

<template>
    <div>
        <h1>登録済み商品一覧</h1>
        <div v-if="loading">
            商品情報を読み取り中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
            <table border="1">
                <tr>
                    <td>商品名</td>
                    <td>値段</td>
                    <td>カテゴリ名</td>
                    <td>シリーズ名</td>
                    <td>在庫</td>
                    <td>販売状況</td>
                    <td>予約可否</td>
                </tr>
                <tr v-for="toy in toys" :key="toy.id">
                    <td>{{ toy.name }}</td>
                    <td>{{ toy.price }}</td>
                    <td>{{ toy.category }}</td>
                    <td>{{ toy.series }}</td>
                    <td>{{ toy.stock }}</td>
                    <td>{{ toy.is_selling }}</td>
                    <td>{{ toy.is_reserve }}</td>
                </tr>
            </table>
        </div>
    </div>
=======
<script setup>
</script>

<template>
  <div>ToysIndex</div>
>>>>>>> 1136c6c85baab785266d16af69c398ba3e3a6636
</template>