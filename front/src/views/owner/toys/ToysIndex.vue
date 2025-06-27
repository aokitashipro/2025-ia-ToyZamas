<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'
    import { useRouter } from 'vue-router' 

    const loading = ref(true)
    const error = ref(null)

    const toys = ref([])
    const sort = ref(null)

    async function getToys(){
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

    async function sortDo(sort){
        try{
            loading.value = true
            error.value = null

            const postData = {'sort' : sort}
            console.log(postData)

            const response = await apiClient.post('/owner/toys/sort', postData)
            toys.value = response.data
            console.log(response)
        }catch(err){
            console.log('商品情報の取得に失敗:', err)
            error.value = '商品情報の取得に失敗しました'
        }finally{
            loading.value = false
        }
    }

    onMounted(() => 
        getToys() 
    )
</script>

<template>
    <div class="page-container">
        <h1>登録済み商品一覧</h1>
        <div v-if="loading">
            商品情報を読み取り中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
            <RouterLink :to="'/owner/toys/create'">商品を新規登録</RouterLink>
            <div>
                <h4>ソート:</h4>
                <select v-model="sort">
                    <option value="price_high">値段の高い順</option>
                    <option value="price_low">値段の低い順</option>
                    <option value="stock_much">在庫多い順</option>
                    <option value="stock_little">在庫少ない順</option>
                </select>
                <input type="button" @click="sortDo(sort)" value="ソートを実行">
            </div>
            <table border="1">
                <tr class="culm_name">
                    <td>商品名</td>
                    <td>値段</td>
                    <td>カテゴリ名</td>
                    <td>シリーズ名</td>
                    <td>在庫</td>
                    <td>販売状況</td>
                    <td>予約可否</td>
                </tr>
                <tr v-for="toy in toys" :key="toy.id">
                    <td><RouterLink :to="`/owner/toys/${toy.id}`">
                        {{ toy.name }}
                    </RouterLink></td>
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
</template>

<style scoped>
.page-container {
  max-width: 1200px;
  margin: 0 auto;
  padding-left: 2em;
  padding-right: 2em;
}
.table-wrapper {
  width: 100%;
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed; /* 追加：列幅を均等に */
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  word-break: break-all; /* 長い文字列も折り返す */
}
body, html, .table-wrapper {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
</style>