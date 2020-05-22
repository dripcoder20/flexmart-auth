<template>
   <div>
       <form @submit.prevent="login()">
           <input type="text" v-model="form.mobile_number">
           <input type="password" v-model="form.password">
           <button>Login</button>
   </form>
   </div>
</template>

<script>
import {osVersion, osName, browserName, browserVersion} from 'mobile-device-detect'
export default {
    name: "LoginForm",
    data() {
        return {
            form: {
                mobile_number: "",
                password: "",
                device_name: "",
            }
        }
    },
    methods: {
        async login() {
            this.form.device_name = `${osName}(${osVersion})-${browserName}(${browserVersion})`
            axios.get("/sanctum/csrf-cookie").then(response => { // Login...
                axios.post('/api/login', this.form).then(async ()=> {

                    console.log(response);
                    response = await axios.get('/api/me');
                    console.log(response);
                })
            });
        }
    }
}
</script>

<style>

</style>
