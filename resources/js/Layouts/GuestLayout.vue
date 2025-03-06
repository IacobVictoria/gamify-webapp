<template>
    <Layout>
        <div class="flex min-h-screen flex-1 flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            
            </div>
            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
                <div class="bg-white px-6 py-12 shadow-custom sm:rounded-lg sm:px-12">
                    <slot />
                </div>
            </div>
        </div>
    </Layout>
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
    components:{
        Layout
    }
};
</script>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from './Layout.vue';
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
