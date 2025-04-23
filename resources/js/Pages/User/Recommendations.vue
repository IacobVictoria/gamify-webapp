<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white">
                <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-pink-600 mb-8">
                        🎁 Recomandările tale
                    </h1>

                    <template v-if="recommendations.length">
                        <ul
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <li
                                v-for="item in recommendations"
                                :key="item.id"
                                class="bg-gradient-to-br from-sky-100 via-emerald-100 to-white p-5 rounded-xl border border-emerald-200 shadow-md hover:shadow-xl transition duration-300 ease-in-out"
                            >
                                <inertia-link
                                    :href="route('products.show', item.slug)"
                                    class="block space-y-4 no-underline"
                                >
                                    <img
                                        :src="
                                            item.image_url || '/placeholder.png'
                                        "
                                        :alt="item.name"
                                        class="rounded-xl object-cover w-full h-44 ring-1 ring-emerald-300"
                                    />

                                    <div class="text-center space-y-1">
                                        <h3
                                            class="text-xl font-semibold text-emerald-700 hover:text-emerald-800 transition"
                                        >
                                            🌟 {{ item.name }}
                                        </h3>

                                        <p
                                            v-if="item.category"
                                            class="text-sm text-gray-500 italic"
                                        >
                                            Categoria: {{ item.category }}
                                        </p>

                                        <p
                                            class="text-base font-bold text-sky-700"
                                        >
                                            {{ item.price }} RON
                                        </p>
                                    </div>
                                </inertia-link>
                            </li>
                        </ul>
                    </template>

                    <template v-else>
                        <div
                            class="bg-yellow-100 border border-yellow-300 text-yellow-800 text-center py-6 px-8 rounded-xl mt-12"
                        >
                            💤 Nu ai recomandări încă. Explorează catalogul
                            pentru a primi sugestii personalizate!
                        </div>
                    </template>
                </div>
            </div>
        </main>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";

defineProps({
    recommendations: Array,
});
</script>
