<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'
    import { useRoute, useRouter } from 'vue-router'

    const loadingGettoys = ref(true)
    const loadingUpdate = ref(false)
    const loadingDelete = ref(false)
    const error = ref(null)

    let toy = ref(null)
    const categories = ref([])
    const current_category = ref(null)
    const series = ref([])
    const current_series = ref(null)

    const laravel_base_url = 'http://127.0.0.1:8000/storage/'
    const img = ref(null)
    const fileInput = ref(null)
    const image_url = ref(null)

    const route = useRoute()
    const router = useRouter()
    const toyId = route.params.id


    async function getToys(toyId){  
      try{
        loadingGettoys.value = true
        loadingUpdate.value = false
        loadingDelete.value = false
        error.value = null

        //toyの情報を取得
        const response = await apiClient.get(`/owner/toys/${toyId}`)
        toy.value = response.data

        img.value = laravel_base_url + toy.value.image_url

        //category一覧を取
        const response_categories = await apiClient.get('/owner/categories')
        categories.value = response_categories.data
        //toyが持っている現在のカテゴリ情報を取得
        categories.value.forEach(function(category){
          if(category.id === toy.value.category_id){
            current_category.value = category
          }
          console.log(category)
          console.log(current_category.value)
        })

        //series一覧を取得
        const response_series = await apiClient.get('/owner/series')
        series.value = response_series.data
        //toyが持っている現在のシリーズ情報を取得
        series.value.forEach(function(a_series){
          if(a_series.id === toy.value.series_id){
            current_series.value = a_series
          }
        })
      }catch(err){
          console.log('商品情報の取得に失敗:', err)
          error.value = '商品情報の取得に失敗しました'
      }finally{
        loadingGettoys.value = false
      }
    }

    async function toyUpdate(toy, current_category, current_series, toyId){
      try{
        
        loadingGettoys.value = false
        loadingUpdate.value = true
        loadingDelete.value = false
        error.value = null


        //選択されているカテゴリとシリーズのidをtoyに格納
        toy.category_id = current_category.id
        toy.series_id = current_series.id

        //is_sellingとis_reserveの値をtrue : falseに変更
        if(toy.is_selling === '販売中'){
          toy.is_selling = true
        }else if(toy.is_selling === '販売中止'){
          toy.is_selling = false
        }

        if(toy.is_reserve === '予約可能'){
          toy.is_reserve = true
        }else if(toy.is_reserve === '予約不可'){
          toy.is_reserve = false
        }

        if(fileInput.value.files[0]){
          toy.image_url = fileInput.value.files[0]
        }
        
        console.log(toy.image_url)

        const formData = new FormData()
        formData.append('name', toy.name)
        formData.append('information', toy.information)
        formData.append('price', toy.price)
        formData.append('category_id', toy.category_id)
        formData.append('series_id', toy.series_id)
        formData.append('image_url', toy.image_url)
        formData.append('is_selling', toy.is_selling ? 1 : 0)
        formData.append('is_reserve', toy.is_reserve ? 1 : 0)
        formData.append('release_date', toy.release_date)

        const response = await apiClient.post(`/owner/toys/${toyId}/update`, formData)

        alert('商品情報の更新に成功しました！商品詳細ページに戻ります')
        router.push(`/owner/toys/${toyId}`)
      }catch(err){
        console.log('商品情報の更新に失敗:', err)
        error = '商品情報の更新に失敗しました'
      }finally{
        loadingUpdate.value = false
      }
    }

    async function toyDelete(){
      try{
        loadingGettoys.value = false
        loadingUpdate.value = false
        loadingDelete.value = true
        error.value = null

        const response = await apiClient.delete(`/owner/toys/${toyId}`)
        alert('商品を削除しました')
        router.push('/owner/toys')
      }catch(err){
        console.log('商品情報の削除に失敗:', err)
        error = '商品情報の削除に失敗しました'
      }finally{
        loadingDelete.value = false
      }
    }

    onMounted(() => 
        {
          getToys(toyId) 
          // getCategories()
          // getSeries()
        }
    )
</script>

