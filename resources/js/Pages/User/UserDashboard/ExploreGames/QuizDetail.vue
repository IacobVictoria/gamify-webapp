<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold text-center text-indigo-700 mb-4">
                📘 Quiz Recap & Progress
            </h2>
        </template>

        <div class="py-8 px-4 max-w-4xl mx-auto">
            <div
                class="bg-white shadow-md rounded-xl p-6 border border-gray-200"
            >
                <h1
                    class="text-2xl font-semibold text-center text-gray-800 mb-2"
                >
                    {{ quiz.title }}
                </h1>
                <p class="text-center text-gray-500 italic mb-6">
                    {{ quiz.description }}
                </p>

                <div
                    class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6"
                >
                    <p class="text-center text-gray-800">
                        📊 Final Score:
                        <span class="font-bold text-indigo-600">{{
                            quizResult.total_score
                        }}</span>
                    </p>
                    <p class="text-center text-gray-600">
                        Attempt #{{ quizResult.attempt_number }} | 📅
                        {{ formatDate(quizResult.date) }}
                    </p>
                </div>
                <div class="overflow-y-auto max-h-[600px] pr-2">
                    <div
                        v-for="(question, qIndex) in quiz.questions"
                        :key="question.id"
                        class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50"
                    >
                        <p class="font-semibold mb-2 text-gray-700">
                            ❓ Q{{ qIndex + 1 }}: {{ question.question }}
                        </p>
                        <p class="text-sm text-gray-500 mb-4">
                            🎯 Points: {{ question.score }}
                        </p>

                        <div
                            v-for="(answer, aIndex) in question.answers"
                            :key="answer.id"
                            class="flex items-center mb-2"
                        >
                            <div
                                class="w-full flex items-center p-3 rounded-lg border transition"
                                :class="customAnswerClass(question, answer)"
                            >
                                <span
                                    class="w-7 h-7 rounded-md flex items-center justify-center mr-3 text-white font-semibold"
                                    :class="indexLetterClass(question, answer)"
                                >
                                    {{ String.fromCharCode(65 + aIndex) }}
                                </span>
                                <span class="flex-1 text-sm text-gray-800">{{
                                    answer.answer
                                }}</span>
                                <span v-if="isCorrectAnswer(question, answer)"
                                    >✅</span
                                >
                                <span v-else-if="isUserAnswer(question, answer)"
                                    >❌</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        quiz: Object,
        responses: Array,
        quizResult: Object,
    },
    methods: {
        isCorrectAnswer(question, answer) {
            const res = this.responses.find(
                (r) => r.question_id === question.id
            );
            return res?.answer_id === answer.id && answer.is_correct;
        },
        isUserAnswer(question, answer) {
            const res = this.responses.find(
                (r) => r.question_id === question.id
            );
            return res?.answer_id === answer.id;
        },
        customAnswerClass(question, answer) {
            if (this.isCorrectAnswer(question, answer))
                return "bg-green-100 border-green-400";
            if (this.isUserAnswer(question, answer))
                return "bg-red-100 border-red-400";
            return "bg-white hover:bg-gray-100 border-gray-300";
        },
        indexLetterClass(question, answer) {
            if (this.isCorrectAnswer(question, answer)) return "bg-green-500";
            if (this.isUserAnswer(question, answer)) return "bg-red-500";
            return "bg-gray-300";
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
    },
};
</script>
