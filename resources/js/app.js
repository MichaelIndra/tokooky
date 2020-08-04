require('./bootstrap');
require('admin-lte');
 
window.Vue = require('vue');
 
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import Vuex from 'vuex';
import axios from 'axios';
import App from './components/App.vue';
import VueSweetalert2 from 'vue-sweetalert2'; 

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
import VueCurrencyFilter from 'vue-currency-filter'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import Multiselect from 'vue-multiselect';

window.moment = require('moment');
window.Vuex = Vuex;

Vue.component('multiselect', Multiselect)
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(VueAxios, axios);
Vue.use(Vuex);

Vue.use(VueCurrencyFilter,
  {
    symbol : 'Rp.',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
  })
import Dashboard from './components/layout/DashboardLayout.vue'  

import KonsumenIndex from './components/konsumen/KonsumenIndex.vue';
import KonsumenCreate from './components/konsumen/KonsumenCreate.vue';
import KonsumenEdit from './components/konsumen/KonsumenEdit.vue';

import BarangIndex from './components/barang/BarangIndex.vue';
import BarangCreate from './components/barang/BarangCreate.vue';
import BarangEdit from './components/barang/BarangEdit.vue';

import TransaksiIndex from './components/transaksi/TransaksiIndex.vue';
import TransaksiCreate from './components/transaksi/TransaksiCreate.vue';

import InvoicePrint from './components/invoice/InvoiceCreate.vue';

import KeteranganQty from './components/transaksi/KeteranganQty/KeteranganQtyCreate.vue';



const routes = [
          {
            path: "/",
            name: "home",
            component: Dashboard
          },
          {
            name: 'transaksis',
            path: '/transaksis',
            component: TransaksiIndex
          },
          {
            name: 'konsumens',
            path: '/konsumens/',
            component: KonsumenIndex
          },
          {
            name: 'barangs',
            path: '/barangs/',
            component: BarangIndex
          },
          {
              name: 'createkonsumen',
              path: '/konsumens/create',
              component: KonsumenCreate
          },
          {
              name: 'editkonsumen',
              path: '/konsumens/edit/:id',
              component: KonsumenEdit
          },

          {
            name: 'createbarang',
            path: '/barangs/create',
            component: BarangCreate
          },

          {
            name: 'editbarang',
            path: '/barangs/edit/:id',
            component: BarangEdit
          },
          {
            name: 'createtransaksi',
            path: '/transaksis/create',
            component: TransaksiCreate
          },
          {
            name: 'invoiceprint',
            path: '/:no_invoice',
            component: InvoicePrint
          },
          {
            name: 'keteranganqty',
            path: '/keteranganqty/create',
            component: KeteranganQty
          },
      
    
];

const router = new VueRouter({ mode: 'history', routes: routes});
// const app = new Vue(Vue.util.extend({ router }, App)).$mount('#app');
const app = new Vue({
  el: '#app',
  router
});
