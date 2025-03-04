<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="min-h-screen bg-gradient-to-b from-green-100 to-white">

                        <!-- About Suppliers -->
                        <section class="max-w-7xl mx-auto py-16 px-6">
                            <h2 class="text-3xl font-bold text-gray-900 text-center">
                                Why Our Suppliers Matter? 🤝
                            </h2>
                            <p class="text-gray-600 text-lg text-center mt-4">
                                They provide us with **organic, high-quality ingredients**, ensuring that every meal is
                                packed with
                                **nutrition and flavor**.
                                Without them, our healthy food journey wouldn’t be possible!
                            </p>
                        </section>

                        <!-- Suppliers List -->
                        <section
                            class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-6 py-12">
                            <div v-for="supplier in suppliers" :key="supplier.id"
                                class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition">
                                <img :src="imagePath(supplier.image)" :alt="supplier.name"
                                    class="rounded-lg w-full h-48 object-cover mb-4">
                                <h3 class="text-2xl font-bold text-green-700">{{ supplier.name }}</h3>
                                <p class="text-gray-600 mt-2">{{ supplier.description }}</p>
                            </div>
                        </section>

                        <!-- Partnership & Trust -->
                        <section class=" py-16 px-6 text-center">
                            <h2 class="text-3xl font-bold text-gray-900">🚀 A Partnership Built on Trust & Quality</h2>
                            <p class="text-gray-600 text-lg max-w-3xl mx-auto mt-4">
                                Our suppliers are carefully selected to ensure the **best quality, fair trade, and
                                sustainable
                                practices**.
                                We work together to bring the **freshest and healthiest** ingredients straight to your
                                plate! 🍏🥑
                            </p>
                            <div class="flex justify-center mt-8 space-x-6">
                                <img :src="imagePath('/landing/logo1.jpg')"
                                    class="h-24 w-24 rounded-full border-2 border-green-500" alt="Supplier 1">
                                <img :src="imagePath('/landing/logo2.jpg')"
                                    class="h-24  w-24  rounded-full border-2 border-green-500" alt="Supplier 2">
                                <img :src="imagePath('/landing/logo3.png')"
                                    class="h-24 object-cover w-24 rounded-full border-2 border-green-500" alt="Supplier 3">

                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';

const suppliers = ref([
    { id: 1, name: "Organic Farms", description: "Supplying fresh organic vegetables and fruits.", image: "/landing/supplier_1.jpg" },
    { id: 2, name: "Pure Honey Co.", description: "Providing 100% natural honey, straight from the hive!", image: "/landing/supplier_2.jpg" },
    { id: 3, name: "Eco Dairy", description: "Bringing the best organic milk and dairy products.", image: "/landing/supplier_3.jpg" },
]);

</script>