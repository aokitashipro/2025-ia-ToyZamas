import { createRouter, createWebHistory } from 'vue-router'
// import HomeView from '../views/HomeView.vue'

import Login from '../views/user/LoginView.vue'
import Logout from '../views/user/LogoutView.vue'
import Register from '../views/user/RegisterView.vue'
//管理者側のルートimport
import OwnerTop from '../views/owner/TopView.vue'

// 管理者:商品画面
import OwnerToysIndex from '../views/owner/toys/ToysIndex.vue'
import OwnerToysCreate from '../views/owner/toys/ToysCreate.vue'
import OwnerToysShow from '../views/owner/toys/ToysShow.vue'
import OwnerToysEdit from '../views/owner/toys/ToysEdit.vue'

// 管理者:在庫画面
import OwnerStocksIndex from '../views/owner/stock/StocksIndex.vue'
import OwnerStockIncrement from '../views/owner/stock/StockIncrement.vue'
import OwnerStocksUpdate from '../views/owner/stock/StocksUpdate.vue'

// 管理者:在庫履歴画面
import OwnerHistoryIndex from '../views/owner/stock/HistoryIndex.vue'

// 管理者:予約画面
import OwnerReserveCreate from '../views/owner/reserve/ReserveCreate.vue'

// 管理者:カテゴリー画面
import OwnerCategoryIndex from '../views/owner/categories/CategoryIndex.vue'
import OwnerCategoryCreate from '../views/owner/categories/CategoryCreate.vue'
import OwnerCategoryEdit from '../views/owner/categories/CategoryEdit.vue'

// 管理者:シリーズ画面
import OwnerSeriesIndex from '../views/owner/series/SeriesIndex.vue'
import OwnerSeriesCreate from '../views/owner/series/SeriesCreate.vue'
import OwnerSeriesEdit from '../views/owner/series/SeriesEdit.vue'

// 管理者:ユーザ管理画面
import OwnerUsersIndex from '../views/owner/users/UsersIndex.vue'
import OwnerUserEdit from '../views/owner/users/UserEdit.vue'

// 管理者:売上分析画面
import OwnerSalesAnalysis from '../views/owner/SalesAnalysis.vue'

//一般ユーザ側のルートimport
import ToyzamasTop from '../views/toyzamas/TopView.vue'

// ユーザ:商品画面
import UserToysIndex from '../views/toyzamas/toys/ToysIndex.vue'
import UserToysShow from '../views/toyzamas/toys/ToysShow.vue'

// ユーザ:カート画面
import UserCartIndex from '../views/toyzamas/cart/CartIndex.vue'

// ユーザ:購入完了画面
import UserBuyCompletion from '../views/toyzamas/buy/BuyCompletion.vue'

// ユーザ:購入履歴画面
import UserHistoryIndex from '../views/toyzamas/buy/history/HistoryIndex.vue'

// ユーザ:予約画面
import UserReservesIndex from '../views/toyzamas/reserves/ReservesIndex.vue'

