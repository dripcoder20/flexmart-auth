require('./bootstrap')
import Vue from 'vue'

Vue.component('logo', require('./components/Logo.vue').default)
Vue.component('login-form', require('./components/LoginForm.vue').default)
Vue.component(
    'mobile-number-input',
    require('./components/MobileNumber.vue').default
)
Vue.component(
    'mobile-validation-form',
    require('./components/MobileValidationForm').default
)
Vue.component(
    'verification-form',
    require('./components/VerificationForm').default
)
Vue.component(
    'password-reset-form',
    require('./components/PasswordResetForm').default
)
Vue.component('registration-form', require('./components/RegisterForm').default)
const app = new Vue({
    el: '#app'
})

export default app
