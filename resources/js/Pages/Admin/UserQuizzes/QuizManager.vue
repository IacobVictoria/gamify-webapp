<template>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-4 text-blue-600">Update Quiz</h1>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Quiz Title</label>
            <input v-model="quiz.title" placeholder="Enter quiz title" class="w-32 p-2 border rounded" />
            <input v-model="quiz.description" placeholder="Enter quiz description" class="w-32 p-2 border rounded" />
            <button @click="updateQuiz" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Update Quiz</button>
        </div>


        <button @click="deleteQuiz" class="bg-red-500 text-white px-4 py-2 rounded mb-4">Delete Quiz</button>

        <h2 class="text-2xl font-bold mb-4 text-blue-500">Questions</h2>
        <button @click="addNewQuestion" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Add Question</button>


        <div class="overflow-hidden rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ question.score }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-4">
                                <button @click="editQuestion(question)"
                                    class="text-gray-600 hover:text-gray-900">Edit</button>
                                <button @click="deleteQuestion(question)"
                                    class="text-red-600 hover:text-red-900">Delete</button>
                                <button @click="toggleAnswers(index)" class="text-grey-600">
                                    {{ showAnswers[index] ? 'Ascunde' : 'Afișează'
                                    }} Răspunsuri
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <ul v-if="showAnswers[index]" class="space-y-2 mt-2 ">
                                <div v-if="question.answers.length > 0">
                                    <li v-for="(answer, answerIndex) in question.answers" :key="answerIndex"
                                        class="flex justify-between items-center p-2 mb-2 bg-gray-100 rounded">
                                        <span>{{ answer.answer }}</span>

                                        <span
                                            :class="{ 'bg-green-400': answer.is_correct, 'bg-red-400': !answer.is_correct }"
                                            class="w-4 h-4 rounded-full"></span>
                                        <button class="text-red-600 hover:text-red-900"
                                            @click="deleteAnswer(answer)">Delete</button>
                                    </li>
                                </div>
                                <div v-else
                                    class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6">
                                    No questions at the moment! Try the quizManager!</div>
                            </ul>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Modal for Adding Question -->
        <AddQuizQuestion :isQuestionModalOpen="isAddQuestionOpen" :quiz-id="quiz.id"
            :add-route="'admin.quiz_add_questions.store'" @close:closeModal="closeAddQuestionModal"></AddQuizQuestion>

        <UpdateQuizQuestion :is-update-open="isEditQuestionOpen" :update-route="'admin.quiz_update_question.update'"
            :question-data="questionToEdit" :quiz-id="quiz.id" @close:closeModal="closeEditQuestionModal">
        </UpdateQuizQuestion>

        <GenericDeleteNotification :delete-route="deleteRoute" :object-id="objectToDelete" :message="deleteMessage"
            :open="isDeleteOpen" @update:open="isDeleteOpen = $event" />
    </div>
</template>

<script>
import AddQuizQuestion from '@/Components/AddQuizQuestion.vue';
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';
import UpdateQuizQuestion from '@/Components/UpdateQuizQuestion.vue';

export default {
    components: {
        AddQuizQuestion,
        UpdateQuizQuestion,
        GenericDeleteNotification
    },
    props: {
        quiz: Object,
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
                description: this.quiz.description
            });
        },
        deleteQuiz() {
            this.isDeleteOpen = true;
            this.deleteRoute = 'admin.user_quiz.destroy';
            this.deleteMessage += 'Are you sure  want to delete this quiz ?';
            this.objectToDelete = this.quiz.id;
            // this.$inertia.delete(route('admin.user_quiz.destroy', { quizId: this.quiz.id }));
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
            // this.$inertia.delete(route('admin.questions.destroy', { id: question.id }));
        },
        deleteAnswer(answer) {
            this.isDeleteOpen = true;
            this.deleteRoute = 'admin.answers.destroy';
            this.deleteMessage += ' Are you sure  want to delete this answer ?';
            this.objectToDelete = answer.id;
            // this.$inertia.delete(route('admin.answers.destroy', { id: answer.id }));
        }
    },
};
</script>

<style scoped>
/* Adăuga stiluri pentru detalii */
</style>
