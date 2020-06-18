<template>
    <div class="tw-flex tw-flex-col">
        <logo></logo>
        <div>
            <h1 class="tw-text-center tw-font-bold tw-text-lg tw-mb-8">
                Delivery Address
            </h1>
        </div>
        <form
            v-if="showForm"
            @keydown="form.errors.clear($event.target.name)"
            @submit.prevent="update()"
            class="tw-flex tw-flex-col tw-px-12 tw-items-center"
        >
            <div class="form-group">
                <label>Province</label>
                <select
                    v-model="selected.province"
                    name="province"
                    disabled
                    class="form-control"
                >
                    <option value="" disabled selected>Select Province</option>
                    <option
                        v-for="(province, provinceIndex) in provinces"
                        :key="provinceIndex"
                        :value="province"
                        >{{ province.provDesc }}</option
                    >
                </select>
                <span class="message">{{ form.errors.get('province') }}</span>
            </div>
            <div class="form-group">
                <label>City</label>
                <select
                    v-model="selected.city"
                    disabled
                    :key="form.city"
                    name="city"
                    class="form-control"
                >
                    <option value="" disabled selected>Select City</option>
                    <option
                        v-for="(city, cityIndex) in cities"
                        :key="cityIndex"
                        :value="city"
                        >{{ city.citymunDesc }}</option
                    >
                </select>
                <span class="message">{{ form.errors.get('city') }}</span>
            </div>
            <div class="form-group">
                <label>Barangay</label>
                <select
                    v-model="selected.barangay"
                    :key="form.barangay"
                    :disabled="!selected.city"
                    name="barangay"
                    class="form-control"
                >
                    <option value="" disabled selected>Select Barangay</option>
                    <option
                        v-for="(barangay, barangayIndex) in barangays"
                        :key="barangayIndex"
                        :value="barangay"
                        >{{ barangay.brgyDesc }}</option
                    >
                </select>
                <span class="message">{{ form.errors.get('barangay') }}</span>
            </div>
            <div class="form-group">
                <label>Complete Address</label>
                <input
                    v-model="form.address"
                    type="text"
                    name="address"
                    class="form-control"
                />
                <span class="message">{{ form.errors.get('address') }}</span>
            </div>
            <div class="tw-flex tw-w-full tw-items-center tw-justify-center">
                <button class="button button--primary">Next</button>
            </div>
            <div v-show="errorMessage">{{ errorMessage }}</div>
        </form>
        <div
            class="signup-footer tw-text-center tw-pt-4 tw-px-4 tw-pb-4 tw-bg-white"
        >
            <a href="/">Skip for now</a>
        </div>
    </div>
</template>

<script lang="javascript">
    import { Form, Error } from '../libraries/form'
    import PROVINCE from '../data/province'
    import CITY from '../data/city'
    import BARANGAY from '../data/barangay'
    import orderBy from 'lodash/orderBy'
    export default {
        name: 'UpdateForm',
        props: ['token'],
        data() {
            return {
                form: new Form({
                    province: '',
                    city: '',
                    barangay: '',
                    address: ''
                }),
                selected: {
                    province: '',
                    city: '',
                    barangay: ''
                },
                showForm: true,
                errorMessage: ''
            }
        },
        watch: {
            'selected.province'(value) {
                this.form.province = value.provDesc;
                this.selected.city = ''
                this.selected.barangay = ''
            },
            'selected.city'(value) {
                if (value) {
                    this.form.city = value.citymunDesc
                    this.selected.barangay = ''
                }
            },
            'selected.barangay'(value) {
                if (value) this.form.barangay = value.brgyDesc
            }
        },
        created() {
            this.selected.province = this.provinces.find(item => item.provDesc === "PANGASINAN");
            setTimeout(() => {
                this.selected.city = this.cities.find(item => item.citymunDesc === "DAGUPAN CITY");
            }, 100);

        },
        computed: {
            provinces() {
                return orderBy(PROVINCE, ['provDesc'])
            },
            cities() {
                return CITY.filter(city => city.provCode === this.selected.province.provCode)
            },
            barangays() {
                return BARANGAY.filter(barangay => barangay.citymunCode === this.selected.city.citymunCode)
            }
        },
        methods: {
            async update() {
                this.errorMessage = ''
                await this.$axios.get('/sanctum/csrf-cookie')

                this.form.put('/api/account').then((response) => {
                    window.location.href = '/'
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
