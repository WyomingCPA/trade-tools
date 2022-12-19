import Vue from 'vue'
import Router from 'vue-router'
import axios from 'axios';
import VueAxios from 'vue-axios';

import layout from '../layout'
import { store } from '../store'

Vue.use(Router)
Vue.use(VueAxios, axios);

let router = new Router({
  mode: 'history',
  base: '/',
  linkActiveClass: 'active',
  scrollBehavior: () => ({ y: 0 }),
  routes: configRoutes()
})

router.beforeEach((to, from, next) => {
  if (!to.meta.authRequired) {
    next();
  } else if (store.getters["authenticated"]) {
    next();
  } else {
    next({
      path: '/auth-pages/login',
      params: { nextUrl: to.fullPath }
    });
  }
});

//
export default router
function configRoutes() {
  return [
    {
      path: '/',
      component: layout,
      children: [
        {
          path: '',
          name: 'dashboard',
          component: () => import('@/pages/dashboard'),
          meta: {
            authRequired: true
          }
        }
      ]
    },
    {
      path: '/orders',
      component: layout,
      children: [
        {
          path: '',
          name: 'order-index',
          component: () => import('@/pages/orders/Index'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'today',
          name: 'order-today',
          component: () => import('@/pages/orders/list/Today'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'last-error',
          name: 'last-error',
          component: () => import('@/pages/orders/Errors'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'stop-orders/:id',
          name: 'stop-orders',
          component: () => import('@/pages/orders/StopOrders'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'spot-orders/:id',
          name: 'spot-orders',
          component: () => import('@/pages/orders/SpotOrders'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'spot-detil/:id',
          name: 'spot-detil',
          component: () => import('@/pages/spot/Detail'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'order-chart/:id',
          name: 'order-chart',
          component: () => import('@/pages/orders/chart/Test'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'order-chart-15min/:id',
          name: 'order-chart-15min',
          component: () => import('@/pages/orders/chart/15min'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/test-strategy',
      component: layout,
      children: [
        {
          path: '',
          name: 'test-strategy-index',
          component: () => import('@/pages/test_strategy/Index'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'create',
          name: 'test-strategy-create',
          component: () => import('@/pages/test_strategy/Create'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'strategy-chart/:id',
          name: 'test-strategy-chart',
          component: () => import('@/pages/test_strategy/chart/Test'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'open-orders/:id',
          name: 'open-orders',
          component: () => import('@/pages/test_strategy/orders/ListOrderDetail'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/stock',
      component: layout,
      children: [
        {
          path: 'rub',
          name: 'stock-rub',
          component: () => import('@/pages/stock/Rub'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'usd',
          name: 'stock-usd',
          component: () => import('@/pages/stock/Usd'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'all',
          name: 'stock-all',
          component: () => import('@/pages/stock/All'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'favorite',
          name: 'stock-favorite',
          component: () => import('@/pages/stock/Favorite'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'dividends',
          name: 'stock-dividends',
          component: () => import('@/pages/stock/Dividends'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'indicator-tutci/:id',
          name: 'stock-indicator-tutci',
          component: () => import('@/pages/stock/charts/IndicatorTUTCI'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/etf',
      component: layout,
      children: [
        {
          path: 'all',
          name: 'etf-all',
          component: () => import('@/pages/etf/All'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'favorite',
          name: 'etf-favorite',
          component: () => import('@/pages/etf/Favorite'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/futures',
      component: layout,
      children: [
        {
          path: 'index',
          name: 'futures-index',
          component: () => import('@/pages/futures/Index'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'favorite',
          name: 'futures-favorite',
          component: () => import('@/pages/futures/Favorite'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/bond',
      component: layout,
      children: [
        {
          path: 'all',
          name: 'bond-all',
          component: () => import('@/pages/bond/All'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'favorite',
          name: 'bond-favorite',
          component: () => import('@/pages/bond/Favorite'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'new',
          name: 'bond-new',
          component: () => import('@/pages/bond/New'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'hide',
          name: 'bond-hide',
          component: () => import('@/pages/bond/Hide'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/calculator',
      component: layout,
      children: [
        {
          path: 'stock-average-calculator',
          name: 'stock-average-calculator',
          component: () => import('@/pages/calculator/StockAverageCalculator'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'stock-aim-average-calculator',
          name: 'stock-aim-average-calculator',
          component: () => import('@/pages/calculator/StockAimAverageCalculator'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/settings',
      component: layout,
      children: [
        {
          path: 'service',
          name: 'settings-service',
          component: () => import('@/pages/settings/Service'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/finance',
      component: layout,
      children: [
        {
          path: 'create',
          name: 'finance-create',
          component: () => import('@/pages/check/Create'),
          meta: {
            authRequired: true
          }
        },
        {
          path: 'all',
          name: 'finance-all',
          component: () => import('@/pages/check/All'),
          meta: {
            authRequired: true
          }
        },
      ]
    },
    {
      path: '/auth-pages',
      component: {
        render(c) { return c('router-view') }
      },
      children: [
        {
          path: 'login',
          name: 'login',
          hideForAuth: true,
          component: () => import('@/pages/samples/auth-pages/login')
        },
        {
          path: 'register',
          name: 'register',
          component: () => import('@/pages/samples/auth-pages/register')
        }
      ]
    },
    {
      path: '/error-pages',
      component: {
        render(c) { return c('router-view') }
      },
      children: [
        {
          path: 'error-404',
          name: 'error-404',
          component: () => import('@/pages/samples/error-pages/error-404')
        },
        {
          path: 'error-500',
          name: 'error-500',
          component: () => import('@/pages/samples/error-pages/error-500')
        }
      ]
    },
    {
      path: '*',
      redirect: '/error-404',
      component: {
        render(c) { return c('router-view') }
      },
      children: [
        {
          path: 'error-404',
          component: () => import('@/pages/samples/error-pages/error-404')
        }
      ]
    }
  ]
}
router.onError(error => {
  console.error('Failure Reason: ', error);
  if (/ChunkLoadError:.*failed./i.test(error.message)) {
    window.location.reload();
  }
});
