<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <!-- <button @click="sendPromoMessage"
                            class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
                            Trimite Promoție
                        </button> -->
                        <!-- Search Bar -->
                        <div class="relative mb-8">
                            <label for="search" class="sr-only">Search</label>
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

                        <!-- Products Grid -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 border-t border-l border-gray-200">

                            <div v-for="product in products" :key="product.id"
                                class="group relative border-b border-r border-gray-200 p-4 sm:p-6">
                                <inertia-link :href="route('products.show', product.id)">
                                    <div
                                        class="aspect-w-3 aspect-h-2 overflow-hidden rounded-lg bg-gray-200 group-hover:opacity-75">
                                        <img src="/images/pic1.jpg" alt="imageAlt"
                                            class="h-full w-full object-cover object-center" />
                                    </div>
                                    <div class="pb-4 pt-10 text-center flex flex-col">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <!-- Link to product page -->

                                            {{ product.name }}
                                        </h3>
                                        <div v-if="product.old_price">
                                            <p class="mt-2 text-sm font-semibold text-red-500">
                                                Reduceri aplicate:
                                                <span v-for="(discount, index) in product.discounts" :key="index">
                                                    -{{ discount }}% <span v-if="index < product.discounts.length - 1">,
                                                    </span>
                                                </span>
                                            </p>

                                            <p class="mt-2 text-base font-medium text-gray-500 line-through">
                                                {{ product.old_price }} Lei
                                            </p>
                                            <p class="mt-2 text-lg font-bold text-gray-900">
                                                {{ product.price }} Lei
                                            </p>
                                        </div>

                                        <!-- Dacă nu există reduceri, afișăm doar prețul normal -->
                                        <div v-else class="mt-4 text-lg font-medium text-gray-900">
                                            {{ product.price }} Lei
                                        </div>

                                    </div>
                                </inertia-link>
                                <button @click="product.isFavorite ? dislikeProduct(product) : likeProduct(product)">
                                    <AddHeartSVG :svg-class="product.isFavorite ? 'text-red-400' : 'text-gray-400'">
                                    </AddHeartSVG>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import AddHeartSVG from '@/Components/AddHeartSVG.vue';
import Layout from '@/Layouts/Layout.vue';

export default {
    name: 'Products.Index',
    components: {
        Layout,
        AddHeartSVG
    },
    props: {
        products: Array,
        searchQueryProp: String,
    },
    data() {
        return {
            searchQuery: this.searchQueryProp,
        }
    },
    methods: {
        fetchProducts() {
            // console.log(this.searchQuery);
            this.$inertia.get(route('products.index', { search: this.searchQuery },
                {
                    preserveState: true, // Menține starea actuală a paginii
                    replace: true, // Înlocuiește istoricul în loc să adauge o nouă intrare
                }
            ))
        },
        async likeProduct(product) {
            await this.$inertia.post(route('wishlist.products.like', product.id), {}, {
                onSuccess: (page) => {

                }
            });
        },

        async dislikeProduct(product) {
            await this.$inertia.post(route('wishlist.products.dislike', product.id), {}, {
                onSuccess: (page) => {

                }
            });
        },

        // sendPromoMessage() {
        //     axios.post('/send-promotion', {
        //         phone: '+40727142462',
        //         name: 'John Doe',
        //         link: 'https://siteulmeu.ro'
        //     })
        //         .then(response => {
        //             console.log('Mesaj trimis cu succes:', response.data);
        //         })
        //         .catch(error => {
        //             console.error('Eroare la trimitere:', error);
        //         });
        // }


    },
}
</script>
