<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white">
                <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                    <h1
                        class="text-4xl font-extrabold tracking-tight text-pink-600 mb-2"
                    >
                        💖 Lista de dorințe 💖
                    </h1>
                    <div class="flex gap-4 items-center">
                        <h2 class="text-lg text-purple-600 font-semibold">
                            Produsele tale preferate! ✨
                        </h2>
                    </div>
                    <form class="mt-12">
                        <div>
                            <template v-if="favorites.length > 0">
                                <ul
                                    role="list"
                                    class="divide-y divide-gray-200 border-b border-t border-gray-200"
                                >
                                    <li
                                        v-for="item in favorites"
                                        :key="item.product.id"
                                        class="flex py-6 sm:py-10"
                                    >
                                        <div class="flex-shrink-0">
                                            <img
                                                :src="item.product.image_url"
                                                :alt="item.product.description"
                                                class="h-24 w-24 rounded-lg object-cover object-center sm:h-32 sm:w-32"
                                            />
                                        </div>

                                        <div
                                            class="relative ml-4 flex flex-1 flex-col justify-between sm:ml-6"
                                        >
                                            <div>
                                                <div
                                                    class="flex justify-between sm:grid sm:grid-cols-2"
                                                >
                                                    <div class="pr-6">
                                                        <h3 class="text-sm">
                                                            <a
                                                                class="font-medium text-gray-700 hover:text-gray-800"
                                                                >{{
                                                                    item.product
                                                                        .name
                                                                }}</a
                                                            >
                                                        </h3>
                                                        <p
                                                            v-if="
                                                                item.product
                                                                    .category
                                                            "
                                                            class="mt-1 text-sm text-gray-500"
                                                        >
                                                            {{
                                                                item.product
                                                                    .category
                                                            }}
                                                        </p>
                                                    </div>

                                                    <div class="text-right">
                                                        <div
                                                            v-if="
                                                                item.product
                                                                    .old_price
                                                            "
                                                        >
                                                            <p
                                                                class="text-sm text-red-500 line-through"
                                                            >
                                                                {{
                                                                    item.product
                                                                        .old_price
                                                                }}
                                                                Lei
                                                            </p>
                                                            <p
                                                                class="text-sm font-medium text-green-600"
                                                            >
                                                                {{
                                                                    item.product
                                                                        .price
                                                                }}
                                                                Lei
                                                            </p>
                                                        </div>
                                                        <p
                                                            v-else
                                                            class="text-sm font-medium text-gray-900"
                                                        >
                                                            {{
                                                                item.product
                                                                    .price
                                                            }}
                                                            Lei
                                                        </p>
                                                        <button
                                                            type="button"
                                                            @click="
                                                                removeFromWishlist(
                                                                    item.product
                                                                        .id
                                                                )
                                                            "
                                                            class="ml-4 text-sm font-medium text-red-500 hover:text-red-700 sm:ml-0 sm:mt-3"
                                                            title="Remove from wishlist"
                                                        >
                                                            <RemoveTrashSVG
                                                                class="w-5 h-5"
                                                            />
                                                        </button>
                                                    </div>

                                                    <div class="mt-6">
                                                        <label
                                                            for="quantity"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Cantitate</label
                                                        >
                                                        <select
                                                            id="quantity"
                                                            v-model="quantity"
                                                            class="mt-1 block w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        >
                                                            <option
                                                                value=""
                                                                disabled
                                                            >
                                                                Selectează cantitatea
                                                            </option>
                                                            <option
                                                                v-for="num in 10"
                                                                :key="num"
                                                                :value="num"
                                                            >
                                                                {{ num }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div
                                                    class="mt-4 flex items-center sm:absolute sm:left-1/2 sm:top-0 sm:mt-0 sm:block"
                                                >
                                                    <button
                                                        type="button"
                                                        class="ml-4 text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:ml-0 sm:mt-3"
                                                    >
                                                        <RemoveTrashSVG></RemoveTrashSVG>
                                                    </button>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                @click="addToCart(item.product)"
                                                class="w-64 flex items-center justify-center gap-2 px-6 py-3 mt-4 text-white bg-emerald-500 border border-transparent rounded-lg shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
                                            >
                                                🛒 Adaugă în coș
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            </template>
                            <template v-else>
                                <div
                                    class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-800 text-center py-4 px-6 rounded-lg shadow-md mb-6 text-lg font-medium"
                                >
                                    😔 No favorites yet... go explore and find
                                    some treasures! 🛍️
                                </div>
                            </template>
                            <inertia-link
                                :href="route('products.index')"
                                class="inline-block mt-4 no-underline bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md"
                            >
                                🔍 Explorează produsele
                            </inertia-link>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </Layout>
</template>
<script>
import HeartSVG from "@/Components/HeartSVG.vue";
import RemoveTrashSVG from "@/Components/RemoveTrashSVG.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Layout from "@/Layouts/Layout.vue";
export default {
    components: {
        AuthenticatedLayout,
        HeartSVG,
        RemoveTrashSVG,
        Layout,
    },
    props: {
        favorites: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            quantity: 1,
        };
    },
    methods: {
        addToCart(product) {
            this.$inertia.post(route("user.shopping-cart.add"), {
                product: product,
                quantity: this.quantity,
            });
        },
        removeFromWishlist(productId) {
            this.$inertia.post(
                route("user.wishlist.products.dislike", {
                    productId: productId,
                })
            );
        },
    },
};
</script>
