

<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import { apiClient } from '@/utils/api.js'

    const route = useRoute()
    const router = useRouter()

    const toy = ref(null)
    const loading = ref(true)
    const error = ref(null)

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
    const handleSubmit = async () => {
        try {
            loading.value = true
            error.value = {}
            const toyId = route.params.id
            const toyData = {
                toy_id: toyId,
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
    ///////////////////////////////////////////////////////

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
                <button @click="handleSubmit">お気に入りに追加</button>
            </div>
        </div>
    </div>
</template>