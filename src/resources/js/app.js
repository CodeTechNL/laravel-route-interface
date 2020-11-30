require('./bootstrap');

import Vue from "vue";

import VueColumnsResizable from 'vue-columns-resizable';
Vue.use(VueColumnsResizable);

Vue.component('route-interface-header', require('./components/header').default);
Vue.component('route-interface-table', require('./components/table').default);
/**
 * Vue application
 */
new Vue({
    el: '#app',
    data() {
        return {}
    },
    mounted() {

    }
});
