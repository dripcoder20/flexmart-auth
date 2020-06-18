<template>
    <div>
        <div>Name: {{ user.first_name }} {{ user.last_name }}</div>
        <div>Mobile: {{ user.mobile_number }}</div>
        <div>
            Address: {{ user.address }} {{ user.city }} {{ user.province }}
        </div>
    </div>
</template>

<script>
    export default {
        name: 'profile',
        data() {
            return {
                user: ''
            }
        },
        async created() {
            const response = await this.$axios.get('/api/me')
            this.user = response.data
        }
    }
</script>

<style></style>
