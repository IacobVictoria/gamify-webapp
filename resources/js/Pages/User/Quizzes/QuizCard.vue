<template>

    <div class="w-[450px] bg-white rounded-3xl shadow-lg p-6 relative overflow-hidden">

        <div class="absolute top-0 left-0 right-0 h-24"
            style="background: linear-gradient(90deg, #4F46E5 0%, #A78BFA 100%);">
        </div>

        <!-- Timer și Score -->
        <div class="relative z-10 mb-8">
            <div class="flex justify-between items-center mb-2">
                <div class="w-full">
                    <div class="bg-white rounded-full h-2 shadow-inner overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-1000"
                            :style="{ width: timerWidth + '%' }">
                        </div>
                    </div>
                </div>
                <div class="ml-4 text-sm font-medium">
                    Score {{ score }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 relative z-10 mt-16">
            <h2 class="text-center text-lg font-medium">
                {{ question.question }}
            </h2>
        </div>

        <div class="flex flex-col gap-2 relative z-10">
            <button v-for="(answer, index) in question.answers" :key="index" @click="selectAnswer(answer)"
                :disabled="selectedAnswer"
                class="w-full text-left p-4 rounded-xl transition-all duration-300 flex items-center relative"
                :class="customAnswerClass(answer)">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-white"
                    :class="indexLetterClass(answer)">
                    {{ String.fromCharCode(65 + index) }}
                </span>
                {{ answer.answer }}

                <!-- Score Animation -->
                <div v-if="selectedAnswer === answer.id && answer.is_correct"
                    class="absolute -top-10 right-2 score-popup">
                    +{{ question.score }}
                </div>
            </button>
        </div>

        <!-- Question Counter -->
        <div class="text-center mt-6 text-sm text-gray-500 relative z-10">
            {{ questionIndex + 1 }}/{{ totalQuestions }}
        </div>
        <button v-if="questionIndex + 1 === totalQuestions" @click="finishQuiz"
            class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 transition-colors">
            Finish Quiz
        </button>
    </div>
</template>

<script>
export default {
    props: {
        question: Object,
        questionIndex: Number,
        totalQuestions: Number,
        initialScore: Number
    },
    data() {
        return {
            selectedAnswer: null,
            score: this.initialScore,
            timeRemaining: 30,
            interval: null,
            isQuizFinished: false,
            correctAnswersCount: 0,
            responses: []
        };
    },
    computed: {
        timerWidth() {
            return (this.timeRemaining / 30) * 100;
        }
    },
    methods: {
        finishQuiz() {
            this.isQuizFinished = true;
            console.log('f')
            clearInterval(this.interval);
            
            this.$emit('quiz-completed', this.score, this.correctAnswersCount,this.responses);
        },
        customAnswerClass(answer) {
            if (this.selectedAnswer === answer.id) {
                return answer.is_correct ? 'bg-green-600 text-white' : 'bg-red-500 text-white';
            }
            return 'bg-gray-50 hover:bg-gray-100';
        },

        indexLetterClass(answer) {
            if (this.selectedAnswer === answer.id) {
                return answer.is_correct ? 'text-green-600' : 'text-red-500';
            }
            return 'text-gray-700';
        },

        startTimer() {
            if (this.isQuizFinished) return;
            clearInterval(this.interval);
            this.timeRemaining = 30;
            this.interval = setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                } else {
                    if (this.questionIndex + 1 === this.totalQuestions) {
                        this.finishQuiz();

                    } else {
                        this.nextQuestion();
                    }
                }
            }, 1000);
        },

        selectAnswer(answer) {
            if (this.selectedAnswer) return;
            this.selectedAnswer = answer.id;
            clearInterval(this.interval);
            // Track the question and selected answer
            this.responses.push({
                questionId: this.question.id,
                answerId: answer.id
            });
            if (answer.is_correct) {
                this.score += this.question.score;
                this.$emit("score-updated", this.score);
                this.correctAnswersCount++;
            }

            if (this.questionIndex + 1 === this.totalQuestions) {
                this.finishQuiz();

            }
            else {
                setTimeout(() => {
                    this.nextQuestion();
                }, 1500);
            }

        },

        nextQuestion() {
            this.selectedAnswer = null;
            this.$emit("next-question");
            this.startTimer();
        }
    },
    mounted() {
        this.startTimer();
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
                    this.$emit('quiz-completed', this.score, this.correctAnswersCount,this.responses);
                }
            }
        }
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
        transform: translateY(10px);
    }

    50% {
        opacity: 1;
        transform: translateY(-5px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

button:disabled {
    cursor: default;
}

/* Stilizare pentru letterbox */
button span {
    transition: all 0.3s ease;
}

/* Shadow pentru card */
.quiz-card {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>