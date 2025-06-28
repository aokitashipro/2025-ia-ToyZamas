<template>
  <div>
    <h1>売上分析</h1>
    <!-- グラフ -->
    <canvas ref="salesChart"></canvas>
      <!-- 切り替えラジオ -->
    <div>
      <label>
        <input type="radio" value="monthly" v-model="type" @change="fetchSalesData" />
        月別
      </label>
      <label>
        <input type="radio" value="daily" v-model="type" @change="fetchSalesData" />
        日別
      </label>
    </div>

      <!-- 読み込み -->
    <div v-if="loading">
      <div class="loader"></div>
    </div>
        <!-- 表 -->
    <div v-else>
      <table>
        <thead>
          <tr>
            <th>日付</th>
            <th>売上金額</th>
            <th>販売数</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in salesData" :key="index">
            <td>{{ item.date }}</td>
            <td>{{ item.total_sales_amount }} 円</td>
            <td>{{ item.total_item_sold }} 点</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import Chart from 'chart.js/auto'
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const type = ref('monthly')
const salesData = ref([])
const loading = ref(false)
const salesChart = ref(null)
let chartInstance = null //  グローバル変数でインスタンス保持

const fetchSalesData = async () => {
  loading.value = true
  try {
  //   const res = await apiClient.get('/owner/sales-analysis', {  いったんコメントアウト
      // params: { type: type.value }
    // })
    // salesData.value = res.data.data　　　いったんコメントアウト　本番はAPIからのデータを使用
    //-------
    // グラフの描画
    // ダミーデータ（実際はAPIのtypeによって出し分ける）
    salesData.value = type.value === 'monthly'
      ? [
          { date: '2025-01', total_sales_amount: 15000, total_item_sold: 25 },
          { date: '2025-02', total_sales_amount: 18000, total_item_sold: 30 },
        ]
      : [
          { date: '2025-06-24', total_sales_amount: 5000, total_item_sold: 10 },
          { date: '2025-06-25', total_sales_amount: 12000, total_item_sold: 20 },
        ]
      // グラフデータ生成
    const labels = salesData.value.map(item => item.date)
    const salesAmount = salesData.value.map(item => item.total_sales_amount)
    const itemCount = salesData.value.map(item => item.total_item_sold)

       // 既存のグラフがあれば破棄
    if (chartInstance) {
      chartInstance.destroy()
    }


    chartInstance = new Chart(salesChart.value, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: '売上金額',
            data: salesAmount,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
          },
          {
            label: '販売数',
            data: itemCount,
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          }
        }
      }
    })
  } catch (e) {
    alert('売上データの取得に失敗しました')
  } finally {
    loading.value = false
  }
}

onMounted(fetchSalesData)
</script>

<style scoped>
canvas {
  max-width: 600px;
  margin: 0 auto;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  border: 1px solid #000000;
  padding: 8px;
  text-align: center;
}



</style>

<!-- <template>
  <div>
    <h2>売上分析</h2>

    <div v-if="loading">読み込み中...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <p>合計売上：¥{{ data.total_sales }}</p>
      <p>注文数：{{ data.total_orders }}件</p>
      <p>平均注文額：¥{{ data.average_order_amount }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { apiClient } from '@/utils/api.js';

const data = ref({});
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const res = await apiClient.get('/owner/sales-analysis');
    data.value = res.data;
  } catch (e) {
    error.value = 'データの取得に失敗しました';
  } finally {
    loading.value = false;
  }
});
</script> -->