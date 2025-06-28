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

    const laravel_base_url = 'http://127.0.0.1:8000/storage/'
    const img = ref(null)

    const loadToy = async () => {
        try {
            loading.value = true
            error.value = null

            const toyId = route.params.id
            const response = await apiClient.get(`/toyzamas/toys/${toyId}`)
            toy.value = response.data || response

            if (toy.value.image_url) {
                img.value = laravel_base_url + toy.value.image_url
            }

            console.log('商品詳細:', toy.value)
            console.log( toy.value.image_url )
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
                reserve_price: toy.value.price,
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
    <div class="toy-show-container">
        <div class="toy-main-row">
            <div class="toy-image-col">
                <img v-if="img" :src="img" alt="toy image" class="toy-image">
                <p v-else class="no-image-text">画像が存在しません。</p>
            </div>
            <div class="toy-info-col">
                <h1 v-if="toy" class="toy-title">{{ toy.name }}</h1>
                <div v-if="loading">
                    <div class="loader"></div>
                </div>
                <div v-else-if="error">
                    エラー: {{ error }}
                </div>
                <div v-else-if="toy">
                    <div class="toy-info">
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
                        <button @click="favoritSubmit" class="btn">お気に入りに追加</button>
                        <select v-model="num" class="num-select">
                            <option v-for="n in toy.stock" :key="n" :value="n">{{ n }}</option>
                        </select>
                        <button v-if="toy.is_reserve" @click="reserveSubmit" class="btn">予約注文</button>
                        <button v-else @click="cartSubmit" class="btn">カートに追加</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.toy-show-container {
  max-width: 700px;
  margin: 2em auto;
  background: #f9f9fc;
  border: 1px solid #e0e0e0;
  padding: 2em 1.5em;
}
.toy-main-row {
  display: flex;
  gap: 2em;
  align-items: flex-start;
}
.toy-image-col {
  flex: 0 0 320px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.toy-info-col {
  flex: 1;
  min-width: 0;
}
.toy-title {
  color: #338fe5;
  margin: 0 0 1em 0;
  font-size: 1.5em;
  text-align: left;
}
.toy-image {
  width: 300px;
  height: auto;
  display: block;
  margin-bottom: 1em;
  border: 1px solid #ccc;
  background: #fff;
}
.no-image-text {
  width: 300px;
  height: 200px;
  background: #f0f0f0;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1em;
  font-size: 1em;
  border: 1px dashed #ccc;
}
.toy-info {
  margin-bottom: 2em;
}
.info-row {
  display: flex;
  align-items: center;
  margin-bottom: 0.7em;
}
.info-row label {
  width: 90px;
  font-weight: bold;
  color: #338fe5;
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.info-row span {
  flex: 1;
}
.action-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.7em;
  margin-top: 1.5em;
}
.btn, .btn-secondary {
  padding: 0.5em 1.5em;
  background: #338fe5;
  color: #fff;
  border: none;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-secondary {
  background: #eee;
  color: #338fe5;
}
.btn:hover:not(.btn-secondary) {
  background: #226bb3;
}
.btn-secondary:hover {
  background: #dbeafe;
}
.num-select {
  padding: 0.4em;
  border: 1px solid #ccc;
  border-radius: 4px;
}
@media (max-width: 700px) {
  .toy-main-row {
    flex-direction: column;
    gap: 1em;
  }
  .toy-image-col {
    align-items: center;
    margin-bottom: 1em;
  }
  .toy-info-col {
    width: 100%;
  }
}
</style>