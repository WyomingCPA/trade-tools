import Vue from 'vue'
import { store } from './store'
import App from './App.vue'
import router from './router'
import { BootstrapVue } from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueGoodTablePlugin from "vue-good-table";
import axios from 'axios'


Vue.use(BootstrapVue)
Vue.use(VueSweetalert2)
Vue.use(VueGoodTablePlugin);
Vue.config.productionTip = false
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = 'http://localhost/trade-tools/public'
//axios.defaults.baseURL = 'http://trade-tools.simpleitrunner.ru:3000/';
axios.defaults.withCredentials = true

store.dispatch('me').then(() => {
  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
})