// ユーザ:お気に入り画面
import UserFavoritesIndex from '../views/toyzamas/favorites/FavoritesIndex.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // { path: '/', name: 'home', component: HomeView, },
    {path: '/',redirect: '/login'},
    // { path: '/about', name: 'about', component: () => import('../views/AboutView.vue'),},

    //userディレクトリのルート設定
    { path: '/login', name: 'login', component: Login,  meta: { layout: 'user' }, }, //デフォルトはuserにしてます。//ログイン用ルートの追加

    { path: '/logout', name: 'logout', component: Logout,  meta: { layout: 'user' }, }, //ログアウト用ルートの追加
    
    { path: '/register', name: 'register', component: Register,  meta: { layout: 'user' }, }, //登録用ルートの追加

    //Ownerディレクトリのルート設定
    { path: '/owner', name: 'OwnerTop', component: OwnerTop,  meta: { layout: 'owner' },},

    // 管理者:商品画面ルート
    { path: '/owner/toys', name: 'OwnerToysIndex', component: OwnerToysIndex,  meta: { layout: 'owner' }, },
    { path: '/owner/toys/create', name: 'OwnerToysCreate', component: OwnerToysCreate,  meta: { layout: 'owner' }, },
    { path: '/owner/toys/:toy', name: 'OwnerToysShow', component: OwnerToysShow, props: true,  meta: { layout: 'owner' }, },
    { path: '/owner/toys/:toy/edit', name: 'OwnerToysEdit', component: OwnerToysEdit, props: true,  meta: { layout: 'owner' }, },

    // 管理者:在庫画面ルート
    { path: '/owner/stocks', name: 'OwnerStocksIndex', component: OwnerStocksIndex,  meta: { layout: 'owner' }, },
    { path: '/owner/stocks/:stock', name: 'OwnerStockIncrement', component: OwnerStockIncrement, props: true,  meta: { layout: 'owner' }, },
    { path: '/owner/stocks/:stock/update', name: 'OwnerStocksUpdate', component: OwnerStocksUpdate, props: true,  meta: { layout: 'owner' }, },
    { path: '/owner/stocks/history', name: 'OwnerHistoryIndex', component: OwnerHistoryIndex,  meta: { layout: 'owner' }, },

    // 管理者:予約画面ルート
    { path: '/owner/reserve', name: 'OwnerReserveCreate', component: OwnerReserveCreate,  meta: { layout: 'owner' }, },

    // 管理者:カテゴリー画面ルート
    { path: '/owner/categories', name: 'OwnerCategoryIndex', component: OwnerCategoryIndex,  meta: { layout: 'owner' }, },
    { path: '/owner/categories/create', name: 'OwnerCategoryCreate', component: OwnerCategoryCreate,  meta: { layout: 'owner' }, },
    { path: '/owner/categories/:category/edit', name: 'OwnerCategoryEdit', component: OwnerCategoryEdit, props: true,  meta: { layout: 'owner' }, },

    // 管理者:シリーズ画面ルート
    { path: '/owner/series', name: 'OwnerSeriesIndex', component: OwnerSeriesIndex,  meta: { layout: 'owner' }, },
    { path: '/owner/series/create', name: 'OwnerSeriesCreate', component: OwnerSeriesCreate,  meta: { layout: 'owner' }, },
    { path: '/owner/series/:series/edit', name: 'OwnerSeriesEdit', component: OwnerSeriesEdit, props: true,  meta: { layout: 'owner' }, },

    // 管理者:ユーザ管理画面ルート
    { path: '/owner/users', name: 'OwnerUsersIndex', component: OwnerUsersIndex,  meta: { layout: 'owner' }, },
    { path: '/owner/users/:user', name: 'OwnerUserEdit', component: OwnerUserEdit, props: true,  meta: { layout: 'owner' }, },

    // 管理者:売上分析画面ルート
    { path: '/owner/sales-analysis', name: 'OwnerSalesAnalysis', component: OwnerSalesAnalysis,  meta: { layout: 'owner' }, },

    //Toyzamasディレクトリのルート設定
    { path: '/toyzamas/:id/top', name:'ToyzamasTop', component: ToyzamasTop,  meta: { layout: 'user' }, },
    // ユーザ:商品画面
    { path: '/toyzamas/toys', name: 'UserToysIndex', component: UserToysIndex, props: true,  meta: { layout: 'user' },  },
    { path: '/toyzamas/toys/:toy', name: 'UserToysShow', component: UserToysShow, props: true,  meta: { layout: 'user' },  },

    // ユーザ:カート画面
    { path: '/toyzamas/cart', name: 'UserCartIndex', component: UserCartIndex, props: true,  meta: { layout: 'user' },  },
    
    // ユーザ:購入完了画面
    { path: '/toyzamas/buy/completion', name: 'UserBuyCompletion', component: UserBuyCompletion, props: true,  meta: { layout: 'user' },  },

    // ユーザ:購入履歴画面
    { path: '/toyzamas/buy/history', name: 'UserHistoryIndex', component: UserHistoryIndex, props: true,  meta: { layout: 'user' },  },

    // ユーザ:予約画面
    { path: '/toyzamas/reserves', name: 'UserReservesIndex', component: UserReservesIndex, props: true,  meta: { layout: 'user' },  },

    // ユーザ:お気に入り画面
    { path: '/toyzamas/favorites', name: 'UserFavoritesIndex', component: UserFavoritesIndex, props: true,  meta: { layout: 'user' },  },
  ],
})

export default router
