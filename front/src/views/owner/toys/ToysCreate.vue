<script setup>
    import { ref, onMounted} from 'vue'
    import { apiClient } from '@/utils/api';

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

    const error = (null)
    const loading = (true)

    async function getCategories(){
        try{
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
            console.log('カテゴリ名の取得に失敗:', err)
            error.value = 'カテゴリ名一覧の取得に失敗しました'
        }
    }

    onMounted(() =>
        loading.value = true,
        error.value = null,
        getCategories(),
        getSeries()
    )

</script>

<template>
    <div>
        <div v-if="loading">
            カテゴリデータ、シリーズデータを取得中...
        </div>
        <div v-else-if="error">
            エラー：{{ error }}
        </div>
        <div v-else>
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
                カテゴリ名：
                <select v-model="category_id">
                    <option v-for="category in categories" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </p>
            <p>
                シリーズ名：
                <select v-model="series_id">
                    <option v-for="a_series in series" :value="series.id">
                        {{ a_series.name }}
                    </option>
                </select>
            </p>
            <p>
                画像：
                <img :src="'/images' + props.profile.filename">
                <input type="hidden" v-model="image_url">
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
            <input type="button" value="商品を登録" @click="registerToy(
                name, information, price, is_selling, is_reserve, 
                category_id, series_id, image_url, stock, release_date)">
        </div>
    </div>
</template>