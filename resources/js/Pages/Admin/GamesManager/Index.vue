<template>
    <AuthenticatedLayout>
        <div class="px-24 py-6">
            <div class="w-full  mx-auto sm:px-6 lg:px-8">
                    <div class="container mx-auto px-6 py-12">
                        <h1 class="text-3xl font-bold text-center mb-10 text-gray-700">🎮 Games Manager</h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Quizzes Section -->
                            <div
                                class="bg-purple-100 p-8 rounded-2xl shadow-lg flex flex-col items-center text-center transition transform hover:scale-105">
                                <img :src="imagePath('/quizzes/hard-quiz.png')" alt="Quizzes" class="w-24 h-24 mb-4">
                                <h2 class="text-2xl font-semibold text-purple-700">🧠 Quizzes</h2>
                                <p class="text-gray-700 mt-2">
                                    Create engaging quizzes for your users to test their knowledge and have fun.
                                </p>
                                <inertia-link :href="route('admin.user_quiz.index')"
                                    class=" w-[20em] mt-6 bg-purple-600 no-underline text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-purple-700 transition duration-300">
                                    📝 Create Quiz
                                </inertia-link>
                            </div>

                            <!-- Hangman Section -->
                            <div
                                class="bg-orange-100 p-8 rounded-2xl shadow-lg flex flex-col items-center text-center transition transform hover:scale-105">
                                <img :src="imagePath('/user_dashboard/joystick.png')" alt="Hangman" class="w-24 h-24 mb-4">
                                <h2 class="text-2xl font-semibold text-orange-700">🔠 Hangman</h2>
                                <p class="text-gray-700 mt-2">
                                    Classic Hangman game, improving vocabulary and logic skills.
                                </p>
                                <inertia-link :href="route('admin.hangman_manager.index')"
                                    class="w-[20em] mt-6 bg-orange-600 no-underline text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-orange-700 transition duration-300">
                                    🎯 Create Hangman
                                </inertia-link>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    }
}</script>