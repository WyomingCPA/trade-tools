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
            //authRequired: true
          }
        },
        {
          path: 'stock-aim-average-calculator',
          name: 'stock-aim-average-calculator',
          component: () => import('@/pages/calculator/StockAimAverageCalculator'),
          meta: {
            //authRequired: true
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