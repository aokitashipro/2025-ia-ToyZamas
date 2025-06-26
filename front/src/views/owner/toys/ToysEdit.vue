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

    const route = useRoute()
    const router = useRouter()
    const toyId = route.params.toy


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
            商品情報を読み取り中...
        </div>

        <div v-else-if="loadingUpdate">
          商品情報を更新中...
        </div>

        <div v-else-if="loadingDelete">
          商品情報を削除中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
          <RouterLink :to="`/owner/toys/${toyId}`">商品詳細へ戻る</RouterLink><br><br>
          <form @submit.prevent="toyUpdate(toy, current_category, current_series, toyId)" enctype="multipart/form-data">
            <p>
              商品名: 
              <input type="text" v-model="toy.name" name="name">
            </p>
            
            <p>
              値段:
              <input type="number" v-model="toy.price" name="price" min="0">
            </p>
            
  <!--      在庫は仕入れか予約、購入のいずれかでしか変動できないものとする  
            <p>
              在庫: 
              <input type="number" v-model="toy.stock" name="stock" min="0">
            </p> -->
            
            <p>
              商品説明:
              <input type="text" v-model="toy.information" name="information" max="255">
            </p>
            
            <p>
              カテゴリ名：
              <select v-model="current_category">
                <option v-for="category in categories" :value="category">
                  {{ category.name }}
                </option>
              </select>
            </p>
            
            <p>
              シリーズ名：
              <select v-model="current_series" >
                  <option v-for="a_series in series" :value="a_series">
                    {{ a_series.name }}
                  </option>
              </select>
            </p>

            <p>
              販売状況：
              <!-- 販売中の場合は販売中にチェックが入っている -->
              <div v-if="toy.is_selling">
                <input type="radio" v-model="toy.is_selling" id="can_selling" name="is_selling" value="販売中" checked="checked">
                <label for="can_selling">販売中</label>
                <input type="radio" v-model="toy.is_selling" id="cant_selling" name="is_selling" value="販売中止" >
                <label for="cant_selling">販売中止</label>
              </div>
              <!-- 販売中止の場合は販売中止にチェックが入っている -->
              <div v-else>
                <input type="radio" v-model="toy.is_selling" id="can_selling" name="is_selling" value="販売中">
                <label for="can_selling">販売中</label>
                <input type="radio" v-model="toy.is_selling" id="cant_selling" name="is_selling" value="販売中止" checked="checked">
                <label for="cant_selling">販売中止</label>
              </div>
            </p>
            
            <p>
              予約状況：
              <!-- 予約可能状況なら予約可能にチェックが入っている -->
              <div v-if="toy.is_reserve">
                <input type="radio" v-model="toy.is_reserve" id="can_reserve" name="is_reserve" value="予約可能" checked="checked">
                <label for="can_reserve">予約可能</label>
                <input type="radio" v-model="toy.is_reserve" id="cant_reserve" name="is_reserve" value="予約不可">
                <label for="cant_reserve">予約不可</label>
              </div>
              <!-- 予約不可状況なら予約不可にチェックが入っている -->
              <div v-else>
                <input type="radio" v-model="toy.is_reserve" id="can_reserve" name="is_reserve" value="予約可能">
                <label for="can_reserve">予約可能</label>
                <input type="radio" v-model="toy.is_reserve" id="cant_reserve" name="is_reserve" value="予約不可" checked="checked">
                <label for="cant_reserve">予約不可</label>
              </div>
              
            </p>
            <p>
              発売日:
              <input type="text" v-model="toy.release_date" name="release_date">
            </p>
            <p>
              画像データ：
              <input type="file" name="image" ref="fileInput">
              <br>現在の画像:
              <div>
                <img v-if="toy.image_url" :src="img" alt="toy image" width="200" height="150">
                <div v-else>
                  登録されていません
                </div>
              </div>
            </p>
            <input type="submit" value="商品情報を更新">
          </form>
          <input type="button" @click="toyDelete" value="商品を削除">
        </div>
    </div>
</template>