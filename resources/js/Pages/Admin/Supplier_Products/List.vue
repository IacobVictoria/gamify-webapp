<template>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <div class="relative mb-8">
                    <label for="search" class="sr-only">Caută</label>
                    <div class="relative w-full max-w-lg mx-auto">
                        <input v-model="searchQuery" id="search" name="search"
                            class="block w-full rounded-md border border-gray-300 pl-10 pr-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Search for products..." type="search" @input="fetchProducts" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <!-- Search Icon -->
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l6-6m0 0l-6-6m6 6H3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button @click="back()" class="text-blue-500 hover:underline">
                        Înapoi
                    </button>
                </div>

                <div
                    class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-16 gap-y-16">
                    <div v-for="product in products" :key="product.id" class="max-w-xs mb-16">
                        <h2 class="text-xl font-bold text-gray-900">Furnizor : {{ product.supplier.name }} </h2>
                        <div class="relative h-full">
                            <div class="relative h-48 w-full overflow-hidden rounded-lg">
                                <img src="/images/pic4.jpg" class="h-full w-full object-cover object-center" />
                            </div>

                            <div class="relative mt-4">
                                <h3 class="text-sm font-medium text-gray-900">{{ product.name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ product.description }}</p>
                            </div>

                            <div class="mt-6">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Cantitate</label>
                                <select id="quantity" v-model="quantity[product.id]"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    style="max-height: 150px; overflow-y: auto;">
                                    <option value="" disabled>Selectează cantitatea</option>
                                    <option v-for="num in 10" :key="num" :value="num">{{ num }}</option>
                                </select>
                            </div>

                            <div
                                class="absolute inset-x-0 top-0 flex h-48 items-end justify-end overflow-hidden rounded-lg p-4">
                                <p class="relative text-lg font-semibold text-white">{{ product.price }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a @click="addToBag(product)"
                                class="relative flex items-center justify-center rounded-md border border-transparent bg-gray-100 px-8 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200">
                                Adaugă în coș
                            </a>
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
        searchQueryProp: {
            type: String
        }
    },

    data() {
        return {
            searchQuery: this.searchQueryProp,
            quantity: {},
        }
    },

    methods: {
        back() {
            return this.$inertia.get(route('admin.purchase_suppliers.index'));
        },

        fetchProducts() {
            return this.$inertia.get(route('admin.suppliers_products.index', { search: this.searchQuery }, {
                preserveState: true,
                replace: true,
            }));
        },

        addToBag(product) {
            return this.$inertia.post(route('admin.shopping-cart.store'), {
                productId: product.id,
                quantity: this.quantity[product.id],
            });
        }
    }
}

</script>