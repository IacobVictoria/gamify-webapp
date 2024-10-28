<template>
    <AuthenticatedLayout>
        <div class="m-32 p-6 max-w-lg mx-auto bg-white shadow-md rounded-lg">
            <h1 class="text-xl font-semibold mb-4">Create New Quiz</h1>
            <div class="mb-4">
                <input v-model="quizTitle" placeholder="Quiz Title" class="w-full p-2 border rounded" />
            </div>

            <button @click="openQuestionModal" class="bg-blue-500 text-white px-4 py-2 rounded">
                Add New Question
            </button>

            <!-- Displaying the added questions -->
            <div class="mt-4">
                <h2 class="text-lg font-semibold mb-2">Questions</h2>
                <div v-for="(question, index) in questions" :key="index" class="p-2 border-b">
                    <h3 class="font-semibold">{{ question.text }} (Score: {{ question.score }})</h3>
                    <div v-for="(answer, ansIndex) in question.answers" :key="ansIndex" class="ml-4">
                        <p>{{ ansIndex + 1 }}. {{ answer.text }} <span v-if="answer.isCorrect">(Correct)</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isQuestionModalOpen"
            class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg mx-auto">
                <h2 class="text-2xl font-bold text-center mb-4 text-blue-500">Add New Question</h2>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Question</label>
                    <input v-model="newQuestionText" placeholder="Enter question text"
                        class="w-full p-2 border rounded" />
                    <input v-model="newQuestionScore" type="number" placeholder="Enter a score for the question" class="w-full p-2 border rounded mt-2" />
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
                        <button @click="removeAnswer(index)" class="text-red-500 ml-2 hover:text-red-700">
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
                    <button @click="saveQuestion" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    <button @click="closeQuestionModal" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
                </div>
            </div>
        </div>

        <!-- Final Save Button -->
        <div class="m-32 p-6 max-w-lg mx-auto">
            <button @click="postQuiz" class="bg-green-500 text-white px-4 py-2 rounded">Post Quiz</button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    data() {
        return {
            quizTitle: '',
            isQuestionModalOpen: false,
            newQuestionText: '',
            newQuestionScore: '',
            newAnswers: [{ text: '', isCorrect: false }],
            correctAnswerIndex: null,
            questions: [],
        };
    },
    methods: {
        openQuestionModal() {
            this.isQuestionModalOpen = true;
        },

        closeQuestionModal() {
            this.isQuestionModalOpen = false;
            // Reset modal fields when closed
            this.resetQuestionModal();
        },

        addAnswer() {
            this.newAnswers.push({ text: '', isCorrect: false });
        },

        removeAnswer(index) {
            this.newAnswers.splice(index, 1);
        },

        saveQuestion() {
            // Validate question input before saving
            if (!this.newQuestionText || this.newAnswers.length === 0 || this.correctAnswerIndex === null) {
                alert("Please fill in all fields for the question and answers.");
                return;
            }

            // Add the new question and its answers to the questions array
            this.questions.push({
                text: this.newQuestionText,
                score: this.newQuestionScore,
                answers: this.newAnswers.map((answer, index) => ({
                    text: answer.text,
                    isCorrect: index === this.correctAnswerIndex
                }))
            });

            // Close modal and reset fields
            this.closeQuestionModal();
        },

        resetQuestionModal() {
            this.newQuestionText = '';
            this.newQuestionScore = '';
            this.newAnswers = [{ text: '', isCorrect: false }];
            this.correctAnswerIndex = null;
        },

        postQuiz() {
            // Prepare the data to be sent to the backend
            const payload = {
                title: this.quizTitle,
                questions: this.questions.map(question => ({
                    text: question.text,
                    score: question.score,
                    answers: question.answers.map((answer, index) => ({
                        text: answer.text,
                        isCorrect: answer.isCorrect,
                    }))
                }))
            };

            // Send the data to the backend to store
            this.$inertia.post(route('admin.user_quiz.store'), payload)
                .then(() => {
                    // Reset the form after successful submission
                    this.quizTitle = '';
                    this.questions = [];
                })
                .catch(error => {
                    console.error("Error posting quiz:", error);
                });
        }
    }
};
</script>
