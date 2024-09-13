<template>
    <div class="flex min-h-screen flex-1 flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
        <inertia-link :href="route('home')">
            <div
                class="w-32 flex items-center justify-center absolute top-4 left-2 px-12 py-5 rounded-md text-sm font-semibold text-white bg-gradient-to-r from-lightBlue to-darkBlue transition transform hover:-translate-y-2 hover:shadow-lg hover:shadow-gray-400 motion-reduce:transition-none motion-reduce:hover:transform-none">
                Back
            </div>

        </inertia-link>
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <!-- <Link href="/">
            <Logo class="mx-auto w-56 h-24" />
            </Link> -->
            <h2 class="mt-2 text-2xl font-bold leading-9 tracking-tight text-gray-900 heading-underline-text">
                {{ pageType === 'Login' ? 'Sign in to your account' : 'Create a new account' }}
            </h2>
        </div>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-6 py-12 shadow-custom sm:rounded-lg sm:px-12">
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GuestLayout',

    props: {
        pageType: {
            type: String,
            required: true,
        },
    },
};
</script>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.heading-underline-text::after {
    content: '';
    display: block;
    width: 7em;
    height: 2px;
    background-color: darkBlue;
    margin: 4px auto 0;
}

.shadow-custom {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
</style>
