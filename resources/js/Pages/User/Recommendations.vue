<template>
    <Layout>
        <main class="mt-32 mb-24 bg-gradient-to-b from-white via-gray-50 to-white min-h-screen pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-bold text-emerald-600 mb-10 text-center">
                    🌟 Recomandările tale personalizate
                </h1>

                <template v-if="recommendations.length">
                    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <li
                            v-for="item in recommendations"
                            :key="item.id"
                            class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition duration-300 border border-emerald-100"
                        >
                            <inertia-link
                                :href="route('products.show', item.slug)"
                                class="block no-underline"
                            >
                            <div
                                        class="relative w-full h-64 flex items-center justify-center overflow-hidden rounded-lg"
                                    >
                                    <img
                                        :src="item.image_url || '/placeholder.png'"
                                        :alt="item.name"
                                        class="max-h-full max-w-full object-cover"
                                    />
                                </div>

                                <div class="p-5 space-y-2 text-center">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        {{ item.name }}
                                    </h3>
                                    <p v-if="item.category" class="text-sm text-gray-500 italic">
                                        Categoria: {{ item.category }}
                                    </p>
                                    <p class="text-lg font-bold text-sky-700">
                                        {{ item.price }} RON
                                    </p>
                                </div>
                            </inertia-link>
                        </li>
                    </ul>
                </template>

                <template v-else>
                    <div
                        class="bg-yellow-50 border border-yellow-300 text-yellow-800 text-center py-6 px-8 rounded-xl mt-12"
                    >
                        💤 Nu ai recomandări încă. Explorează catalogul pentru a primi sugestii personalizate!
                    </div>
                </template>
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
