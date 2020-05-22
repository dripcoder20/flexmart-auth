require('./bootstrap')
import Vue from 'vue'

Vue.component('login-form', require('./components/LoginForm.vue').default)
const app = new Vue({
    el: '#app'
})

export default app
