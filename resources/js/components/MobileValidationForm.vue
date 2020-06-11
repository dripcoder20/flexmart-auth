<template>
    <div class="tw-flex tw-flex-col">
        <logo></logo>
        <div>
            <h1 class="tw-text-center tw-font-bold tw-text-lg tw-mb-8">
                Enter mobile number
            </h1>
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
                <span class="message">
                    {{ form.errors.get('mobile_number') }}
                </span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-center">
                <button class="button button--primary">Next</button>
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

                this.form.post('/api/mobile/validate').then((response) => {
                    window.location.href = '/mobile/verify?token=' + response.token
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
