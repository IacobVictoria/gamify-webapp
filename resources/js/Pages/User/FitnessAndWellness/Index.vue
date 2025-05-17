<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CalorieTracker from "./CalorieTracker.vue";
import AiAssistent from "./AiAssistent.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="bg-gray-100 min-h-screen">
            <!-- Tracker de Calorii + AI Assistant side by side -->
            <section class="py-20 bg-gray-50">
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">
                    🧮 Calculator de Calorii & 🤖 Asistent AI
                </h2>
                <p class="text-lg text-gray-600 text-center mb-10">
                    Află necesarul tău caloric și primește sfaturi nutriționale
                    în timp real, oferite de AI.
                </p>
                <div class="px-24 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Tracker Card -->
                    <div
                        class="bg-white rounded-xl shadow-md p-6 h-full flex flex-col"
                    >
                        <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                            🔥 Calculator de Calorii
                        </h3>
                        <div class="flex-1">
                            <CalorieTracker />
                        </div>
                    </div>

                    <!-- AI Assistant Card -->
                    <div
                        class="bg-white rounded-xl shadow-md p-6 h-full flex flex-col"
                    >
                        <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                            🤖 Consultă AI-ul pentru sfaturi
                        </h3>
                        <div class="flex-1">
                            <AiAssistent />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
