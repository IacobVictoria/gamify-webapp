<template>
    <div class="flex flex-col justify-center items-center min-h-screen bg-gray-200 space-y-4">
        <inertia-link :href="route('user.quizzes.index')" class="text-blue-500 underline">Back to search for
            quizzes</inertia-link>

        <StartScreen v-if="!quizStarted && !quizEnded" :quiz="quiz" @start-quiz="startQuiz" />

        <QuizCard v-if="quizStarted" :question="currentQuestion" :questionIndex="currentQuestionIndex"
            :totalQuestions="quiz.questions.length" :initialScore="score" @next-question="nextQuestion"
            @quit-quiz="quitQuiz" @score-updated="updateScore" @quiz-completed="quizCompleted" />

        <FinalScreen v-if="quizEnded" :score="score" :totalQuestions="quiz.questions.length"
            :correct-answers="correctAnswersCount" @quit-quiz="quitQuiz" :quiz-id="quiz.id" :responses="responses" />
    </div>
</template>

<script>
import QuizCard from './QuizCard.vue';
import StartScreen from './StartScreen.vue';
import FinalScreen from './FinalScreen.vue';

export default {
    components: {
        StartScreen,
        QuizCard,
        FinalScreen
    },
    props: {
        quiz: Object
    },
    data() {
        return {
            quizStarted: false,
            currentQuestionIndex: 0,
            score: 0,
            quizEnded: false,
            correctAnswersCount: 0,
            responses: []
        };
    },
    computed: {
        currentQuestion() {
            return this.quiz.questions[this.currentQuestionIndex];
        }
    },
    methods: {
        startQuiz() {
            this.quizStarted = true;
            this.currentQuestionIndex = 0;
            this.score = 0;
        },

        nextQuestion() {
            if (this.currentQuestionIndex < this.quiz.questions.length - 1) {
                this.currentQuestionIndex += 1;
            } else {
                // Finalizare quiz
                this.quizStarted = true; // Rămâne activ pentru a arăta FinalScreen
            }
        },

        quitQuiz() {
            this.quizStarted = false;
            this.currentQuestionIndex = 0;
            this.score = 0;
        },

        updateScore(newScore) {
            this.score = newScore;
        },

        quizCompleted(score, correctAnswersCount, responses) {
            this.score = score;
            this.correctAnswersCount = correctAnswersCount;
            this.quizStarted = false;
            this.responses = responses;
            this.quizEnded = true;

        },

    }
};
</script>
