import Vue from 'vue'
import './bootstrap'

window.Vue = require('vue');

// Components
import NotificationUser from './components/UserNotification.vue'

// Global component registration
Vue.component('notification-user', NotificationUser)

const app = new Vue({
    el: '#app',
    mounted() {
        console.log('Vue is mounted!');
    }
})





