<template>
    <div v-if="isUpdateOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg mx-auto">
            <h2 class="text-2xl font-bold text-center mb-4 text-blue-500">Edit Question</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Question</label>
                <input v-model="newQuestionText" placeholder="Enter question text" class="w-full p-2 border rounded" />
                <input v-model="newQuestionScore" type="number" placeholder="Enter a score for the question"
                    class="w-full p-2 border rounded mt-2" />
            </div>
            <h3 class="text-gray-700 font-semibold mb-2">Answers</h3>
            <div class="grid grid-cols-3 mb-2">
                <div class="text-gray-600 font-semibold col-span-2 text-center">TEXT</div>
                <div class="text-gray-600 font-semibold text-center">IS CORRECT</div>
            </div>
            <div v-for="(answer, index) in newAnswers" :key="index" class="grid grid-cols-3 items-center mb-2">
                <div class="flex items-center col-span-2">
                    <span class="mr-2">{{ index + 1 }}</span>
                    <input v-model="answer.text" placeholder="Answer text" class="flex-1 p-2 border rounded" />
                    <button v-if="!answer.text" @click="removeAnswer(index)" class="text-red-500 ml-2 hover:text-red-700">
                        &#10006;
                    </button>
                </div>
                <div class="flex items-center justify-center">
                    <input type="radio" :value="index" v-model="correctAnswerIndex" class="ml-2" />
                </div>
            </div>

            <button @click="addAnswer" class="text-blue-500 mt-2 flex items-center">
                <span class="text-xl font-bold mr-1">+</span> Add another answer
            </button>
            <div class="flex justify-end mt-4 space-x-2">
                <button @click="saveQuestion" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                <button @click="closeQuestionModal" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isUpdateOpen: {
            type: Boolean,
            required: true
        },
        quizId: {
            type: [String, Number],
            required: true
        },
        questionData: Object,
        updateRoute: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            newQuestionText: '',
            newQuestionScore: 0,
            newAnswers: [],
            correctAnswerIndex: -1,
        };
    },

    watch: {
        questionData: {
            immediate: true,
            handler(newVal) {
                if (newVal) {
                    this.newQuestionText = newVal.question;
                    this.newQuestionScore = newVal.score || 0;
                    this.newAnswers = newVal.answers ? newVal.answers.map(answer => ({
                        id: answer.id,
                        text: answer.answer,
                        isCorrect: answer.is_correct
                    })) : [];
                    this.correctAnswerIndex = newVal.answers ?
                        newVal.answers.findIndex(answer => answer.is_correct) : -1;
                }
            }
        }
    },

    methods: {

        addAnswer() {
            this.newAnswers.push({ text: '', isCorrect: false });
        },

        removeAnswer(index) {
            this.newAnswers.splice(index, 1);
        },

        closeQuestionModal() {
            this.$emit('close:closeModal');
        },

        saveQuestion() {
            const questionPayload = {
                id: this.questionData.id,
                text: this.newQuestionText,
                score: this.newQuestionScore,
                answers: this.newAnswers.map((answer, index) => ({
                    id: answer.id || null,
                    text: answer.text,
                    isCorrect: index === this.correctAnswerIndex
                })),
            };

            this.$inertia.put(route(this.updateRoute, { quizId: this.quizId }), {
                question: questionPayload
            });

            this.closeQuestionModal();
        }
    }
}
</script>
