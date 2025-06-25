<template>
  a
 <select v-model="selectedInfo"><!-- ★ v-modelに初期値オブジェクトを設定 -->
  <option v-for="info in infoList" :value="code"><!-- ★ valueにinfoというオブジェクトを設定 -->
   {{ info.label }}
  </option>
 </select>
</template>

<script>
export default {
 data() {
   return {
     //初期値
     selectedInfo: { code: '2', label: '２番' },
     //選択肢
     infoList: [
       {code: '1', label: '１番'},
       {code: '2', label: '２番'},
       {code: '3', label: '３番'},
       {code: '4', label: '４番'},      
     ]
   }
 }
}
</script>