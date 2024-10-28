<template>
    <AuthenticatedLayout>
        <div class="w-9/12 p-12 mx-auto m-24 bg-white shadow-md rounded-lg" style="max-height: 800px; overflow-y: auto;">
            <div class="mb-4">
                <h1 class="text-2xl font-bold">QUIZ</h1>
                <div class="text-lg font-semibold mt-2">Quiz Title: {{ quiz.title }}</div>
                <div class="mt-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded">Delete Quiz</button>
                </div>
            </div>

            <!-- Questions Section -->
            <div v-for="(question, qIndex) in quiz.questions" :key="qIndex" class="mb-6 p-4 border rounded shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold">Question {{ qIndex + 1 }}: {{ question.question }}</h2>
                        <p class="text-gray-600">Score: {{ question.score }}</p>
                    </div>
                    <div class="space-x-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded">Delete Question</button>
                        <button @click="toggleAnswers(qIndex)" class="bg-green-500 text-white px-3 py-1 rounded">Show
                            Answers</button>
                    </div>
                </div>

                <!-- Answers Section -->
                <ul v-if="showAnswers[qIndex]" class="mt-4 space-y-2">
                    <li v-for="(answer, aIndex) in question.answers" :key="aIndex"
                        class="flex justify-between items-center p-2 bg-gray-100 rounded">
                        <div class="flex items-center">
                            <span class="mr-2">{{ answer.answer }}</span>
                            <span :class="{ 'bg-green-400': answer.is_correct, 'bg-red-400': !answer.is_correct }"
                                class="w-4 h-4 rounded-full"></span>
                        </div>
                        <div class="space-x-2">
                            <button class="bg-blue-500 text-white px-2 py-1 rounded">Update</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded">Delete Answer</button>
                        </div>
                    </li>
                    <div v-if="question.answers.length === 0"
                        class="text-yellow-700 bg-yellow-100 p-4 rounded-lg text-center">
                        No answers available for this question.
                    </div>
                </ul>
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
        quiz: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            showAnswers: []
        };
    },
    methods: {
        toggleAnswers(index) {
            this.showAnswers[index] = !this.showAnswers[index];
        }
    }
};
</script>
