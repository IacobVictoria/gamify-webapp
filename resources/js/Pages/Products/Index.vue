<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <h2 class="sr-only">Products</h2>

                        <div class="grid grid-cols-2 border border-gray-200 sm:mx-0 md:grid-cols-3 lg:grid-cols-4">
                            <div v-for="product in products" :key="product.id"
                                class="group relative border-b border-r border-gray-200 p-4 sm:p-6">
                                <div
                                    class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg bg-gray-200 group-hover:opacity-75">
                                    <img src="/images/pic1.jpg" alt="imageAlt"
                                        class="h-full w-full object-cover object-center" />
                                </div>
                                <div class="pb-4 pt-10 text-center">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ product.name }}
                                    </h3>
                                    <div class="mt-3 flex flex-col items-center">
                                        <p class="mt-1 text-sm text-gray-500">0 reviews</p>
                                    </div>
                                    <p class="mt-4 text-base font-medium text-gray-900">0$ price </p>
                                </div>
                                <p class="mt-4 text-base font-medium text-gray-900">{{ product.score }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';

export default {
    name: 'Products.Index',
    components: {
        Layout
    },
    props: {
        products: Array
    }
}
</script>
