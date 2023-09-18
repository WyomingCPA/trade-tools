import { createApp } from 'vue'
import i18n from './i18n'
import { createVuestic } from 'vuestic-ui'
import { createGtm } from '@gtm-support/vue-gtm'

import stores from './stores'
import router from './router'
import vuesticGlobalConfig from './services/vuestic-ui/global-config'
import App from './App.vue'

import axios from 'axios'
import middleware401 from './api/middleware401'
import middlewareCSRF from './api/middlewareCSRF'

const app = createApp(App)

axios.defaults.withCredentials = true

//axios.defaults.baseURL = 'http://localhost/trade-tools/public'
axios.defaults.baseURL = 'http://trade-tools.simpleitrunner.ru:3000/';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Content-Type'] = 'application/json'
const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = token
}
axios.interceptors.request.use(middlewareCSRF, (err) => Promise.reject(err))
axios.interceptors.response.use((resp) => resp, middleware401)

app.use(stores)
app.use(router)
app.use(i18n)
app.use(createVuestic({ config: vuesticGlobalConfig }))

if (import.meta.env.VITE_APP_GTM_ENABLED) {
  app.use(
    createGtm({
      id: import.meta.env.VITE_APP_GTM_KEY,
      debug: false,
      vueRouter: router,
    }),
  )
}

app.mount('#app')
