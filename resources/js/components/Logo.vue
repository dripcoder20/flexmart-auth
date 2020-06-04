<template>
    <div class="logo-wrapper">
        <h1 class="tw-text-accent tw-font-bold">Flex Account</h1>
        <p class="tw-text-white tw-text-center">
            GROCERY AT YOUR
            <br />DOOR STEPS
        </p>
        <slot></slot>
    </div>
</template>
<style lang="scss" scoped>
    .logo-wrapper {
        margin-top: 135px;
        @apply tw-flex tw-flex-col tw-items-center tw-mb-8 tw-bg-brand;
        h1 {
            font-size: 2rem;
        }
    }
</style>
