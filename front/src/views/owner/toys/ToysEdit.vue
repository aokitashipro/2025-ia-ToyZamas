<script setup>
    import {ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api'
    import { useRoute } from 'vue-router'

    const loading = ref(true)
    const error = ref(null)

    let toy = ref(null)
    const categories = ref([])
    const current_category = ref(null)
    const series = ref([])
    const current_series = ref(null)

    const route = useRoute()
    const toyId = route.params.toy

    async function getToys(toyId){  
      try{
        loading.value = true
        error.value = null

        //toyの情報を取得
        const response = await apiClient.get(`/owner/toys/${toyId}`)
        toy.value = response.data

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
        loading.value = false
      }
    }

    // async function getCategories(){
    //     try{

    //         console.log(response_categories.data);
    //         console.log(categories.value);
    //     }catch(err){
    //         console.log('カテゴリ名の取得に失敗:', err)
    //         error.value = 'カテゴリ名一覧の取得に失敗しました'
    //     }
    // }

    // async function getSeries(){
    //     try{
    //     const response_series = await apiClient.get('/owner/series')
    //     series.value = response_series.data
    //     }catch(err){
    //         console.log('シリーズ名の取得に失敗:', err)
    //         error.value = 'シリーズ名一覧の取得に失敗しました'
    //     }finally{
    //         loading.value = false
    //     }
    // }
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
        <div v-if="loading">
            商品情報を読み取り中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
          <RouterLink :to="'/owner/toys'">商品一覧へ戻る</RouterLink><br><br>
          <p>
            商品名: 
            <input type="text" v-model="toy.name" name="name">
          </p>
          
          <p>
            値段:
            <input type="number" v-model="toy.price" name="price" min="0">
          </p>
          
          <p>
            在庫: 
            <input type="number" v-model="toy.stock" name="stock" min="0">
          </p>
          
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
              <input type="radio" v-model="toy.is_selling" id="selling_now" name="is_selling" value="true" checked="checked">
              <label for="can_selling">販売中</label>
              <input type="radio" v-model="toy.is_selling" id="not_selling" name="is_selling" value="false">
              <label for="cant_selling">販売中止</label>
            </div>
            <!-- 販売中止の場合は販売中止にチェックが入っている -->
            <div v-else>
              <input type="radio" v-model="toy.is_selling" id="selling_now" name="is_selling" value="true">
              <label for="can_selling">販売中</label>
              <input type="radio" v-model="toy.is_selling" id="not_selling" name="is_selling" value="false" checked="checked">
              <label for="cant_selling">販売中止</label>
            </div>
          </p>
          
          <p>
            予約状況：
            <!-- 予約可能状況なら予約可能にチェックが入っている -->
            <div v-if="toy.is_reserve">
              <input type="radio" v-model="toy.is_reserve" id="can_reserve" name="is_reserve" value="true" checked="checked">
              <label for="can_reserve">予約可能</label>
              <input type="radio" v-model="toy.is_reserve" id="cant_reserve" name="is_reserve" value="false">
              <label for="cant_reserve">予約不可</label>
            </div>
            <!-- 予約不可状況なら予約不可にチェックが入っている -->
            <div v-else>
              <input type="radio" v-model="toy.is_reserve" id="can_reserve" name="is_reserve" value="true">
              <label for="can_reserve">予約可能</label>
              <input type="radio" v-model="toy.is_reserve" id="cant_reserve" name="is_reserve" value="false" checked="checked">
              <label for="cant_reserve">予約不可</label>
            </div>
            
          </p>
          <p>
            発売日:
            <input type="text" v-model="toy.release_date" name="release_date">
          </p>

          <input type="button" @click="toyUpdate" value="商品情報を更新">
          <input type="button" @click="toyDelete" value="商品を削除">
        </div>
    </div>
</template>