<template>
    <AuthenticatedLayout>
        <div
            class="flex flex-col justify-center items-center min-h-screen bg-gray-200 space-y-4"
        >
            <StartScreen
                v-if="
                    !quizStarted &&
                    !quizEnded &&
                    !isQuizLocked &&
                    $page.props.nr_attempts < 3
                "
                :quiz="quiz"
                @start-quiz="startQuiz"
                :nr_attempts="nr_attempts"
            />

            <div v-if="quiz.questions.length > 0">
                <QuizCard
                    v-if="quizStarted"
                    :question="currentQuestion"
                    :questionIndex="currentQuestionIndex"
                    :totalQuestions="quiz.questions.length"
                    :initialScore="score"
                    @next-question="nextQuestion"
                    @quit-quiz="quitQuiz"
                    @score-updated="updateScore"
                    @quiz-completed="quizCompleted"
                    :nr_attempts="nr_attempts"
                />
            </div>

            <FinalScreen
                v-if="quizEnded === true"
                :score="score"
                :totalQuestions="quiz.questions.length"
                :correct-answers="correctAnswersCount"
                @lock-quiz="lockQuiz"
                :quiz-id="quiz.id"
                :responses="responses"
                @retry-quiz="retryQuiz"
                :nr-attempts="nr_attempts"
            />

            <!-- Mesaj dacă quiz-ul este blocat -->
            <div
                v-if="isQuizLocked && !quizStarted && !quizEnded"
                class="bg-red-100 text-red-800 p-4 rounded-md shadow-md w-full max-w-xl text-center"
            >
                Acest quiz a fost închis! Vezi permanent în
                <b>Dashboard-ul utilizatorului</b> ce cunoștințe poți
                îmbunătăți. 📊
            </div>

            <!-- componenta de vizualizare rezultate finale -->
            <FinalResult
                v-if="isQuizLocked === true"
                :responses="responses"
                :quiz="quiz"
            ></FinalResult>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import QuizCard from "./QuizCard.vue";
import StartScreen from "./StartScreen.vue";
import FinalScreen from "./FinalScreen.vue";
import FinalResult from "./FinalResult.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        StartScreen,
        QuizCard,
        FinalScreen,
        FinalResult,
        AuthenticatedLayout,
    },
    props: {
        quiz: Object,
        nr_attempts: Number,
        is_locked: Boolean,
    },
    data() {
        return {
            quizStarted: false,
            currentQuestionIndex: 0,
            score: 0,
            quizEnded: false,
            correctAnswersCount: 0,
            responses: [],
            isQuizLocked: this.is_locked,
        };
    },
    mounted() {
        const savedIndex = localStorage.getItem("quiz_question_index");
        const savedScore = localStorage.getItem("quiz_score");

        if (savedIndex !== null) {
            this.currentQuestionIndex = parseInt(savedIndex);
            this.quizStarted = true;
        }

        if (savedScore !== null) {
            this.score = parseInt(savedScore);
        }
    },
    computed: {
        currentQuestion() {
            return this.quiz.questions[this.currentQuestionIndex];
        },
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
                localStorage.setItem(
                    "quiz_question_index",
                    this.currentQuestionIndex
                );
                localStorage.setItem("quiz_score", this.score);
            } else {
                // Finalizare quiz
                this.quizStarted = true; // Rămâne activ pentru a arăta FinalScreen
                localStorage.removeItem("quiz_question_index");
                localStorage.removeItem("quiz_score");
            }
        },

        quitQuiz() {
            this.quizStarted = false;
            this.currentQuestionIndex = 0;
            this.score = 0;
        },

        updateScore(newScore) {
            this.score = newScore;
            localStorage.setItem("quiz_score", this.score);
        },

        quizCompleted(score, correctAnswersCount, responses) {
            this.score = score;
            this.correctAnswersCount = correctAnswersCount;
            this.quizStarted = false;
            this.responses = responses;
            this.quizEnded = true;
            localStorage.removeItem("quiz_question_index");
            localStorage.removeItem("quiz_score");
        },

        retryQuiz() {
            this.quizStarted = true;
            this.quizEnded = false;
            this.currentQuestionIndex = 0;
            this.score = 0;
            localStorage.removeItem('quiz_question_index');
            localStorage.removeItem('quiz_score');
        },

        lockQuiz() {
            this.quizStarted = false;
            this.quizEnded = false;
            this.isQuizLocked = true;
            localStorage.removeItem('quiz_question_index');
            localStorage.removeItem('quiz_score');
        },
    },
};
</script>
