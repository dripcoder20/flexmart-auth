<template>
    <div class="tw-flex tw-flex-col">
        <div class="logo-wrapper">
            <h1 class="tw-text-accent tw-font-bold">Flex Account</h1>
            <p class="tw-text-white tw-text-center">
                GROCERY AT YOUR
                <br />DOOR STEPS
            </p>
            <p>{{mobile}}</p>
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
                    placeholder="Code"
                    name="otp"
                    class="form-control"
                    maxlength="6"
                />
                <span class="message">{{ form.errors.get('code') }}</span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-between">
                <a
                    href="javascript:"
                    @click="resend()"
                    class="forgot-password tw-text-white tw-text-sm tw-mb-4"
                    >Resend code</a
                >
                <button class="button button--primary">Confirm</button>
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
