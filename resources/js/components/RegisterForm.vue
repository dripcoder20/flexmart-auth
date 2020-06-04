<template>
    <div class="tw-flex tw-flex-col">
        <logo></logo>
        <div>
            <h1 class="tw-text-center tw-font-bold tw-text-lg tw-mb-8">
                Complete your Profile
            </h1>
        </div>
        <form
            @keydown="form.errors.clear($event.target.name)"
            @submit.prevent="register()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <label>First Name</label>
                <input
                    v-model="form.first_name"
                    type="text"
                    placeholder="Your awesome first name"
                    name="firstname"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('first_name') }}</span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input
                    v-model="form.last_name"
                    type="text"
                    placeholder="Your awesome last name"
                    name="lastname"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('last_name') }}</span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Your secure password"
                    name="password"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('password') }}</span>
            </div>
            <p class="tw-text-white tw-text-center tw-mb-4">
                By registering you agree with our terms and conditions
            </p>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-center">
                <button class="button button--primary">Register</button>
            </div>
            <div v-show="errorMessage">{{ errorMessage }}</div>
        </form>
        <div
            class="signup-footer tw-text-center tw-pt-4 tw-px-4 tw-pb-4 tw-bg-white"
        >
            <a href="/login">Have an existing account? Click here</a>
        </div>
    </div>
</template>

<script lang="javascript">
    import { Form, Error } from '../libraries/form'
    export default {
        name: 'RegisterForm',
        props: ['token'],
        data() {
            return {
                form: new Form({
                    first_name: '',
                    last_name: '',
                    password: '',
                    confirmation_token: ''
                }),
                errorMessage: ''
            }
        },
        created() {
            this.form.confirmation_token = this.token;
        },
        methods: {
            async register() {
                this.errorMessage = ''
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/register').then((response) => {
                    window.location.replace('/update-profile')
                }).catch((error)=> {
                    this.errorMessage = error.message
                })
            }
        }
    }
</script>
<style lang="scss" scoped>
    body {
        @apply tw-bg-brand;
    }
    .logo-wrapper {
        margin-top: 135px;
        @apply tw-flex tw-flex-col tw-items-center tw-mb-8 tw-bg-brand;
        h1 {
            @apply tw-text-2xl;
        }
    }
    form {
        min-height: calc(100vh - 378px);
    }
</style>
