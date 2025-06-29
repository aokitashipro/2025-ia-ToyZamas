<script setup>
  import { ref, onMounted} from 'vue'
  import { apiClient } from '@/utils/api';
  import { useRouter } from 'vue-router';

  let name = ref(null);
  let information = ref(null);
  let price = ref(0);
  let is_selling = ref(false);
  let is_reserve = ref(false);
  let category_id = ref(0);
  let series_id = ref(0);
  let image_url = ref(null);
  let stock = ref(0);
  let release_date = ref(null);

  const categories = ref([])
  const series = ref([])

  const loading = ref(true)
  const loadingStore = ref(false)
  const error = ref(null)
  const router = useRouter()

  const fileInput = ref(null)


  async function getCategories(){
    try{
        loading.value = true
        loadingStore.value = false
        error.value = null

        const response_categories = await apiClient.get('/owner/categories')
        categories.value = response_categories.data
        console.log(categories.value)
    }catch(err){
        console.log('カテゴリ名の取得に失敗:', err)
        alert('カテゴリ名一覧の取得に失敗しました')
    }
  }

  async function getSeries(){
    try{
    const response_series = await apiClient.get('/owner/series')
    series.value = response_series.data
    }catch(err){
        console.log('シリーズ名の取得に失敗:', err)
        error.value = 'シリーズ名一覧の取得に失敗しました'
    }finally{
        loading.value = false
    }
  }

  async function toyStore(){
      try{
          loading.value = false
          loadingStore.value = true
          error.value = null

          //image_urlに画像情報を保存
          if(fileInput.value.files[0]){
            image_url.value = fileInput.value.files[0]
          }else{
            image_url.value =  null
          }

          console.log(image_url.value)

          const formData = new FormData()
          formData.append('name', name.value)
          formData.append('information', information.value)
          formData.append('price', price.value)
          formData.append('is_selling', is_selling.value ? 1 : 0)
          formData.append('is_reserve', is_reserve.value ? 1 : 0)
          formData.append('category_id', category_id.value)
          formData.append('series_id', series_id.value)
          formData.append('image_url', image_url.value)
          formData.append('stock', stock.value)
          formData.append('release_date', release_date.value)

          const response = await apiClient.post('/owner/toys', formData)

          alert('商品を登録しました')
          router.push('/owner/toys')
      }catch(err){
          console.log('商品の登録に失敗：', err)
          error.value = '商品の登録に失敗しました'
      }finally{
          loadingStore.value = false
      }
  }

  onMounted(() =>{
      getCategories()
      getSeries()
      }
  )

</script>

<template>
  <div>
    <div v-if="loading">
      <div class="loader"></div>
    </div>

    <div v-else-if="loadingStore">
      <div class="loader"></div>
    </div>

    <div v-else-if="error">
      エラー：{{ error }}
    </div>

    <div v-else>
      <RouterLink :to="'/owner/toys'">商品一覧へ戻る</RouterLink><br><br>
      <form @submit.prevent="toyStore()" enctype="multipart/form-data" class="toy-form">
        <h1>新規商品登録</h1>
        <div class="form-row">
          <label for="name">商品名：</label>
          <input type="text" v-model="name" id="name" name="name" max="255" placeholder="商品名を入力してください">
        </div>
        <div class="form-row">
          <label for="information">商品説明：</label>
          <input type="text" v-model="information" id="information" name="information" placeholder="商品説明を入力してください">
        </div>
        <div class="form-row">
          <label for="price">値段：</label>
          <input type="number" v-model="price" id="price" name="price" min="0" placeholder="値段を入力してください">
        </div>
        <div class="form-row">
          <label for="category_id">カテゴリ名：</label>
          <select v-model="category_id" id="category_id">
            <option v-for="category in categories" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div class="form-row">
          <label for="series_id">シリーズ名：</label>
          <select v-model="series_id" id="series_id">
            <option v-for="a_series in series" :value="a_series.id">
              {{ a_series.name }}
            </option>
          </select>
        </div>
        <div class="form-row">
          <label>販売状況：</label>
          <input type="radio" v-model="is_selling" id="can_selling" name="is_selling" value="true" checked="checked">
          <label for="can_selling" class="radio-label">販売中</label>
          <input type="radio" v-model="is_selling" id="cant_selling" name="is_selling" value="false">
          <label for="cant_selling" class="radio-label">販売中止</label>
        </div>
        <div class="form-row">
          <label>予約状況：</label>
          <input type="radio" v-model="is_reserve" id="can_reserve" name="is_reserve" value="true" checked="checked">
          <label for="can_reserve" class="radio-label">予約可能</label>
          <input type="radio" v-model="is_reserve" id="cant_reserve" name="is_reserve" value="false">
          <label for="cant_reserve" class="radio-label">予約不可</label>
        </div>
        <div class="form-row">
          <label for="image">画像データ：</label>
          <input type="file" name="image" ref="fileInput" id="image">
        </div>
        <div class="form-row">
          <label for="stock">在庫：</label>
          <input type="number" v-model="stock" id="stock" name="stock" min="0" placeholder="在庫を入力してください">
        </div>
        <div class="form-row">
          <label for="release_date">発売日：</label>
          <input type="text" v-model="release_date" id="release_date" name="release_date" placeholder="yyyy-mm-dd形式で入力してください">
        </div>
        <div class="form-row">
          <input type="submit" value="商品登録" class="submit-btn">
        </div>
      </form>
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
</style>