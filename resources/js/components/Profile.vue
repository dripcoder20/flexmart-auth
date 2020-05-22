<template>
    <div>
        <div>Name: {{ user.first_name }} {{ user.last_name }}</div>
        <div>Mobile: {{ user.mobile_number }}</div>
    </div>
</template>

<script>
    export default {
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
