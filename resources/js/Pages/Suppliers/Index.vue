<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div
                        class="min-h-screen bg-gradient-to-b from-green-100 to-white"
                    >
                        <!-- Despre furnizori -->
                        <section class="max-w-7xl mx-auto py-16 px-6">
                            <h2
                                class="text-3xl font-bold text-gray-900 text-center"
                            >
                                De ce sunt importanți furnizorii noștri? 🤝
                            </h2>
                            <p class="text-gray-600 text-lg text-center mt-4">
                                Ei ne oferă ingrediente **organice și de înaltă
                                calitate**, asigurându-se că fiecare masă este
                                plină de **nutriție și savoare**. Fără ei,
                                drumul nostru către o alimentație sănătoasă nu
                                ar fi posibil!
                            </p>
                        </section>

                        <!-- Suppliers List -->
                        <section
                            class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-6 py-12"
                        >
                            <div
                                v-for="supplier in suppliers"
                                :key="supplier.id"
                                class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition"
                            >
                                <img
                                    :src="imagePath(supplier.image)"
                                    :alt="supplier.name"
                                    class="rounded-lg w-full h-48 object-cover mb-4"
                                />
                                <h3 class="text-2xl font-bold text-green-700">
                                    {{ supplier.name }}
                                </h3>
                                <p class="text-gray-600 mt-2">
                                    {{ supplier.description }}
                                </p>
                            </div>
                        </section>

                        <!-- Partnership & Trust -->
                        <section class="py-16 px-6 text-center">
                            <h2 class="text-3xl font-bold text-gray-900">
                                🚀 Un parteneriat bazat pe încredere și calitate
                            </h2>
                            <p
                                class="text-gray-600 text-lg max-w-3xl mx-auto mt-4"
                            >
                                Furnizorii noștri sunt atent selectați pentru a
                                garanta **cea mai bună calitate**, **comerț
                                echitabil** și **practici sustenabile**.
                                Colaborăm pentru a aduce cele **mai proaspete și
                                mai sănătoase** ingrediente direct în farfuria
                                ta! 🍏🥑
                            </p>

                            <div class="flex justify-center mt-8 space-x-6">
                                <img
                                    :src="imagePath('/landing/logo1.jpg')"
                                    class="h-24 w-24 rounded-full border-2 border-green-500"
                                    alt="Supplier 1"
                                />
                                <img
                                    :src="imagePath('/landing/logo2.jpg')"
                                    class="h-24 w-24 rounded-full border-2 border-green-500"
                                    alt="Supplier 2"
                                />
                                <img
                                    :src="imagePath('/landing/logo3.png')"
                                    class="h-24 object-cover w-24 rounded-full border-2 border-green-500"
                                    alt="Supplier 3"
                                />
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Layout from "@/Layouts/Layout.vue";

const suppliers = ref([
    {
        id: 1,
        name: "Organic Farms",
        description: "Furnizează legume și fructe organice proaspete.",
        image: "/landing/supplier_1.jpg",
    },
    {
        id: 2,
        name: "Pure Honey Co.",
        description: "Oferă miere 100% naturală, direct din stup!",
        image: "/landing/supplier_2.jpg",
    },
    {
        id: 3,
        name: "Eco Dairy",
        description: "Aduce cele mai bune produse lactate ecologice.",
        image: "/landing/supplier_3.jpg",
    },
]);
</script>
