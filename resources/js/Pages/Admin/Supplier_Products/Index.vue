<template>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="py-16 sm:py-24 xl:mx-auto xl:max-w-7xl xl:px-8">
                <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8 xl:px-0">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Choose by Supplier</h2>
                    <a :href="route('admin.suppliers_products.index')" class="hidden text-sm font-semibold text-indigo-600 hover:text-indigo-500 sm:block">
                        Shopping ...
                        <span aria-hidden="true"> &rarr;</span>
                    </a>
                </div>

                <div class="mt-4 flow-root">
                    <div class="-my-2">
                        <div class="relative box-content h-80 overflow-x-auto py-2 xl:overflow-visible">
                            <div
                                class="absolute flex space-x-8 px-4 sm:px-6 lg:px-8 xl:relative xl:grid xl:grid-cols-5 xl:gap-x-8 xl:space-x-0 xl:px-0">
                                <inertia-link v-for="supplier in suppliers" :key="supplier.name" :href="route('admin.suppliers_products.show',supplier.id)"
                                    class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                                    <span aria-hidden="true" class="absolute inset-0">
                                        <img src="/images/best.jpg" alt=""
                                            class="h-full w-full object-cover object-center" />
                                    </span>
                                    <span aria-hidden="true"
                                        class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-gray-800 opacity-50" />
                                    <span class="relative mt-auto text-center text-xl font-bold text-white">{{
                                        supplier.name
                                        }}</span>
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },

    props: {
        suppliers: {
            type: Array,
            required: true
        }
    }
}

</script>