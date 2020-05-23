<template>
    <div class="tw-flex tw-flex-col">
        <logo></logo>
        <div>
            <h1 class="tw-text-center tw-font-bold tw-text-lg tw-mb-8">
                Nominate Your Password
            </h1>
            <p class="tw-text-white tw-text-center tw-px-12 tw-mb-4">
                Awesome password keeps your account secure.
            </p>
        </div>
        <form
            @keydown="form.errors.clear($event.target.name)"
            @submit.prevent="reset()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Your awesome password"
                    name="password"
                    class="form-control"
                />
                <span class="message">{{
                    form.errors.get('password_confirmation')
                }}</span>
            </div>
            <div class="form-group">
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Confirm your password"
                    name="password_confirmation"
                    class="form-control"
                />
                <span class="message">{{
                    form.errors.get('password_confirmation')
                }}</span>
            </div>

            <div class="tw-flex tw-w-full tw-items-center tw-justify-center">
                <button class="button button--primary">Reset</button>
            </div>
            <div v-show="errorMessage">{{ errorMessage }}</div>
        </form>
        <div
            class="signup-footer tw-text-center tw-pt-4 tw-px-4 tw-pb-4 tw-bg-white"
        >
            <a href="/login">Back to Login</a>
        </div>
    </div>
</template>

<script lang="javascript">
    import { Form } from '../libraries/form'
    export default {
        name: 'PasswordResetForm',
        props: ['token'],
        data() {
            return {
                form: new Form({
                    password: '',
                    password_confirmation: ''
                }),
                errorMessage: ''
            }
        },
        created() {
            this.form.token = this.token;
        },
        methods: {
            async reset() {
                this.errorMessage = ''
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/reset-password?token=' + this.form.token).then(() => {
                    window.location.replace('/login')
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
