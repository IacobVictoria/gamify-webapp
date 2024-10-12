<template>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-xl font-bold text-gray-900">Supplier : {{ supplier.name }} </h2>
                <div class="mb-4">
                    <button @click="back()" class="text-blue-500 hover:underline">
                        Back
                    </button>
                </div>
                <div
                    class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-16 gap-y-16">
                    <div v-for="product in products" :key="product.id" class="max-w-xs mb-16">
                        <div class="relative h-full">
                            <div class="relative h-48 w-full overflow-hidden rounded-lg">
                                <img src="/images/pic4.jpg" class="h-full w-full object-cover object-center" />
                            </div>
                            <div class="relative mt-4">
                                <h3 class="text-sm font-medium text-gray-900">{{ product.name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ product.description }}</p>
                            </div>
                            <div
                                class="absolute inset-x-0 top-0 flex h-48 items-end justify-end overflow-hidden rounded-lg p-4">
                                <p class="relative text-lg font-semibold text-white">{{ product.price }}</p>
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
        products: {
            type: Array,
            required: true
        },

        supplier: {
            type: Object,
            required: true
        }
    },

    methods: {
        back() {
            this.$inertia.get(route('admin.purchase_suppliers.index'));
        }
    }
}
</script>