<template>
    <div>
        <h1>商品情報編集ページ</h1>
        <div v-if="loadingGettoys">
          <div class="loader"></div>
        </div>

        <div v-else-if="loadingUpdate">
          <div class="loader"></div>
        </div>

        <div v-else-if="loadingDelete">
          <div class="loader"></div>
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
          <RouterLink :to="`/owner/toys/${toyId}`">商品詳細へ戻る</RouterLink><br><br>
          <form @submit.prevent="toyUpdate(toy, current_category, current_series, toyId)" enctype="multipart/form-data" class="toy-form">
            <div class="form-row">
              <label for="name">商品名：</label>
              <input type="text" v-model="toy.name" name="name" id="name">
            </div>
            <div class="form-row">
              <label for="price">値段：</label>
              <input type="number" v-model="toy.price" name="price" id="price" min="0">
            </div>
            <!-- 在庫は仕入れか予約、購入のいずれかでしか変動できないものとする  
            <div class="form-row">
              <label for="stock">在庫：</label>
              <input type="number" v-model="toy.stock" name="stock" id="stock" min="0">
            </div> -->
            <div class="form-row">
              <label for="information">商品説明：</label>
              <input type="text" v-model="toy.information" name="information" id="information" max="255">
            </div>
            <div class="form-row">
              <label for="category_id">カテゴリ名：</label>
              <select v-model="current_category" id="category_id">
                <option v-for="category in categories" :value="category">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div class="form-row">
              <label for="series_id">シリーズ名：</label>
              <select v-model="current_series" id="series_id">
                  <option v-for="a_series in series" :value="a_series">
                    {{ a_series.name }}
                  </option>
              </select>
            </div>
            <div class="form-row">
              <label>販売状況：</label>
              <input type="radio" v-model="toy.is_selling" id="can_selling" name="is_selling" value="販売中">
              <label for="can_selling" class="radio-label">販売中</label>
              <input type="radio" v-model="toy.is_selling" id="cant_selling" name="is_selling" value="販売中止">
              <label for="cant_selling" class="radio-label">販売中止</label>
            </div>
            <div class="form-row">
              <label>予約状況：</label>
              <input type="radio" v-model="toy.is_reserve" id="can_reserve" name="is_reserve" value="予約可能">
              <label for="can_reserve" class="radio-label">予約可能</label>
              <input type="radio" v-model="toy.is_reserve" id="cant_reserve" name="is_reserve" value="予約不可">
              <label for="cant_reserve" class="radio-label">予約不可</label>
            </div>
            <div class="form-row">
              <label for="release_date">発売日：</label>
              <input type="text" v-model="toy.release_date" name="release_date" id="release_date">
            </div>
            <div class="form-row">
              <label for="image">画像データ：</label>
              <input type="file" name="image" ref="fileInput" id="image">
            </div>
            <div class="form-row">
              <label>現在の画像：</label>
              <div>
                <img v-if="toy.image_url" :src="img" alt="toy image" height="200">
                <div v-else>
                  登録されていません
                </div>
              </div>
            </div>
            <div class="form-row">
              <input type="submit" value="商品情報を更新" class="submit-btn">
            </div>
          </form>
          <input type="button" @click="toyDelete" value="商品を削除" class="delete-btn">
        </div>
    </div>
</template>

<style scoped>
.toy-form {
  max-width: 500px;
  margin: 0 auto;
  background: #f9f9fc;
  padding: 2em 1.5em;
  border-radius: 10px;
}
.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 1em;
}
.form-row label {
  width: 110px;
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.form-row input[type="text"],
.form-row input[type="number"],
.form-row input[type="file"],
.form-row select {
  flex: 1;
  padding: 0.25rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.form-row input[type="radio"] {
  margin-right: 0.2em;
}
.radio-label {
  margin-left: 0;
  font-weight: normal;
  margin-right: 1em;
}
.submit-btn {
  margin-left: auto;
  padding: 0.5em 2em;
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
}
.submit-btn:hover {
  background: #226bb3;
}
.delete-btn {
  margin: 2em auto 0 auto;
  display: block;
  padding: 0.5em 2em;
  background: #e53e3e;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
}
.delete-btn:hover {
  background: #b91c1c;
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