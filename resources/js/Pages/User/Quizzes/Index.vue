<template>
    <AuthenticatedLayout>
        <div class="text-indigo-600 font-bold text-xl underline decoration-wavy decoration-pink-500 text-center">
            Hello, {{ $page.props.user.name }}
        </div>

        <!-- Quiz Sections Container -->
        <div class="container mx-auto mt-6 space-y-12 px-4">
            <!-- Easy Quizzes -->
            <div v-if="quizzes['easy'].length" class="space-y-6">
                <h2 class="text-green-600 font-semibold text-2xl text-center">Easy</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['easy']" :key="quiz.quizData.id"
                        class="flex-1 max-w-md bg-gradient-to-r from-green-300 to-green-500 p-6 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col items-center text-center">
                        <img :src="quiz.image" alt="Quiz Image" class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-2xl font-extrabold text-white mb-2">{{ quiz.title }}</h3>
                       
                        <span
                            class="bg-white text-green-600 text-xs font-semibold py-1 px-2 rounded-full shadow-md mb-2">
                            Easy
                        </span>
                        <p class="text-md text-white mb-4">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="bg-white text-green-700 font-semibold py-2 px-6 rounded-full shadow-md hover:bg-green-200 transition">
                            Let's Play
                        </inertia-link>
                        <div v-if="quiz.is_locked"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                            <span class="text-white font-bold text-3xl transform rotate-12">LOCKED</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medium Quizzes -->
            <div v-if="quizzes['medium'].length" class="space-y-6">
                <h2 class="text-yellow-600 font-semibold text-2xl text-center">Medium</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['medium']" :key="quiz.quizData.id"
                        class="flex-1 max-w-md bg-gradient-to-r from-yellow-300 to-yellow-500 p-6 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col items-center text-center">
                        <img :src="quiz.image" alt="Quiz Image" class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-2xl font-extrabold text-white mb-2">{{ quiz.quizData.title }}</h3>
                       
                        <span
                            class="bg-white text-yellow-600 text-xs font-semibold py-1 px-2 rounded-full shadow-md mb-2">
                            Medium
                        </span>
                        <p class="text-md text-white mb-4">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="bg-white text-yellow-700 font-semibold py-2 px-6 rounded-full shadow-md hover:bg-yellow-200 transition">
                            Let's Play
                        </inertia-link>
                        <div v-if="quiz.is_locked"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                            <span class="text-white font-bold text-3xl transform rotate-12">LOCKED</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hard Quizzes -->
            <div v-if="quizzes['hard'].length" class="space-y-6">
                <h2 class="text-red-600 font-semibold text-2xl text-center">Hard</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['hard']" :key="quiz.quizData.id"
                        class="flex-1 max-w-md bg-gradient-to-r from-red-300 to-red-500 p-6 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col items-center text-center">
                        <img :src="quiz.image" alt="Quiz Image" class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-2xl font-extrabold text-white mb-2">{{ quiz.quizData.title }}</h3>

                        <span class="bg-white text-red-600 text-xs font-semibold py-1 px-2 rounded-full shadow-md mb-2">
                            Hard
                        </span>
                        <p class="text-md text-white mb-4">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="bg-white text-red-700 font-semibold py-2 px-6 rounded-full shadow-md hover:bg-red-200 transition">
                            Let's Play
                        </inertia-link>
                        <div v-if="quiz.is_locked"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                            <span class="text-white font-bold text-3xl transform rotate-12">LOCKED</span>
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
    },
    props: {
        quizzes: {
            type: Array,
            required: true
        }
    },
}
</script>
