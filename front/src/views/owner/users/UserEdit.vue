<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import { apiClient } from '@/utils/api.js'

    const route = useRoute()
    const router = useRouter()

    const user = ref(null)
    const loading = ref(true)
    const error = ref(null)
    const num = ref({
      name: '',
      email: '',
      is_admin: false
    });

    const loadUser = async () => {
        try {
            loading.value = true
            error.value = null

            const userId = route.params.id
            const response = await apiClient.get(`/owner/users/${userId}`)
            user.value = response.data || response

            console.log('ユーザー詳細:', user.value)
            // num.value にデフォルト値を設定
            num.value = {
              name: user.value.name || '',
              email: user.value.email || '',
              is_admin: user.value.is_admin || false
            }
        }catch (err){
            console.error('ユーザー詳細の取得に失敗:', err)

            if (err.status === 404) {
                error.value = 'データが見つかりません'
            } else {
                error.value = 'ユーザー詳細の取得に失敗しました'
            }
        }finally {
            loading.value = false
        }
    }
    //ユーザー情報更新
    async function userSubmit() {
        console.log(route.params.id)
        console.log(num.value.name)
        console.log(num.value.email)
        console.log(num.value.is_admin)
        console.log(user)
        try {
            loading.value = true
            error.value = {}
           
            const userData = {
                name: num.value.name,  // num.name.value ではなく num.value.name が正しい
                email: num.value.email, // 同様に正しいプロパティを使用
                is_admin: num.value.is_admin // num.value.is_admin を使う
            }
            console.log('送信データ:', userData)

            const response = await apiClient.put(`/owner/users/${route.params.id}`, userData)
            console.log('作成成功:', response)
            alert('ユーザー情報を更新しました！')
            // 商品情報を再取得
            await loadUser();
        } catch (error) {
            console.error('ユーザー情報の更新に失敗:', error)

            if (error.errors) {
                errors.value = error.errors
            } else {
                alert('ユーザー情報の更新に失敗しました。')
            }
        } finally {
            loading.value = false
            // 商品情報を再取得
            await loadUser();
        }
    }

    const goBack = () => {
        router.push('/owner/users')
    }

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleString('ja-JP')
    }

    onMounted(() => {
        loadUser()
    })
</script>

<template>
  <div class="user-edit-card">
    <h1 v-if="user">{{ user.name }}</h1>
    <div v-if="loading">
      <div class="loader"></div>
    </div>

    <div v-else-if="error">
      エラー: {{ error }}
    </div>

    <div v-else-if="user">
      <label>
        名前:
        <input v-if="user" type="text" v-model="num.name" placeholder="名前を入力してください">
      </label>
      <label>
        メール:
        <input v-if="user" type="email" v-model="num.email" placeholder="メールを入力してください">
      </label>
      <label>
        管理者:
        <input v-if="user" type="checkbox" v-model="num.is_admin">
      </label>
      <button @click="userSubmit">情報更新</button>
      <button @click="goBack" class="btn btn-secondary">一覧に戻る</button>
    </div>
  </div>
</template>

<style scoped>
.user-edit-card {
  max-width: 420px;
  margin: 2em auto;
  padding: 2em 1.5em;
  background: #f9f9fc;
  border-radius: 12px;
  border: 1px solid #e0e0e0;
}
.user-edit-card h1 {
  margin-bottom: 1.2em;
  color: #338fe5;
  font-size: 1.3em;
  text-align: center;
}
.user-edit-card label {
  display: flex;
  align-items: center;
  margin-bottom: 1em;
  font-weight: bold;
}
.user-edit-card input[type="text"],
.user-edit-card input[type="email"] {
  flex: 1;
  margin-left: 1em;
  padding: 0.4em;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.user-edit-card input[type="checkbox"] {
  margin-left: 1em;
  transform: scale(1.2);
}
.user-edit-card button {
  margin-right: 1em;
  padding: 0.5em 1.5em;
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}
.user-edit-card button.btn-secondary {
  background: #eee;
  color: #338fe5;
}
.user-edit-card button:hover:not(.btn-secondary) {
  background: #226bb3;
}
.user-edit-card button.btn-secondary:hover {
  background: #dbeafe;
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