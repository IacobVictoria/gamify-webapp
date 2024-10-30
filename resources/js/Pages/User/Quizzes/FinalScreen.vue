<!-- FinalScreen.vue -->
<template>
    <div class="flex flex-col justify-center items-center min-h-screen bg-gray-200">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-md w-full">
            <h2 class="text-2xl font-bold mb-4">Quiz Completat!</h2>
            <p class="text-lg mb-4">
                Scorul tău final este: <strong>{{ score }}</strong> puncte
            </p>
            <p class="text-lg mb-4">
                Ai răspuns corect la <strong>{{ percentageCorrect }}%</strong> din întrebări.
            </p>
            <div class="flex gap-8">
                <button @click="retryQuiz" class="bg-blue-500 text-white py-2 px-4 rounded">Retry Quiz </button>
                <button @click="lockQuiz" class="bg-blue-500 text-white py-2 px-4 rounded">Lock Quiz </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        score: Number,
        totalQuestions: Number,
        correctAnswers: Number,
        responses: Array,
        quizId: String
    },
    computed: {
        percentageCorrect() {
            return Math.round((this.correctAnswers / (this.totalQuestions)) * 100);
        }
    },
    methods: {
        retryQuiz() {
            this.$inertia.post('/user/user_quiz/retry', {
                quiz_id: this.quizId,
                user_id: this.$page.props.user.id,
                score: this.score,
                responses: this.responses,
            });
        },

        lockQuiz() {
            this.$inertia.post('/user/user_quiz/lock', {
                quiz_id: this.quizId,
                user_id: this.$page.props.user.id,
                score: this.score,
                responses: this.responses,
            });
        }
    }

};
</script>
