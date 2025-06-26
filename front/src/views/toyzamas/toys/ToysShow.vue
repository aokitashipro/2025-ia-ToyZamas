<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import { apiClient } from '@/utils/api.js'

    const route = useRoute()
    const router = useRouter()

    const toy = ref(null)
    const loading = ref(true)
    const error = ref(null)
    const num = ref(0)

    const loadToy = async () => {
        try {
            loading.value = true
            error.value = null

            const toyId = route.params.id
            const response = await apiClient.get(`/toyzamas/toys/${toyId}`)
            toy.value = response.data || response

            console.log('商品詳細:', toy.value)
        }catch (err){
            console.error('商品詳細の取得に失敗:', err)

            if (err.status === 404) {
                error.value = 'データが見つかりません'
            } else {
                error.value = '商品詳細の取得に失敗しました'
            }
        }finally {
            loading.value = false
        }
    }

    //お気に入り登録
    async function favoritSubmit () {
        try {
            loading.value = true
            error.value = {}
            const toyData = {
                toy_id: toy.value.id,
            }
            console.log('送信データ:', toyData)

            const response = await apiClient.post('/toyzamas/favorites', toyData)
            console.log('作成成功:', response)
            alert('お気に入り登録しました！')
            // 商品情報を再取得
            await loadToy();
        } catch (error) {
            console.error('お気に入り登録に失敗:', error)

            if (error.errors) {
                errors.value = error.errors
            } else {
                alert('お気に入り登録に失敗しました。')
            }
        } finally {
            loading.value = false
            // 商品情報を再取得
            await loadToy();
        }
    }  

    //カート登録
    async function cartSubmit() {

        if(num.value === 0) {
            alert('購入数を指定してください。')
            return;
        }
        try {
            loading.value = true
            error.value = {}
            const toyData = {
                toy_id: toy.value.id,
                quantity: num.value,
            }
            console.log('送信データ:', toyData)

            const response = await apiClient.post('/toyzamas/carts', toyData)
            console.log('作成成功:', response)
            alert('カートに追加しました！')
            // 商品情報を再取得
            await loadToy();
        } catch (error) {
            console.error('カート登録に失敗:', error)

            if (error.errors) {
                errors.value = error.errors
            } else {
                alert('カート追加に失敗しました。')
            }
        } finally {
            loading.value = false
            // 商品情報を再取得
            await loadToy();
        }
    }

    //予約登録
    async function reserveSubmit() {
        if(num.value === 0) {
            alert('予約数を指定してください。')
            return;
        }
        try {
            loading.value = true
            error.value = {}
            console.log(route.params.id)
            console.log(toy.value.release_date)
            console.log(num.value)
            console.log(toy)
            const toyData = {
                toy_id: toy.value.id,
                reserve_date: toy.value.release_date,
                reserve_num: num.value,
            }
            console.log('送信データ:', toyData)

            const response = await apiClient.post('/toyzamas/reserves', toyData)
            console.log('作成成功:', response)
            alert('商品を予約しました！')
            // 商品情報を再取得
            await loadToy();
        } catch (error) {
            console.error('予約に失敗:', error)

            if (error.errors) {
                errors.value = error.errors
            } else {
                alert('予約に失敗しました。')
            }
        } finally {
            loading.value = false
            // 商品情報を再取得
            await loadToy();
        }
    }

    const goBack = () => {
        router.push('/toyzamas/toys')
    }

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleString('ja-JP')
    }

    onMounted(() => {
        loadToy()
    })
</script>

<template>
    <div>
        <div>
            <img v-if="image_url" :src="image_url" alt="toy image">
            <p v-else>画像が存在しません。</p>
        </div>
        <h1 v-if="toy">{{ toy.name }}</h1>
        <div v-if="loading">
            読み込み中...
        </div>

        <div v-else-if="error">
            エラー: {{ error }}
        </div>

        <div v-else-if="toy">
            <div class="toy-info">
                <!-- <div class="info-row">
                    <label>商品名:</label>
                    <span>{{ toy.name }}</span>
                </div> -->

                <div class="info-row">
                    <label>商品説明:</label>
                    <span>{{ toy.information }}</span>
                </div>

                <div class="info-row">
                    <label>価格:</label>
                    <span>{{ toy.price?.toLocaleString() }}円</span>
                </div>

                <div class="info-row">
                    <label>カテゴリ:</label>
                    <span>{{ toy.category_name }}</span>
                </div>

                <div class="info-row">
                    <label>シリーズ:</label>
                    <span>{{ toy.series_name }}</span>
                </div>

                <div class="info-row">
                    <label>在庫:</label>
                    <span>{{ toy.stock?.toLocaleString() }}コ</span>
                </div>

                <div class="info-row">
                    <label>発売日:</label>
                    <span>{{ toy.release_date }}</span>
                </div>
            </div>

            <div class="action-buttons">
                <button @click="goBack" class="btn btn-secondary">一覧に戻る</button>
                <button @click="favoritSubmit">お気に入りに追加</button>
                <select v-model="num">
                    <option v-for="n in toy.stock" :key="n" :value="n">{{ n }}</option>
                </select>
                <button v-if="toy.is_reserve" @click="reserveSubmit">予約注文</button>
                <button v-else @click="cartSubmit">カートに追加</button>
            </div>
        </div>
    </div>
</template>