<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div
                        class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8"
                    >
                        <div class="flex ml-14 mr-14">
                            <!-- Search Bar -->
                            <div class="relative mb-8 flex flex-1">
                                <label for="search" class="sr-only"
                                    >Search</label
                                >
                                <div class="relative w-full max-w-lg mx-auto">
                                    <input
                                        v-model="searchQuery"
                                        id="search"
                                        name="search"
                                        class="block w-full rounded-md border border-gray-300 pl-10 pr-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Search for products..."
                                        type="search"
                                        @input="fetchProducts"
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                    >
                                        <!-- Search Icon -->
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 19l6-6m0 0l-6-6m6 6H3"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <!-- Category Filter -->
                            <div class="relative mb-4 flex-1">
                                <select
                                    v-model="selectedCategory"
                                    @change="fetchProducts"
                                    class="block w-full rounded-md border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option value="">Toate categoriile</option>
                                    <option
                                        v-for="cat in categories"
                                        :key="cat"
                                        :value="cat"
                                    >
                                        {{ cat }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- Products Grid -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-12"
                        >
                            <div
                                v-for="product in products"
                                :key="product.id"
                                class="group relative bg-white shadow-lg rounded-lg p-4 flex flex-col items-center h-full"
                            >
                                <inertia-link
                                    :href="route('products.show', product.slug)"
                                    class="no-underline"
                                >
                                    <div
                                        class="relative w-full h-64 flex items-center justify-center overflow-hidden rounded-lg"
                                    >
                                        <img
                                            :src="product.image"
                                            alt="imageAlt"
                                            class="max-h-full max-w-full object-cover"
                                        />
                                    </div>
                                    <div
                                        class="w-full text-center mt-4 flex flex-col flex-grow"
                                    >
                                        <h3
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ product.name }}
                                        </h3>
                                        <div
                                            v-if="product.old_price"
                                            class="mt-2"
                                        >
                                            <p
                                                class="text-sm font-semibold text-red-500"
                                            >
                                                Reduceri aplicate:
                                                <span
                                                    v-for="(
                                                        discount, index
                                                    ) in product.discounts"
                                                    :key="index"
                                                >
                                                    -{{ discount }}%
                                                    <span
                                                        v-if="
                                                            index <
                                                            product.discounts
                                                                .length -
                                                                1
                                                        "
                                                        >,
                                                    </span>
                                                </span>
                                            </p>

                                            <p
                                                class="mt-2 text-base font-medium text-gray-500 line-through"
                                            >
                                                {{ product.old_price }} Lei
                                            </p>
                                            <p
                                                class="mt-2 text-lg font-bold text-gray-900"
                                            >
                                                {{ product.price }} Lei
                                            </p>
                                        </div>

                                        <!-- Dacă nu există reduceri, afișăm doar prețul normal -->
                                        <div
                                            v-else
                                            class="mt-4 text-lg font-medium text-gray-900"
                                        >
                                            {{ product.price }} Lei
                                        </div>
                                    </div>
                                </inertia-link>
                                <!-- Bottom Section: Like button -->
                                <div
                                    v-if="isLoggedIn()"
                                    class="w-full mt-auto flex justify-between items-center pt-4"
                                >
                                    <button
                                        @click="
                                            product.isFavorite
                                                ? dislikeProduct(product)
                                                : likeProduct(product)
                                        "
                                        class="text-2xl"
                                    >
                                        <AddHeartSVG
                                            :svg-class="
                                                product.isFavorite
                                                    ? 'text-red-400'
                                                    : 'text-gray-400'
                                            "
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import AddHeartSVG from "@/Components/AddHeartSVG.vue";
import Layout from "@/Layouts/Layout.vue";

export default {
    name: "Products.Index",
    components: {
        Layout,
        AddHeartSVG,
    },
    props: {
        products: Array,
        searchQueryProp: String,
        categories: Array,
        searchCategory: String,
    },
    data() {
        return {
            searchQuery: this.searchQueryProp,
            selectedCategory: this.searchCategory ? this.searchCategory : '',
        };
    },
    methods: {
        fetchProducts() {
            this.$inertia.get(
                route(
                    "products.index",
                    {
                        search: this.searchQuery,
                        category: this.selectedCategory,
                    },
                    {
                        preserveState: true,
                        preserveScroll: true,
                        replace: true,
                    }
                )
            );
        },

        async likeProduct(product) {
            await this.$inertia.post(
                route("wishlist.products.like", product.id),
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            );
        },

        async dislikeProduct(product) {
            await this.$inertia.post(
                route("wishlist.products.dislike", product.id),
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            );
        },
    },
};
</script>
