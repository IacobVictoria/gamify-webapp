<template>
    <div
        class="w-[450px] bg-white rounded-3xl shadow-xl overflow-hidden relative border border-gray-200"
    >
        <!-- Header + Score -->
        <div
            class="bg-gradient-to-r from-indigo-600 to-purple-400 h-28 px-6 py-4 flex items-center justify-between"
        >
            <div>
                <p class="text-white text-sm">
                    Round {{ $page.props.nr_attempts + 1 }}
                </p>
                <h2 class="text-white text-xl font-semibold">
                    🧠 Quiz Challenge
                </h2>
            </div>
            <div
                class="bg-white rounded-full px-4 py-2 text-indigo-700 font-bold shadow"
            >
                Score: {{ score }}
            </div>
        </div>

        <!-- Întrebare -->
        <div class="p-6">
            <div
                class="bg-gray-50 p-4 rounded-xl shadow-inner text-center mb-6"
            >
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ question.question }}
                </h2>
            </div>

            <!-- Răspunsuri -->
            <div class="flex flex-col gap-3">
                <button
                    v-for="(answer, index) in question.answers"
                    :key="index"
                    @click="selectAnswer(answer)"
                    :disabled="selectedAnswer"
                    class="w-full flex items-center gap-3 p-4 rounded-xl border transition-all duration-300"
                    :class="customAnswerClass(answer)"
                >
                    <span
                        class="w-8 h-8 rounded-full flex items-center justify-center text-black font-bold"
                        :class="indexLetterClass(answer)"
                    >
                        {{ String.fromCharCode(65 + index) }}
                    </span>
                    <span class="flex-1">{{ answer.answer }}</span>

                    <!-- Popup +Score -->
                    <div
                        v-if="selectedAnswer === answer.id && answer.is_correct"
                        class="text-green-600 font-bold text-sm animate-bounce"
                    >
                        +{{ question.score }}
                    </div>
                </button>
            </div>

            <!-- Progress -->
            <div class="text-center text-gray-500 text-sm mt-6">
                Question {{ questionIndex + 1 }} of {{ totalQuestions }}
            </div>

            <!-- Finalizare -->
            <div class="flex justify-center mt-6">
                <button
                    v-if="questionIndex + 1 === totalQuestions"
                    @click="finishQuiz"
                    class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-indigo-700 transition"
                >
                    Finish Quiz
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        question: Object,
        questionIndex: Number,
        totalQuestions: Number,
        initialScore: Number,
        nr_attempts: Number,
    },
    data() {
        return {
            selectedAnswer: null,
            score: this.initialScore,
            timeRemaining: 30,
            interval: null,
            isQuizFinished: false,
            correctAnswersCount: 0,
            responses: [],
        };
    },

    methods: {
        finishQuiz() {
            this.isQuizFinished = true;
            clearInterval(this.interval);

            this.$emit(
                "quiz-completed",
                this.score,
                this.correctAnswersCount,
                this.responses
            );
        },
        customAnswerClass(answer) {
            if (this.selectedAnswer === answer.id) {
                return answer.is_correct
                    ? "bg-green-500 text-white border-green-500"
                    : "bg-red-500 text-white border-red-500";
            }
            return "bg-white hover:bg-indigo-50 border-gray-300";
        },
        indexLetterClass(answer) {
            if (this.selectedAnswer === answer.id) {
                return answer.is_correct ? "bg-green-200" : "bg-red-200";
            }
            return "bg-gray-200";
        },
        selectAnswer(answer) {
            if (this.selectedAnswer) return;
            this.selectedAnswer = answer.id;
            clearInterval(this.interval);
            // track the question and selected answer
            this.responses.push({
                questionId: this.question.id,
                answerId: answer.id,
            });
            if (answer.is_correct) {
                this.score += this.question.score;
                this.$emit("score-updated", this.score);
                this.correctAnswersCount++;
            }

            if (this.questionIndex + 1 === this.totalQuestions) {
                this.finishQuiz();
            } else {
                setTimeout(() => {
                    this.nextQuestion();
                }, 1500);
            }
        },

        nextQuestion() {
            this.selectedAnswer = null;
            this.$emit("next-question");
        },
    },

    beforeDestroy() {
        clearInterval(this.interval);
    },
    watch: {
        question: {
            immediate: true,
            handler(newVal) {
                if (newVal === this.totalQuestions && this.selectedAnswerId) {
                    this.isQuizFinished = true;
                    this.$emit(
                        "quiz-completed",
                        this.score,
                        this.correctAnswersCount,
                        this.responses
                    );
                }
            },
        },
    },
};
</script>

<style scoped>
.score-popup {
    animation: scorePopup 0.5s ease-out forwards;
    color: #2563eb;
    font-weight: bold;
    font-size: 1.2rem;
}

@keyframes scorePopup {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }

    50% {
        opacity: 1;
        transform: translateY(-20px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

button:disabled {
    cursor: default;
}

button span {
    transition: all 0.3s ease;
}

.quiz-card {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
