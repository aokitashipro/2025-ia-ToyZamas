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
        }catch(err){
            console.log('カテゴリ名の取得に失敗:', err)
            error.value = 'カテゴリ名一覧の取得に失敗しました'
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

            image_url.value = fileInput.value.files[0]

            const postData = {
                name, information, price, is_selling, is_reserve, category_id, series_id, image_url, stock, release_date 
            }
            const response = await apiClient.post('/toys', postData)

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
            カテゴリデータ、シリーズデータを取得中...
        </div>

        <div v-else-if="loadingStore">
            商品データ登録中...
        </div>

        <div v-else-if="error">
            エラー：{{ error }}
        </div>

        <div v-else>
            <form @submit="toyStore(name, information, price, is_selling, is_reserve, category_id, series_id, image_url,stock, release_date)" enctype="multipart/form-data">
                <h1>新規商品登録</h1>
                <p>
                    商品名：
                    <input type="text" v-model="name" name="name" max="255" placeholder="商品名を入力してください">
                </p>
                <p>
                    商品説明：
                    <input type="text" v-model="information" name="information" placeholder="商品説明を入力してください">
                </p>
                <p>
                    値段：
                    <input type="number" v-model="price" name="price" min="0" placeholder="値段を入力してください">
                </p>
                <p>
                    カテゴリ名：
                    <select v-model="category_id">
                        <option selected>
                            カテゴリ名を選択してください
                        </option>
                        <option v-for="category in categories" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </p>
                <p>
                    シリーズ名：
                    <select v-model="series_id">
                        <option selected>
                            シリーズ名を選択してください
                        </option>
                        <option v-for="a_series in series" :value="a_series.id">
                            {{ a_series.name }}
                        </option>
                    </select>
                </p>
                <p>
                    販売状況：
                    <input type="radio" v-model="is_selling" id="selling_now" name="is_selling" value="true">
                    <label for="can_selling">販売中</label>

                    <input type="radio" v-model="is_selling" id="not_selling" name="is_selling" value="false">
                    <label for="cant_selling">販売中止</label>
                </p>
                <p>
                    予約状況：
                    <input type="radio" v-model="is_reserve" id="can_reserve" name="is_reserve" value="true">
                    <label for="can_reserve">予約可能</label>

                    <input type="radio" v-model="is_reserve" id="cant_reserve" name="is_reserve" value="false">
                    <label for="cant_reserve">予約不可</label>
                </p>
                <p>
                    <!-- 入力された画像をフォルダに保存、パスをDBに保存 -->
                    <!-- https://qiita.com/MK32A/items/8a9bd640fc9c8e69efdd -->
                    画像データ：
                    <input type="file" name="image" ref="fileInput">
                </p>
                <p>
                    在庫：
                    <input type="number" v-model="stock" name="price" min="0" placeholder="在庫を入力してください">
                </p>
                <p>
                    発売日：
                    <input type="text" v-model="release_date" name="release_date" placeholder="yyyy-mm-dd形式で入力してください">
                </p>
                <br><br>
                <input type="submit" value="商品登録">
            </form>
        </div>
    </div>
</template>