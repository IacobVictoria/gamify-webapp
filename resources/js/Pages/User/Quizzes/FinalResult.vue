<template>
    <div class="min-h-screen py-12 px-4 flex justify-center items-start">
        <div
            class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-6 bg-white rounded-2xl overflow-hidden"
        >
            <!-- Feedback Panel -->
            <div
                class="col-span-1 bg-yellow-100 p-6 border-r border-yellow-300 h-full flex flex-col justify-center"
            >
                <h2 class="text-2xl font-bold text-yellow-800 mb-2">
                    Apreciem feedback-ul tău! 📝
                </h2>
                <p class="text-sm text-yellow-700 mb-4">
                    Spune-ne cum ți s-a părut quizul sau ce ai îmbunătăți:
                </p>

                <textarea
                    v-model="feedback"
                    rows="6"
                    class="w-full p-3 rounded-lg border border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-600 resize-none"
                ></textarea>
                <div class="flex justify-end mt-4">
                    <button
                        @click="submitFeedback"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg transition"
                    >
                        Trimite ✨
                    </button>
                </div>
            </div>

            <!-- Quiz Section -->
            <div
                class="col-span-2 px-6 py-6 overflow-y-auto h-[80vh] space-y-6"
            >
                <div>
                    <h1 class="text-3xl font-bold text-blue-800 mb-2">
                        Quiz: {{ quiz.title }}
                    </h1>
                    <p class="text-gray-600 italic">{{ quiz.description }}</p>
                </div>

                <!-- Questions -->
                <div
                    v-for="(question, questionIndex) in quiz.questions"
                    :key="question.id"
                    class="bg-blue-50 rounded-xl p-6 shadow-md"
                >
                    <div class="font-semibold text-xl text-blue-900 mb-3">
                        Întrebare {{ questionIndex + 1 }}:
                        {{ question.question }}
                    </div>
                    <div class="text-sm text-blue-700 mb-5">
                        Scor: {{ question.score }}
                    </div>

                    <div
                        v-for="(answer, index) in question.answers"
                        :key="answer.id"
                        class="mb-3"
                    >
                        <div
                            class="flex items-center p-4 rounded-lg border transition-all duration-300"
                            :class="customAnswerClass(question, answer)"
                        >
                            <span
                                class="w-9 h-9 flex items-center justify-center rounded-full font-bold mr-4 border"
                                :class="indexLetterClass(question, answer)"
                            >
                                {{ String.fromCharCode(65 + index) }}
                            </span>
                            <span class="flex-1 text-base">{{
                                answer.answer
                            }}</span>

                            <span
                                v-if="isCorrectAnswer(question, answer)"
                                class="ml-auto text-green-800 font-semibold"
                                >+{{ question.score }}</span
                            >
                            <span
                                v-else-if="isUserAnswer(question, answer)"
                                class="ml-auto text-red-600 font-semibold"
                                >✗</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        responses: Array,
        quiz: Object,
    },
    data() {
        return {
            feedback: "",
        };
    },
    methods: {
        isCorrectAnswer(question, answer) {
            const userResponse = this.responses.find(
                (resp) => resp.questionId === question.id
            );
            return (
                userResponse &&
                userResponse.answerId === answer.id &&
                answer.is_correct
            );
        },
        isUserAnswer(question, answer) {
            const userResponse = this.responses.find(
                (resp) => resp.questionId === question.id
            );
            return userResponse && userResponse.answerId === answer.id;
        },
        customAnswerClass(question, answer) {
            if (this.isCorrectAnswer(question, answer))
                return "bg-green-100 text-green-800 border-green-400";
            if (this.isUserAnswer(question, answer))
                return "bg-red-100 text-red-700 border-red-300";
            return "bg-white text-gray-700 border-gray-300";
        },
        indexLetterClass(question, answer) {
            if (this.isCorrectAnswer(question, answer))
                return "bg-green-500 text-white border-green-500";
            if (this.isUserAnswer(question, answer))
                return "bg-red-500 text-white border-red-500";
            return "bg-gray-200 text-gray-800 border-gray-300";
        },
        submitFeedback() {
            this.isFeedback = false;
            this.$inertia.post(
                route("user.quiz.remark.store", { quizId: this.quiz.id }),
                {
                    feedback: this.feedback,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },
    },
};
</script>

<style scoped>
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-thumb {
    background-color: rgba(107, 114, 128, 0.6);
    border-radius: 4px;
}
</style>
