<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'

// Vue Router
const router = useRouter()

// リアクティブデータ
const isLoggedIn = ref(false)
const isLoading = ref(false)

const formData = reactive({
  email: '',
  password: ''
})

const message = reactive({
  text: '',
  isError: false
})

// メッセージ表示用関数
const showMessage = (text, isError = false) => {
  message.text = text
  message.isError = isError
}

// ログイン成功後の画面表示
const showLoggedInState = () => {
  isLoggedIn.value = true
  message.text = ''
}

// フォーム送信処理
const handleSubmit = async () => {
  isLoading.value = true

  const loginData = {
    email: formData.email,
    password: formData.password
  }

  try {
    const data = await apiClient.post('/login', loginData)
    
    // トークンを保存
    localStorage.setItem('token', data.token)
    if (data.user) {
      localStorage.setItem('user', JSON.stringify(data.user))
    }

    if(data.user.is_admin === 1){
      router.push('/owner/toys')
    }else if(data.user.is_admin === 0){
      window.location.href = `/toyzamas/toys`
    }

    showMessage('ログイン成功！', false)

    console.log(data.user.is_admin)

    // ログイン成功後の画面を表示
    setTimeout(() => {
      showLoggedInState()
    }, 500)


  } catch (error) {
    console.error('ログインエラー:', error)
    showMessage(error.message || 'ログインに失敗しました', true)
  } finally {
    isLoading.value = false
  }
}

// ログアウト処理
const handleLogout = async () => {
  try {
    // APIでログアウト
    await apiClient.post('/logout', {});
    // ローカルストレージをクリア
    localStorage.removeItem('token')
    localStorage.removeItem('user')

        // フォームを再表示
    isLoggedIn.value = false
    formData.email = ''
    formData.password = ''
    showMessage('ログアウトしました', false)
    
  } catch (error) {
    console.error('ログアウトAPIエラー:', error)
  }
}

// ページ読み込み時にトークンがあるかチェック
onMounted(() => {
  const token = localStorage.getItem('token')
  if (token) {
    // 既にログインしている場合はログイン済み画面を表示
    showLoggedInState()
  }
})

</script>
<template>
  <h1>ログイン</h1>
  <div class="login-card">
    
    <!-- ログインフォーム -->
    <div v-show="!isLoggedIn">
      <form @submit.prevent="handleSubmit">
        <div class="form-row">
          <label for="email">Email:</label>
          <input 
            id="email" 
            v-model="formData.email" 
            type="email" 
            placeholder="email" 
            required
          >
        </div>
        
        <div class="form-row">
          <label for="password">Password:</label>
          <input 
            id="password" 
            v-model="formData.password" 
            type="password" 
            placeholder="password" 
            required
          >
        </div>
        
        <button type="submit" :disabled="isLoading" class="login-btn">
          {{ isLoading ? 'ログイン中...' : 'Login' }}
        </button>
      </form>

      <div 
        v-if="message.text" 
        :style="{ color: message.isError ? 'red' : 'green', marginTop: '1em' }"
      >
        {{ message.text }}
      </div>
      
      <p class="register-link">
        <router-link to="/register">アカウントをお持ちでない方はこちら</router-link>
      </p>
    </div>

    <!-- ログイン済み画面 -->
    <div v-show="isLoggedIn">
      <h2>ログイン済み</h2>
      <p>ログインが完了しました。</p>
      <button @click="handleLogout" class="logout-btn">ログアウト</button>
    </div>
  </div>
</template>

<style scoped>
.login-card {
  max-width: 400px;
  margin: 2em auto;
  padding: 2em 1.5em;
  background: #f9f9fc;
  border-radius: 12px;
  border: 1px solid #e0e0e0;
}
h1 {
  text-align: center;
  color: #338fe5;
  margin-bottom: 1.5em;
}
.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 1em;
}
.form-row label {
  width: 90px;
  margin-right: 1em;
  text-align: right;
  flex-shrink: 0;
}
.form-row input {
  flex: 1;
  padding: 0.4em;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.login-btn, .logout-btn {
  display: block;
  width: 100%;
  padding: 0.6em 0;
  background: #338fe5;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  margin-top: 1em;
  cursor: pointer;
  transition: background 0.2s;
}
.login-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}
.login-btn:hover:not(:disabled),
.logout-btn:hover {
  background: #226bb3;
}
.register-link {
  text-align: center;
  margin-top: 1.5em;
}
</style>

<!-- ログアウトは別でつくる -->