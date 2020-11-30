require('./bootstrap');

import Vue from "vue";

Vue.component('route-interface-table', require('./components/table').default);
/**
 * Vue application
 */
new Vue({
    el: '#app',
});
