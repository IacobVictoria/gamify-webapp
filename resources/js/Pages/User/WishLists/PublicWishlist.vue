<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white">
                <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                    <h1
                        class="text-3xl font-bold text-center text-blue-800 mb-10"
                    >
                        🧾 Lista de dorințe a lui {{ friend.name }}
                    </h1>

                    <ul v-if="wishlist.length" class="space-y-6">
                        <li
                            v-for="item in wishlist"
                            :key="item.id"
                            class="flex items-center bg-white shadow-md rounded-xl p-4 transition hover:shadow-lg"
                        >
                            <img
                                :src="item.product.image_url"
                                :alt="item.product.name"
                                class="w-24 h-24 object-cover rounded-lg border border-gray-200"
                            />

                            <div class="ml-6 flex-1">
                                <div
                                    class="text-xl font-semibold text-gray-800"
                                >
                                    {{ item.product.name }}
                                </div>
                                <div class="mt-1">
                                    <template v-if="item.product.old_price">
                                        <div
                                            class="text-sm text-gray-400 line-through"
                                        >
                                            💸 {{ item.product.old_price }} Lei
                                        </div>
                                        <div class="text-red-600 font-bold">
                                            💰 {{ item.product.price }} Lei
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="text-gray-700 font-medium">
                                            💰 {{ item.product.price }} Lei
                                        </div>
                                    </template>
                                </div>

                                <div
                                    v-if="item.product.category"
                                    class="text-sm text-gray-400 mt-1"
                                >
                                    📦 Categorie: {{ item.product.category }}
                                </div>
                            </div>
                            <div
                                v-if="item.alreadyInMyWishlist"
                                class="mt-2 text-green-600 font-medium text-sm"
                            >
                                ✅ Deja în lista ta de dorințe
                            </div>
                            <button
                                v-else
                                @click="addToMyWishlist(item.product)"
                                class="ml-4 flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-full shadow hover:bg-red-600 transition duration-200 text-sm"
                                title="Adaugă la lista mea"
                            >
                                ❤️ Adaugă în lista mea
                            </button>
                        </li>
                    </ul>

                    <div
                        v-else
                        class="bg-yellow-100 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg"
                    >
                        🚫 Această listă de dorințe este momentan goală!
                    </div>
                </div>
            </div>
        </main>
    </Layout>
</template>
<script>
import Layout from "@/Layouts/Layout.vue";
export default {
    components: {
        Layout,
    },
    props: {
        wishlist: {
            type: Array,
            required: true,
        },
        friend: {
            type: Object,
        },
    },
    methods: {
        addToMyWishlist(product) {
            this.$inertia.post(
                route("user.wishlist.products.like", product.id),
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
