<template>
    <AuthenticatedLayout>
        <div class="flex flex-col justify-center items-center min-h-screen bg-gray-200 space-y-4">
            <inertia-link :href="route('user.quizzes.index')" class="text-blue-500 underline">Back to search for
                quizzes</inertia-link>

            <StartScreen v-if="!quizStarted && !quizEnded && !isQuizLocked && $page.props.nr_attempts < 3" :quiz="quiz"
                @start-quiz="startQuiz" :nr_attempts="nr_attempts" />

            <div v-if="quiz.questions.length > 0">
                <QuizCard v-if="quizStarted" :question="currentQuestion" :questionIndex="currentQuestionIndex"
                    :totalQuestions="quiz.questions.length" :initialScore="score" @next-question="nextQuestion"
                    @quit-quiz="quitQuiz" @score-updated="updateScore" @quiz-completed="quizCompleted"
                    :nr_attempts="nr_attempts" />
            </div>

            <FinalScreen v-if="quizEnded === true" :score="score" :totalQuestions="quiz.questions.length"
                :correct-answers="correctAnswersCount" @lock-quiz="lockQuiz" :quiz-id="quiz.id" :responses="responses"
                @retry-quiz="retryQuiz" :nr-attempts="nr_attempts" />

            <!-- componenta de vizualizare rezultate finale -->
            <FinalResult v-if="isQuizLocked === true" :responses="responses" :quiz="quiz"></FinalResult>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import QuizCard from './QuizCard.vue';
import StartScreen from './StartScreen.vue';
import FinalScreen from './FinalScreen.vue';
import FinalResult from './FinalResult.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        StartScreen,
        QuizCard,
        FinalScreen,
        FinalResult,
        AuthenticatedLayout
    },
    props: {
        quiz: Object,
        nr_attempts: Number,
    },
    data() {
        return {
            quizStarted: false,
            currentQuestionIndex: 0,
            score: 0,
            quizEnded: false,
            correctAnswersCount: 0,
            responses: [],
            isQuizLocked: false
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

        retryQuiz() {
            this.quizStarted = true;
            this.quizEnded = false;
            this.currentQuestionIndex = 0;
            this.score = 0;
        },

        lockQuiz() {
            this.quizStarted = false;
            this.quizEnded = false;
            this.isQuizLocked = true;
        }

    }
};
</script>
