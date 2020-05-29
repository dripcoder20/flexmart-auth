<template>
    <div class="mobile-number-input">
        <span class="prefix">{{ prefix }}</span>
        <input
            type="text"
            placeholder="Mobile number"
            name="mobile_number"
            :value="value"
            @input="emitValue"
        />
    </div>
</template>

<script>
    export default {
        props: {
            prefix: {
                default: '+63',
                type: String
            },
            value: {
                default: '',
                type: String
            }
        },
        methods: {
            emitValue(value) {
                this.$emit('input-mobile', this.prefix + value.target.value)
            }
        }
    }
</script>

<style lang="scss" scoped>
    .mobile-number-input {
        background: white;
        border-radius: 5px;
        border: #efefef solid 1px;
        width: 278px;
        height: 45px;
        box-shadow: 1px 1px 1px 0 #00000021;
        @apply tw-mb-1 tw-px-2 tw-flex tw-items-center;
        .prefix {
            @apply tw-flex tw-pr-2 tw-mr-2;
            border-right: solid #dbdbdb 1px;
        }
        input {
            height: 45px;
            width: 100%;
            &:focus {
                outline: none;
            }
        }
    }
</style>
