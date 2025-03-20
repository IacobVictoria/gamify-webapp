<template>
    <AuthenticatedLayout>
        <div class="m-12 grid grid-cols-3 gap-6">
            <!-- Titlu -->
            <div class="col-span-3 text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-700 flex items-center justify-center">
                    ✍️ Let's Update this Awesome Quiz!
                </h1>
            </div>
            <!-- Card pentru Editare Quiz -->
            <div class="col-span-3 p-6 bg-white shadow-md rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-blue-600">📋 Edit Quiz Details</h2>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Titlu Quiz -->
                    <div>
                        <label class="block text-gray-600 font-medium text-sm mb-1">🏷️ Title</label>
                        <input v-model="quiz.title" placeholder="Enter quiz title"
                            class="w-full p-3 text-sm border rounded focus:ring-2 focus:ring-blue-400" />
                    </div>

                    <!-- Descriere Quiz -->
                    <div>
                        <label class="block text-gray-600 font-medium text-sm mb-1">📖 Description</label>
                        <input v-model="quiz.description" placeholder="Enter quiz description"
                            class="w-full p-3 text-sm border rounded focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <!-- Difficulty Quiz -->
                    <div>
                        <label class="block text-gray-600 font-medium text-sm mb-1">📖 Difficulty</label>
                        <select v-model="quiz.difficulty"
                            class="w-full p-2 text-sm border rounded focus:ring-2 focus:ring-blue-400">
                            <option v-for="difficulty in difficulties" :key="difficulty" :value="difficulty">
                                {{ difficulty.charAt(0).toUpperCase() + difficulty.slice(1) }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Butoane Update / Delete -->
                <div class="flex justify-between mt-4">
                    <button @click="updateQuiz"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center transition">
                        💾 Update Quiz
                    </button>
                    <button @click="deleteQuiz"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center transition">
                        🗑️ Delete Quiz
                    </button>
                </div>
            </div>
            <!-- Lista Întrebărilor -->
            <div class="col-span-3 p-6 bg-white shadow-md rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-blue-500">📝 Quiz Questions</h2>

                <button @click="addNewQuestion"
                    class="w-[16em] bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4 flex items-center justify-center transition">
                    ➕ Add New Question
                </button>

                <div class="overflow-hidden rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Question</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template v-for="(question, index) in quiz.questions" :key="index">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            {{ question.question }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ question.score }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-4">
                                        <button @click="editQuestion(question)"
                                            class="text-blue-500 hover:text-blue-700 flex items-center">
                                            ✏️ Edit
                                        </button>
                                        <button @click="deleteQuestion(question)"
                                            class="text-red-500 hover:text-red-700 flex items-center">
                                            🗑️ Delete
                                        </button>
                                        <button @click="toggleAnswers(index)" class="text-gray-600 flex items-center">
                                            {{ showAnswers[index] ? '🔽 Hide' : '🔼 Show' }} Answers
                                        </button>
                                    </td>
                                </tr>
                                <!-- Lista Răspunsurilor -->
                                <tr v-if="showAnswers[index]">
                                    <td colspan="3">
                                        <ul class="space-y-2 mt-2">
                                            <li v-for="(answer, answerIndex) in question.answers" :key="answerIndex"
                                                class="flex justify-between items-center p-2 mb-2 bg-gray-100 rounded">
                                                <div class="flex items-center">
                                                    <span class="text-gray-800">{{ answer.answer }}</span>
                                                </div>
                                                <span
                                                    :class="{ 'bg-green-400': answer.is_correct, 'bg-red-400': !answer.is_correct }"
                                                    class="w-5 h-5 rounded-full mr-8"></span>
                                                <button class="text-red-600 hover:text-red-900"
                                                    @click="deleteAnswer(answer)">🗑️ Delete</button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal for Adding Question -->
            <AddQuizQuestion :isQuestionModalOpen="isAddQuestionOpen" :quiz-id="quiz.id"
                :add-route="'admin.quiz_add_questions.store'" @close:closeModal="closeAddQuestionModal">
            </AddQuizQuestion>

            <UpdateQuizQuestion :is-update-open="isEditQuestionOpen" :update-route="'admin.quiz_update_question.update'"
                :question-data="questionToEdit" :quiz-id="quiz.id" @close:closeModal="closeEditQuestionModal">
            </UpdateQuizQuestion>

            <GenericDeleteNotification :delete-route="deleteRoute" :object-id="objectToDelete" :message="deleteMessage"
                :open="isDeleteOpen" @update:open="isDeleteOpen = $event" />
        </div>
    </AuthenticatedLayout>

</template>

<script>
import AddQuizQuestion from '@/Components/AddQuizQuestion.vue';
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';
import UpdateQuizQuestion from '@/Components/UpdateQuizQuestion.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AddQuizQuestion,
        UpdateQuizQuestion,
        GenericDeleteNotification,
        AuthenticatedLayout
    },
    props: {
        quiz: Object,
        difficulties: Array
    },
    data() {
        return {
            isAddQuestionOpen: false,
            showAnswers: [],
            isEditQuestionOpen: false,
            questionToEdit: null,
            isDeleteOpen: false,
            deleteRoute: '',
            deleteMessage: '',
            objectToDelete: '',

        };
    },
    mounted() {
        this.showAnswers = this.quiz.questions.map(() => false);
    },
    methods: {
        updateQuiz() {
            this.$inertia.put(route('admin.user_quiz.update', { quizId: this.quiz.id }), {
                title: this.quiz.title,
                description: this.quiz.description,
                difficulty: this.quiz.difficulty
            });
        },
        deleteQuiz() {
            this.isDeleteOpen = true;
            this.deleteRoute = 'admin.user_quiz.destroy';
            this.deleteMessage += 'Are you sure  want to delete this quiz ?';
            this.objectToDelete = this.quiz.id;
        },
        addNewQuestion() {
            this.isAddQuestionOpen = true;
        },
        closeAddQuestionModal() {
            this.isAddQuestionOpen = false;
        },
        closeEditQuestionModal() {
            this.isEditQuestionOpen = false;
            this.questionToEdit = null;
        },
        toggleAnswers(index) {
            this.showAnswers[index] = !this.showAnswers[index];
        },
        editQuestion(question) {
            this.isEditQuestionOpen = true;
            this.questionToEdit = question;
            console.log(this.questionToEdit);
        },
        deleteQuestion(question) {
            this.isDeleteOpen = true;
            this.deleteRoute = 'admin.questions.destroy';
            this.deleteMessage += 'Are you sure  want to delete this question ?';
            this.objectToDelete = question.id;
        },
        deleteAnswer(answer) {
            this.isDeleteOpen = true;
            this.deleteRoute = 'admin.answers.destroy';
            this.deleteMessage += ' Are you sure  want to delete this answer ?';
            this.objectToDelete = answer.id;
        }
    },
};
</script>

