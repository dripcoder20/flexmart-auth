require('./bootstrap')
import Vue from 'vue'

Vue.component('login-form', require('./components/LoginForm.vue').default)
Vue.component(
    'mobile-number-input',
    require('./components/MobileNumber.vue').default
)
const app = new Vue({
    el: '#app'
})

export default app
