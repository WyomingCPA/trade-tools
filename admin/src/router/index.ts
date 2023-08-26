import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'
import Page404Layout from '../layouts/Page404Layout.vue'

import RouteViewComponent from '../layouts/RouterBypass.vue'
import UIRoute from '../pages/admin/ui/route'

import { useUserStore } from '../stores/user'

import authMiddleware from './middleware/auth-middleware'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/:catchAll(.*)',
    redirect: { name: 'dashboard' },
  },
  {
    name: 'admin',
    path: '/admin',
    component: AppLayout,
    children: [
      {
        name: 'dashboard',
        meta: {
          authRequired: true
        },
        path: 'dashboard',
        component: () => import('../pages/admin/dashboard/Dashboard.vue'),
      },
      {
        name: 'algo-trading',
        path: 'algo-trading',
        component: RouteViewComponent,
        children: [
          {
            name: 'orders',
            path: 'orders',
            component: () => import('../pages/admin/algo-trading/Orders.vue'),
          },
          {
            name: 'bots',
            path: 'bots',
            component: () => import('../pages/admin/algo-trading/Bots.vue'),
          },
          {
            name: 'signals',
            path: 'signals',
            component: () => import('../pages/admin/algo-trading/Signals.vue'),
          },
          {
            name: 'strategy',
            path: 'strategy',
            component: () => import('../pages/admin/algo-trading/ListStrategy.vue'),
          },
        ],
      },
      {
        name: 'test-strategy',
        path: 'test-strategy',
        component: RouteViewComponent,
        children: [
          {
            name: 'test-strategy-list',
            path: 'test-strategy-list',
            component: () => import('../pages/admin/test-strategy/ListStrategy.vue'),
          },
          {
            name: 'test-strategy-create',
            path: 'test-strategy-create',
            component: () => import('../pages/admin/test-strategy/CreateStrategy.vue'),
          },
        ],
      },
      {
        name: 'stocks',
        path: 'stocks',
        component: RouteViewComponent,
        children: [
          {
            name: 'stock-all',
            path: 'stock-all',
            component: () => import('../pages/admin/stock/All.vue'),
          },
          {
            name: 'stock-favorite',
            path: 'stock-favorite',
            component: () => import('../pages/admin/stock/Favorite.vue'),
          },
          {
            name: 'stock-rus',
            path: 'stock-rus',
            component: () => import('../pages/admin/stock/RusStock.vue'),
          },
        ],
      },
      {
        name: 'etf',
        path: 'etf',
        component: RouteViewComponent,
        children: [
          {
            name: 'etf-all',
            path: 'etf-all',
            component: () => import('../pages/admin/etf/All.vue'),
          },
          {
            name: 'etf-favorite',
            path: 'etf-favorite',
            component: () => import('../pages/admin/etf/Favorite.vue'),
          },
        ],
      },
      {
        name: 'bonds',
        path: 'bonds',
        component: RouteViewComponent,
        children: [
          {
            name: 'bonds-all',
            path: 'bonds-all',
            component: () => import('../pages/admin/bond/All.vue'),
          },
          {
            name: 'bonds-favorite',
            path: 'bonds-favorite',
            component: () => import('../pages/admin/bond/Favorite.vue'),
          },
          {
            name: 'bonds-new',
            path: 'bonds-new',
            component: () => import('../pages/admin/bond/New.vue'),
          },
          {
            name: 'bonds-hide',
            path: 'bonds-hide',
            component: () => import('../pages/admin/bond/Hide.vue'),
          },
        ],
      },
      {
        name: 'futures',
        path: 'futures',
        component: RouteViewComponent,
        children: [
          {
            name: 'futures-all',
            path: 'futures-all',
            component: () => import('../pages/admin/futures/All.vue'),
          },
          {
            name: 'futures-favorite',
            path: 'futures-favorite',
            component: () => import('../pages/admin/futures/Favorite.vue'),
          },
        ],
      },
      {
        name: 'calculators',
        path: 'calculators',
        component: RouteViewComponent,
        children: [
          {
            name: 'calculators-stock-average',
            path: 'calculators-stock-average',
            component: () => import('../pages/admin/calculators/StockAverage.vue'),
          },
          {
            name: 'calculators-stock-aim-average',
            path: 'calculators-stock-aim-average',
            component: () => import('../pages/admin/calculators/StockAimAverage.vue'),
          },
          {
            name: 'calculators-count-lots',
            path: 'calculators-count-lots',
            component: () => import('../pages/admin/calculators/CountLots.vue'),
          },
        ],
      },
      {
        name: 'trade-ideas',
        path: 'trade-ideas',
        component: RouteViewComponent,
        children: [
          {
            name: 'trade-ideas-list',
            path: 'trade-ideas-list',
            component: () => import('../pages/admin/trade-ideas/Index.vue'),
          },
          {
            name: 'trade-ideas-add',
            path: 'trade-ideas-add',
            component: () => import('../pages/admin/trade-ideas/Add.vue'),
          },
        ],
      },
      {
        name: 'settings',
        path: 'settings',
        component: RouteViewComponent,
        children: [
          {
            name: 'settings-external-services',
            path: 'settings-external-services',
            component: () => import('../pages/admin/settings/ExternalServices.vue'),
          },
        ],
      },
      {
        name: 'my-finance',
        path: 'my-finance',
        component: RouteViewComponent,
        children: [
          {
            name: 'my-finance-all',
            path: 'my-finance-all',
            component: () => import('../pages/admin/my-finance/All.vue'),
          },
          {
            name: 'my-finance-add',
            path: 'my-finance-add',
            component: () => import('../pages/admin/my-finance/Add.vue'),
          },
        ],
      },
      {
        name: 'maps',
        path: 'maps',
        component: RouteViewComponent,
        children: [
          {
            name: 'maplibre-maps',
            path: 'maplibre-maps',
            component: () => import('../pages/admin/maps/maplibre-maps/MapLibreMapsPage.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Maps',
            },
          },
          {
            name: 'yandex-maps',
            path: 'yandex-maps',
            component: () => import('../pages/admin/maps/yandex-maps/YandexMapsPage.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Maps',
            },
          },
          {
            name: 'leaflet-maps',
            path: 'leaflet-maps',
            component: () => import('../pages/admin/maps/leaflet-maps/LeafletMapsPage.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Maps',
            },
          },
          {
            name: 'bubble-maps',
            path: 'bubble-maps',
            component: () => import('../pages/admin/maps/bubble-maps/BubbleMapsPage.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Maps',
            },
          },
          {
            name: 'line-maps',
            path: 'line-maps',
            component: () => import('../pages/admin/maps/line-maps/LineMapsPage.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Maps',
            },
          },
        ],
      },
      {
        name: 'tables',
        path: 'tables',
        component: RouteViewComponent,
        children: [
          {
            name: 'markup',
            path: 'markup',
            component: () => import('../pages/admin/tables/markup-tables/MarkupTables.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Tables',
            },
          },
          {
            name: 'data',
            path: 'data',
            component: () => import('../pages/admin/tables/data-tables/DataTables.vue'),
            meta: {
              wikiLink: 'https://github.com/epicmaxco/vuestic-admin/wiki/Tables',
            },
          },
        ],
      },
      {
        name: 'pages',
        path: 'pages',
        component: RouteViewComponent,
        children: [
          {
            name: '404-pages',
            path: '404-pages',
            component: () => import('../pages/admin/pages/404PagesPage.vue'),
          },
          {
            name: 'faq',
            path: 'faq',
            component: () => import('../pages/admin/pages/FaqPage.vue'),
          },
        ],
      },
      UIRoute,
    ],
  },
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        name: 'login',
        path: 'login',
        component: () => import('../pages/auth/login/Login.vue'),
      },
      {
        name: 'signup',
        path: 'signup',
        component: () => import('../pages/auth/signup/Signup.vue'),
      },
      {
        name: 'recover-password',
        path: 'recover-password',
        component: () => import('../pages/auth/recover-password/RecoverPassword.vue'),
      },
      {
        path: '',
        redirect: { name: 'login' },
      },
    ],
  },
  {
    path: '/404',
    component: Page404Layout,
    children: [
      {
        name: 'not-found-advanced',
        path: 'not-found-advanced',
        component: () => import('../pages/404-pages/VaPageNotFoundSearch.vue'),
      },
      {
        name: 'not-found-simple',
        path: 'not-found-simple',
        component: () => import('../pages/404-pages/VaPageNotFoundSimple.vue'),
      },
      {
        name: 'not-found-custom',
        path: 'not-found-custom',
        component: () => import('../pages/404-pages/VaPageNotFoundCustom.vue'),
      },
      {
        name: 'not-found-large-text',
        path: '/pages/not-found-large-text',
        component: () => import('../pages/404-pages/VaPageNotFoundLargeText.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  //  mode: process.env.VUE_APP_ROUTER_MODE_HISTORY === 'true' ? 'history' : 'hash',
  routes,
})
/*
router.beforeEach((to, from, next) => {
  const store = useUserStore();
  console.log(store.fetchUser.toString());
  if (!to.meta.authRequired) {
    next();
  } else if (store.authenticated) {
    next();
  } else {
    next({
      path: '/auth/login',
      params: { nextUrl: to.fullPath }
    });
  }
});
*/
router.beforeEach(authMiddleware)

export default router
