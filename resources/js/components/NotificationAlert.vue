<template>
    <div
        class="tw-flex tw-items-center bg-green-success tw-text-white tw-font-bold tw-py-4 tw-pl-12"
        role="alert"
    >
        <p>{{ message }}</p>
    </div>
</template>

<script>
    export default {
        name: 'NotificationAlert',
        props: ['message']
    }
</script>

<style scoped lang="scss">
    .bg-green-success {
        background-color: #3abe59;
    }
</style>
