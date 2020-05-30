<template>
    <div class="tw-flex tw-flex-col">
        <div class="logo-wrapper">
            <h1 class="tw-text-accent tw-font-bold">Flex Account</h1>
            <p class="tw-text-white tw-text-center">
                GROCERY AT YOUR
                <br />DOOR STEPS
            </p>
        </div>
        <form
            @keydown="form.errors.clear($event.target.name)"
            @submit.prevent="register()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <input
                    v-model="form.first_name"
                    type="text"
                    placeholder="First Name"
                    name="firstname"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('first_name') }}</span>
            </div>
            <div class="form-group">
                <input
                    v-model="form.last_name"
                    type="text"
                    placeholder="Last Name"
                    name="lastname"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('last_name') }}</span>
            </div>
            <div class="form-group">
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    name="password"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('password') }}</span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-between">
                <button class="button button--primary">Register</button>
            </div>
            <div v-show="errorMessage">{{ errorMessage }}</div>
        </form>
        <div
            class="signup-footer tw-text-center tw-pt-10 tw-px-10 tw-pb-4 tw-bg-accent"
        >
            <a href="/login" class="button button--outline">Login Now</a>
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
        min-height: calc(100vh - 421px);
    }
</style>
