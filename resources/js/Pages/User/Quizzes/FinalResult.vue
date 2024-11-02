<template>

    <div class="flex flex-col justify-center items-center w-3/5 bg-gray-200">
        <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow-lg">
            <template v-if="isFeedback" class="mt-6">
                <h2 class="text-xl font-bold mb-2">Quiz Remarks: Just give us feedback</h2>
                <textarea v-model="feedback" placeholder="Your feedback here..." rows="4"
                    class="w-full p-3 border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                <button @click="submitFeedback"
                    class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit Feedback
                </button>
            </template>

            <h1 class="text-2xl font-bold mb-4">Quiz-ul: {{ quiz.title }}</h1>
            <p class="text-gray-700 mb-6">Descrierea: {{ quiz.description }}</p>

            <div v-for="(question, questionIndex) in quiz.questions" :key="question.id"
                class="mb-6 p-4 bg-gray-100 rounded-lg">
                <div class="font-bold text-lg mb-2">Question {{ questionIndex + 1 }}: {{ question.question }}</div>
                <div class="text-sm mb-4">Punctaj întrebare: {{ question.score }}</div>

                <div v-for="(answer, index) in question.answers" :key="answer.id" class="flex items-center mb-2">
                    <div class="w-full text-left p-4 rounded-xl transition-all duration-300 flex items-center relative"
                        :class="customAnswerClass(question, answer)">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-white"
                            :class="indexLetterClass(question, answer)">
                            {{ String.fromCharCode(65 + index) }}
                        </span>
                        {{ answer.answer }}
                        <span v-if="isCorrectAnswer(question, answer)" class="mr-2 ml-8 text-white-600">✓ +{{
                            question.score }}</span>
                        <span v-else-if="isUserAnswer(question, answer)" class="mr-2 ml-8 text-white-500">✗</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        responses: {
            type: Array,
            required: true
        },
        quiz: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            feedback: '',
            isFeedback: true
        }
    },
    methods: {
        isCorrectAnswer(question, answer) {
            const userResponse = this.responses.find(resp => resp.questionId === question.id);
            return userResponse && userResponse.answerId === answer.id && answer.is_correct;
        },

        isUserAnswer(question, answer) {
            const userResponse = this.responses.find(resp => resp.questionId === question.id);
            return userResponse && userResponse.answerId === answer.id;
        },

        customAnswerClass(question, answer) {
            if (this.isCorrectAnswer(question, answer)) {
                return 'bg-green-600 text-white';
            } else if (this.isUserAnswer(question, answer)) {
                return 'bg-red-500 text-white';
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
        submitFeedback() {
            this.isFeedback = false;
            this.$inertia.post(route('user.quiz.remark.store', { quizId: this.quiz.id }), {
                feedback: this.feedback
            },
                {
                    preserveScroll: true,
                    preserveState: true
                });
        }
    }
};
</script>

<style scoped>
.font-bold {
    font-weight: bold;
}

.text-green-600 {
    color: #16a34a;
}

.text-red-500 {
    color: #dc2626;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.bg-white {
    background-color: #ffffff;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
