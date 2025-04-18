<!-- FinalScreen.vue -->
<template>
    <div class="flex flex-col justify-center items-center p-4">
        <div
            class="bg-white p-8 rounded-3xl shadow-2xl text-center sm:w-[600px] md:w-[800px] lg:w-[800px] relative overflow-hidden"
        >
            <div
                class="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-t-3xl"
            ></div>

            <h2 class="text-3xl font-extrabold text-indigo-700 mb-4">
                🎉 Quiz Completat!
            </h2>
            <p class="text-lg text-gray-700 mb-3">Scorul tău final este:</p>
            <p class="text-5xl font-bold text-indigo-600 mb-4">
                {{ score }} <span class="text-xl text-gray-500">puncte</span>
            </p>

            <p class="text-md text-gray-700 mb-6">
                Ai răspuns corect la
                <strong class="text-green-600">{{ percentageCorrect }}%</strong>
                din întrebări.
            </p>
            <div
                v-if="$page.props.nr_attempts <= 2"
                class="flex flex-col sm:flex-row gap-4 justify-center"
            >
                <button
                    @click="retryQuiz"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white py-3 px-6 rounded-xl font-semibold shadow transition"
                >
                    🔁 Retry Quiz
                </button>
                <button
                    @click="lockQuiz"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-xl font-semibold shadow transition"
                >
                    🔒 Lock Quiz
                </button>
            </div>
            <div v-else="$page.props.nr_attempts >= 3">
                <button
                    @click="showResults"
                    class="bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-xl font-semibold shadow transition"
                >
                    📊 View Results
                </button>
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
        quizId: String,
        nr_attempts: Number,
    },
    data() {
        return {
            percentage: 0,
        };
    },
    computed: {
        percentageCorrect() {
            this.percentage = Math.round(
                (this.correctAnswers / this.totalQuestions) * 100
            );
            return Math.round(
                (this.correctAnswers / this.totalQuestions) * 100
            );
        },
    },
    emits: ["retry-quiz", "lock-quiz"],
    methods: {
        retryQuiz() {
            // Nu mai salva din nou, doar pornește o rundă nouă
            this.$emit("retry-quiz");
        },
        lockQuiz() {
            this.$inertia.post(
                "/user/user_quiz/lock",
                {
                    quiz_id: this.quizId,
                    user_id: this.$page.props.user.id,
                    score: this.score,
                    responses: this.responses,
                    percentage: this.percentage,
                },
                {
                    onFinish: () => {
                        this.$emit("lock-quiz");
                    },
                }
            );
        },

        showResults() {
            this.$emit("lock-quiz");
        },
    },
    mounted() {
        let updatedAttempts = 0;
        this.$inertia.post(
            "/user/user_quiz/retry",
            {
                quiz_id: this.quizId,
                user_id: this.$page.props.user.id,
                score: this.score,
                responses: this.responses,
                percentage: this.percentage,
            },
            {
                onFinish: () => {
                     updatedAttempts = this.nr_attempts + 1;
                },
            }
        );
        
        if (updatedAttempts >= 3) {
                        this.lockQuiz();
                    }
    },
};
</script>
