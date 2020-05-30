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
            @submit.prevent="validate()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <mobile-number-input
                    prefix="+63"
                    :value="form.mobile_number.replace('+63', '')"
                    @input-mobile="form.mobile_number = $event"
                ></mobile-number-input>
                <span class="message">{{
                    form.errors.get('mobile_number')
                }}</span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-between">
                <button class="button button--primary">Verify</button>
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
        name: 'MobileValidationForm',
        data() {
            return {
                form: new Form({
                    mobile_number: ''
                }),
                errorMessage: ''
            }
        },
        methods: {
            async validate() {
                this.errorMessage = ''
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.post('/api/validate').then((response) => {
                    window.location.replace('/verify?token=' + response.token)
                }).catch((error)=> {
                    this.errorMessage = error.message
                })
            },
            resend() {

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
