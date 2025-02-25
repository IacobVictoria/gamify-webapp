<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                <div class="flex flex-row justify-center">
                    <div>Take notes </div>
                    <img :src="imagePath('/user_dashboard/pin.png')" class="w-12" />
                </div>
            </h2>
        </template>

        <div class="flex flex-col items-center  py-8 min-h-screen">

            <div class="w-full max-w-3xl p-6 bg-black/20 rounded-lg shadow-lg border border-black/30">
                <h1 class="text-3xl font-bold mb-4 text-black text-center">{{ quiz.title }}</h1>
                <p class="text-gray-200 text-center mb-6 italic">{{ quiz.description }}</p>

                <!-- Scor final și detalii -->
                <div class="bg-black/30 p-4 rounded-lg shadow-md mb-6 border border-black/40">
                    <h2 class="text-lg font-semibold text-black text-center">Your Results 📊</h2>
                    <p class="mb-2 text-black text-black">Final Score: <strong class="text-yellow-300">{{
                            quizResult.total_score
                            }}</strong></p>
                    <p class="mb-2 text-black text-center">Attempt #{{ quizResult.attempt_number }}</p>
                    <p class="mb-2 text-black text-center">📅 Completed on: {{ formatDate(quizResult.date) }}</p>
                </div>

                <!-- Întrebările quiz-ului -->
                <div v-for="(question, questionIndex) in quiz.questions" :key="question.id"
                    class="mb-6 p-4 bg-black/30 rounded-lg shadow-lg backdrop-blur-lg border border-black/30">
                    <div class="font-bold text-lg mb-2 text-black">❓ Question {{ questionIndex + 1 }}: {{
                        question.question }}
                    </div>
                    <div class="text-sm mb-4 text-black-200">🎯 Points: {{ question.score }}</div>

                    <!-- Variantele de răspuns -->
                    <div v-for="(answer, index) in question.answers" :key="answer.id"
                        class="flex items-center mb-2 cursor-pointer">
                        <div class="w-full text-left p-4 rounded-xl transition-all duration-300 flex items-center relative
                            border-2 shadow-sm" :class="customAnswerClass(question, answer)">
                            <span
                                class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-black text-lg font-bold"
                                :class="indexLetterClass(question, answer)">
                                {{ String.fromCharCode(65 + index) }}
                            </span>
                            {{ answer.answer }}
                            <span v-if="isCorrectAnswer(question, answer)"
                                class="ml-8 text-green-300 font-bold text-lg">✅</span>
                            <span v-else-if="isUserAnswer(question, answer)"
                                class="ml-8 text-red-400 font-bold text-lg">❌</span>
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
        quiz: Object,
        responses: Array,
        quizResult: Object
    },
    methods: {
        isCorrectAnswer(question, answer) {
            const userResponse = this.responses.find(resp => resp.question_id === question.id);
            return userResponse && userResponse.answer_id === answer.id && answer.is_correct;
        },
        isUserAnswer(question, answer) {
            const userResponse = this.responses.find(resp => resp.question_id === question.id);
            return userResponse && userResponse.answer_id === answer.id;
        },
        customAnswerClass(question, answer) {
            if (this.isCorrectAnswer(question, answer)) {
                return 'bg-green-500 text-black';
            } else if (this.isUserAnswer(question, answer)) {
                return 'bg-red-500 text-black';
            }
            return 'bg-gray-50 hover:bg-gray-100';
        },
        indexLetterClass(question, answer) {
            if (this.isCorrectAnswer(question, answer)) {
                return 'text-green-600';
            } else if (this.isUserAnswer(question, answer)) {
                return 'text-red-500';
            }
            return 'text-gray-700';
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        }
    }
};
</script>


<style scoped>
/* Efect de blur pentru elementele transparente */
.backdrop-blur-md {
    backdrop-filter: blur(10px);
}

.backdrop-blur-lg {
    backdrop-filter: blur(15px);
}

/* Bordură albă semi-transparentă pentru un efect elegant */
.border-black\/30 {
    border-color: rgba(255, 255, 255, 0.3);
}

.border-black\/40 {
    border-color: rgba(255, 255, 255, 0.4);
}

/* Efecte de hover pe cardurile de răspuns */
.bg-black\/30 {
    background: rgba(255, 255, 255, 0.2);
}

.bg-black\/20 {
    background: rgba(255, 255, 255, 0.15);
}

/* Stilizare răspunsuri */
.correct-answer {
    background: rgba(34, 197, 94, 0.2);
    border-color: rgba(34, 197, 94, 0.6);
}

.wrong-answer {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.6);
}

.unselected-answer {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transition: background 0.3s ease, border 0.3s ease;
}

.unselected-answer:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.4);
}

/* Culoarea literelor de răspuns */
.text-green-300 {
    color: #34d399;
}

.text-red-400 {
    color: #f87171;
}
</style>
