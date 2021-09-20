import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AutocompleteVue from 'autocomplete-vue';
import { ValidationProvider } from 'vee-validate';
import VueMeta from 'vue-meta';
import axios from './api.js';

Vue.component('ValidationProvider', ValidationProvider);
 
Vue.component('autocomplete-vue', AutocompleteVue);

Vue.use(VueToast);
Vue.use(VueMeta, {
  // optional pluginOptions
  refreshOnceOnNavigation: true
});

Vue.config.productionTip = false

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
