import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Login from '../views/user/LoginView.vue'
import Logout from '../views/user/LogoutView.vue'
import Register from '../views/user/RegisterView.vue'
//管理者側のルートimport
import OwnerTop from '../views/owner/TopView.vue'

// 管理者:商品画面
// import OwnerToysIndex from '../views/owner/toys/ToysIndex.vue'
// import OwnerToysCreate from '../views/owner/toys/ToysCreate.vue'
// import OwnerToysShow from '../views/owner/toys/ToysShow.vue'
// import OwnerToysEdit from '../views/owner/toys/ToysEdit.vue'

// 管理者:在庫画面
// import OwnerStocksIndex from '../views/owner/stock/StocksIndex.vue'
// import OwnerStockIncrement from '../views/owner/stock/StockIncrement.vue'
// import OwnerStocksUpdate from '../views/owner/stock/StocksUpdate.vue'

// 管理者:在庫履歴画面
// import OwnerHistoryIndex from '../views/owner/stock/HistoryIndex.vue'

// 管理者:予約画面
// import OwnerReserveCreate from '../views/owner/reserve/ReserveCreate.vue'

// 管理者:カテゴリー画面
// import OwnerCategoryIndex from '../views/owner/categories/CategoryIndex.vue'
// import OwnerCategoryCreate from '../views/owner/categories/CategoryCreate.vue'
// import OwnerCategoryEdit from '../views/owner/categories/CategoryEdit.vue'

// 管理者:シリーズ画面
// import OwnerSeriesIndex from '../views/owner/series/SeriesIndex.vue'
// import OwnerSeriesCreate from '../views/owner/series/SeriesCreate.vue'
// import OwnerSeriesEdit from '../views/owner/series/SeriesEdit.vue'

// 管理者:ユーザ管理画面
// import OwnerUsersIndex from '../views/owner/users/UsersIndex.vue'
// import OwnerUserEdit from '../views/owner/users/UserEdit.vue'

// 管理者:売上分析画面
// import OwnerSalesAnalysis from '../views/owner/SalesAnalysis.vue'

//一般ユーザ側のルートimport
import ToyzamasTop from '../views/toyzamas/TopView.vue'

// ユーザ:商品画面
import UserToysIndex from '../views/toyzamas/toys/ToysIndex.vue'
import UserToysShow from '../views/toyzamas/toys/ToysShow.vue'

// ユーザ:カート画面
// import UserCartIndex from '../views/toyzamas/cart/CartIndex.vue'

// ユーザ:購入完了画面
// import UserBuyCompletion from '../views/toyzamas/buy/BuyCompletion.vue'

// ユーザ:購入履歴画面
// import UserHistoryIndex from '../views/toyzamas/buy/history/HistoryIndex.vue'

// ユーザ:予約画面
// import UserReservesIndex from '../views/toyzamas/reserves/ReservesIndex.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView, },
    { path: '/about', name: 'about', component: () => import('../views/AboutView.vue'),},

    //userディレクトリのルート設定
    { path: '/login', name: 'login', component: Login }, //ログイン用ルートの追加

    { path: '/logout', name: 'logout', component: Logout }, //ログアウト用ルートの追加
    
    { path: '/register', name: 'register', component: Register }, //登録用ルートの追加

    //Ownerディレクトリのルート設定
    { path: '/owner', name: 'OwnerTop', component: OwnerTop },

    // // 管理者:商品画面ルート
    // { path: '/owner/toys', name: 'OwnerToysIndex', component: OwnerToysIndex },
    // { path: '/owner/toys/create', name: 'OwnerToysCreate', component: OwnerToysCreate },
    // { path: '/owner/toys/:toy', name: 'OwnerToysShow', component: OwnerToysShow, props: true },
    // { path: '/owner/toys/:toy/edit', name: 'OwnerToysEdit', component: OwnerToysEdit, props: true },

    // // 管理者:在庫画面ルート
    // { path: '/owner/stocks', name: 'OwnerStocksIndex', component: OwnerStocksIndex },
    // { path: '/owner/stocks/:stock', name: 'OwnerStockIncrement', component: OwnerStockIncrement, props: true },
    // { path: '/owner/stocks/:stock/update', name: 'OwnerStocksUpdate', component: OwnerStocksUpdate, props: true },
    // { path: '/owner/stocks/history', name: 'OwnerHistoryIndex', component: OwnerHistoryIndex },

    // // 管理者:予約画面ルート
    // { path: '/owner/reserve', name: 'OwnerReserveCreate', component: OwnerReserveCreate },

    // // 管理者:カテゴリー画面ルート
    // { path: '/owner/categories', name: 'OwnerCategoryIndex', component: OwnerCategoryIndex },
    // { path: '/owner/categories/create', name: 'OwnerCategoryCreate', component: OwnerCategoryCreate },
    // { path: '/owner/categories/:category/edit', name: 'OwnerCategoryEdit', component: OwnerCategoryEdit, props: true },

    // // 管理者:シリーズ画面ルート
    // { path: '/owner/series', name: 'OwnerSeriesIndex', component: OwnerSeriesIndex },
    // { path: '/owner/series/create', name: 'OwnerSeriesCreate', component: OwnerSeriesCreate },
    // { path: '/owner/series/:series/edit', name: 'OwnerSeriesEdit', component: OwnerSeriesEdit, props: true },

    // // 管理者:ユーザ管理画面ルート
    // { path: '/owner/users', name: 'OwnerUsersIndex', component: OwnerUsersIndex },
    // { path: '/owner/users/:user', name: 'OwnerUserEdit', component: OwnerUserEdit, props: true },

    // // 管理者:売上分析画面ルート
    // { path: '/owner/sales-analysis', name: 'OwnerSalesAnalysis', component: OwnerSalesAnalysis },

    //ユーザートップ
    { path: '/toyzamas/:id/top', name:'ToyzamasTop', component: ToyzamasTop },
    // ユーザ:商品画面
    { path: '/toyzamas/toys', name: 'UserToysIndex', component: UserToysIndex, props: true },
    { path: '/toyzamas/toys/:id', name: 'UserToysShow', component: UserToysShow, props: true },

    // // ユーザ:カート画面
    // { path: '/toyzamas/:user_id/cart', name: 'UserCartIndex', component: UserCartIndex, props: true },
    
    // // ユーザ:購入完了画面
    // { path: '/toyzamas/:user_id/buy/completion', name: 'UserBuyCompletion', component: UserBuyCompletion, props: true },

    // // ユーザ:購入履歴画面
    // { path: '/toyzamas/:user_id/buy/history', name: 'UserHistoryIndex', component: UserHistoryIndex, props: true },

    // // ユーザ:予約画面
    // { path: '/toyzamas/:user_id/reserves', name: 'UserReservesIndex', component: UserReservesIndex, props: true },

    // // ユーザ:お気に入り画面
    // { path: '/toyzamas/:user_id/favorites', name: 'FavoritesIndex', component: FavoritesIndex, props: true },
  ],
})

export default router
