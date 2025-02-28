<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CalorieTracker from './CalorieTracker.vue';
import AiAssistent from './AiAssistent.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="bg-gray-100 min-h-screen">
            <!-- 🏋️ Introducere -->
            <section class="relative w-full py-16 px-6 bg-gradient-to-r from-green-400 to-teal-500 text-white text-center">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-4xl font-extrabold mb-4">Welcome to Your Wellness Journey! 🌱</h1>
                    <p class="text-lg opacity-90">Let’s find out how many calories you need and get personalized advice from our AI assistant.</p>
                </div>
            </section>
            <!-- 🚀 Tracker de Calorii -->
            <section class="py-20 bg-white text-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">🔥 Track Your Calories</h2>
                    <p class="text-lg text-gray-600">Fill in your details to find out your optimal calorie intake.</p>
                    <CalorieTracker />
            </section>

            <!-- 🤖 AI Assistant -->
            <section class="py-20 bg-gray-50 text-center">
                    <h2 class="text-3xl font-bold">🤖 Meet Your AI Assistant</h2>
                    <p class="text-lg text-gray-600">Based on your data, our AI will give you tailored nutrition and fitness advice.</p>
                    <AiAssistent />
            </section>

            <!-- 🎯 Final Call to Action -->
            <section class="py-16 bg-gradient-to-r from-green-500 to-teal-600 text-white text-center">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl font-bold">Ready to Take Control of Your Health?</h2>
                    <p class="text-lg opacity-90 mt-3">Start tracking today and let AI help you reach your goals! 🚀</p>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
