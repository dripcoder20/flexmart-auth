<template>
    <div>
        <form @submit.prevent="login()">
            <label>Mobile Number</label>
            <input type="text" v-model="form.mobile_number" />
            <span>{{ form.errors.get('mobile_number') }}</span>
            <br />
            <label>Password</label>
            <input type="password" v-model="form.password" />
            <span>{{ form.errors.get('password') }}</span>
            <br />
            <button>Login</button>
            No account?
            <a href="/signup">Register here</a>
        </form>
    </div>
</template>

<script>
    import { Form, Error } from '../libraries/form'
    import {
        osVersion,
        osName,
        browserName,
        browserVersion
    } from 'mobile-device-detect'
    export default {
        name: 'LoginForm',
        data() {
            return {
                form: new Form({
                    mobile_number: '',
                    password: '',
                    device_name: ''
                })
            }
        },
        methods: {
            async login() {
                this.form.device_name = `${osName}(${osVersion})-${browserName}(${browserVersion})`
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/login').then((response) => {
                    window.location.replace('/')
                })
            }
        }
    }
</script>

<style></style>
