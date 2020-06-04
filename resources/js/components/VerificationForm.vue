<template>
    <div class="tw-flex tw-flex-col">
        <logo></logo>
        <div>
            <h1 class="tw-text-center tw-font-bold tw-text-lg tw-mb-2">
                Enter One Time Code
            </h1>
            <p class="tw-text-white tw-text-center tw-px-12 tw-mb-4">
                We sent your verification code to
                <br />
                {{ mobile }}
            </p>
        </div>
        <form
            @keydown="form.errors.clear($event.target.name)"
            @submit.prevent="verify()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <input
                    v-model="form.code"
                    type="text"
                    placeholder="Enter one time password"
                    name="otp"
                    class="form-control"
                    maxlength="6"
                />
                <span class="message">{{ form.errors.get('code') }}</span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-center">
                <button class="button button--primary">Verify</button>
            </div>
            <div class="tw-text-white tw-text-center">
                Did not received the code?
                <a
                    href="javascript:"
                    @click="resend()"
                    class="tw-block tw-text-white tw-text-accent tw-mb-4"
                    >Resend code</a
                >
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
        name: 'VerificationForm',
        data() {
            return {
                form: new Form({
                    code: '',
                    token: ''
                }),
                errorMessage: ''
            }
        },
        props: [ 'token', 'mobile' ],
        created() {
            this.form.token = this.token;
        },
        methods: {
            async verify() {
                this.errorMessage = '';
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/verify').then((response) => {
                    window.location.replace('/register?token=' + response.confirmation_token)
                }).catch((error)=> {
                    this.errorMessage = error.message
                })
            },
            async resend() {
                this.errorMessage = '';
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/resend-verification').catch((error)=> {
                    this.errorMessage = error.message
                })
            }
        }
    }
</script>
<style lang="scss" scoped>
    .logo-wrapper {
        margin-top: 135px;
        @apply tw-flex tw-flex-col tw-items-center tw-mb-8 tw-bg-brand;
        h1 {
            @apply tw-text-2xl;
        }
    }
    form {
        min-height: calc(100vh - 418px);
    }
</style>
