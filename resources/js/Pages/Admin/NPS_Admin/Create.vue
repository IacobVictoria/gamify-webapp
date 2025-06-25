<template>
    <AuthenticatedLayout>
        <div class="survey-builder flex space-x-4 mt-8">
            <!-- Available Questions List -->
            <div class="w-1/3 bg-gray-100 p-4 rounded-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Available Questions</h2>
                    <button
                        @click="openQuestionCreator"
                        class="bg-green-500 text-white py-1 px-3 rounded"
                    >
                        Adaugă întrebare
                    </button>
                </div>
                <div
                    v-for="question in localAvailableQuestions"
                    :key="question.id"
                    class="bg-white p-3 mb-2 rounded shadow-md"
                    draggable="true"
                    @dragstart="startDrag(question)"
                >
                    <p class="font-medium">{{ question.text }}</p>
                    <p class="text-sm text-gray-500">{{ question.type }}</p>
                    <div class="flex space-x-2 mt-2">
                        <button
                            @click="openQuestionEditor(question)"
                            class="bg-yellow-500 text-white py-1 px-3 rounded text-sm"
                        >
                            Editează
                        </button>
                        <button
                            @click="deleteQuestion(question.id)"
                            class="bg-red-500 text-white py-1 px-3 rounded text-sm"
                        >
                            Șterge
                        </button>
                    </div>

                    <button
                        @click="viewChoices(question)"
                        v-if="question.type === 'multiple_choice'"
                        class="text-blue-500 text-sm mt-2"
                    >
                        Vezi răspunsurile
                    </button>

                    <!-- Show choices only if the question is active -->
                    <div
                        v-if="
                            activeQuestion && activeQuestion.id === question.id
                        "
                        class="mt-2"
                    >
                        <ul>
                            <li
                                v-for="choice in question.choices"
                                :key="choice.id"
                                class="text-sm text-gray-600"
                            >
                                {{ choice.text }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="w-2/3 bg-gray-200 p-4 rounded-md">
                <h2 class="text-lg font-semibold mb-4">Create Survey</h2>
                <input
                    v-model="survey.title"
                    type="text"
                    class="mb-4 w-full p-2 rounded border"
                    placeholder="Titlu"
                />
                <textarea
                    v-model="survey.description"
                    rows="3"
                    class="mb-4 w-full p-2 rounded border"
                    placeholder="Descriere"
                ></textarea>
                <!-- Checkbox for is_published -->
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            v-model="survey.is_published"
                            class="form-checkbox"
                        />
                        <span class="ml-2">Publică sondaj</span>
                    </label>
                </div>
                <div
                    class="bg-gray-100 p-4 rounded-md min-h-[200px]"
                    @dragover.prevent
                    @drop="onDrop"
                >
                    <p v-if="!survey.questions.length" class="text-gray-500">
                        Trage întrebările aici pentru a le adăuga la sondaj.
                    </p>
                    <div
                        v-for="(question, index) in survey.questions"
                        :key="question.id"
                        class="bg-white p-3 mb-2 rounded shadow-md flex justify-between items-center"
                    >
                        <p>{{ question.text }}</p>
                        <button
                            @click="removeQuestion(index)"
                            class="text-red-500"
                        >
                            Șterge
                        </button>
                    </div>
                </div>
                <button
                    @click="submitSurvey"
                    :class="[
                        'mt-4 py-2 px-4 rounded',
                        !survey.title || !survey.questions.length
                            ? 'bg-gray-400 cursor-not-allowed text-white'
                            : 'bg-blue-500 text-white hover:bg-blue-600',
                    ]"
                    :disabled="!survey.title || !survey.questions.length"
                >
                    Creează sondaj
                </button>
            </div>
        </div>

        <QuestionCreator
            v-if="showQuestionCreator"
            @close="showQuestionCreator = false"
            @questionAdded="addQuestion"
        />
        <QuestionEdit
            v-if="showQuestionEditor"
            :questionData="editingQuestion"
            @close="closeQuestionEditor"
            @questionUpdated="updateQuestion"
        />
    </AuthenticatedLayout>
</template>

<script>
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import QuestionCreator from "./QuestionCreator.vue";
import QuestionEdit from "./QuestionEdit.vue";
import Swal from "sweetalert2";

export default {
    props: {
        availableQuestions: Array,
    },
    data() {
        return {
            localAvailableQuestions: [...this.availableQuestions],
            survey: {
                title: "",
                description: "",
                questions: [],
                is_published: false,
            },
            showQuestionCreator: false,
            draggedQuestion: null,
            showQuestionEditor: false,
            editingQuestion: null,
            activeQuestion: null,
        };
    },
    components: {
        AuthenticatedLayout,
        QuestionCreator,
        QuestionEdit,
    },
    methods: {
        startDrag(question) {
            this.draggedQuestion = question;
        },
        onDrop() {
            if (
                this.draggedQuestion &&
                !this.survey.questions.some(
                    (q) => q.id === this.draggedQuestion.id
                )
            ) {
                // Adăugăm întrebarea în survey
                this.survey.questions.push(this.draggedQuestion);
                // Elimin întrebarea din lista locală de întrebări disponibile
                this.localAvailableQuestions =
                    this.localAvailableQuestions.filter(
                        (q) => q.id !== this.draggedQuestion.id
                    );
            }
            this.draggedQuestion = null;
        },
        removeQuestion(index) {
            const [removed] = this.survey.questions.splice(index, 1);
            // Adăugăm întrebarea înapoi în lista locală de întrebări disponibile
            this.localAvailableQuestions.push(removed);
        },
        async submitSurvey() {
            try {
                await axios.post("/admin/nps/survey/store", {
                    title: this.survey.title,
                    description: this.survey.description,
                    is_published: this.survey.is_published,
                    question_ids: this.survey.questions.map((q) => q.id),
                });
                await Swal.fire({
                    title: "Sondaj creat!",
                    text: "Sondajul a fost salvat cu succes.",
                    icon: "success",
                    confirmButtonText: "OK",
                }).then(() => {
                    window.location.href = "/admin/control_center";
                });
                this.survey.title = "";
                this.survey.description = "";
                this.survey.questions = [];
            } catch (error) {
                Swal.fire({
                    title: "Eroare!",
                    text: "A apărut o problemă la salvarea sondajului.",
                    icon: "error",
                    confirmButtonText: "Închide",
                });
                console.error("Error creating survey:", error);
            }
        },
        openQuestionCreator() {
            this.showQuestionCreator = true;
        },
        addQuestion(newQuestion) {
            this.localAvailableQuestions.push(newQuestion);
        },
        viewChoices(question) {
            this.activeQuestion =
                this.activeQuestion && this.activeQuestion.id === question.id
                    ? null
                    : question;
        },
        openQuestionEditor(question) {
            this.editingQuestion = { ...question };
            this.showQuestionEditor = true;
        },
        closeQuestionEditor() {
            this.showQuestionEditor = false;
            this.editingQuestion = null;
        },
        async deleteQuestion(questionId) {
            const result = await Swal.fire({
                title: "Ești sigur?",
                text: "Această întrebare va fi ștearsă permanent.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Da, șterge",
                cancelButtonText: "Anulează",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(
                        `/admin/nps/questions/delete/${questionId}`
                    );
                    // eliminare din lista locală - efect instant
                    this.localAvailableQuestions =
                        this.localAvailableQuestions.filter(
                            (q) => q.id !== questionId
                        );
                } catch (error) {
                    console.error("Error deleting question:", error);
                    Swal.fire(
                        "Eroare!",
                        "A apărut o problemă la ștergere.",
                        "error"
                    );
                }
            }
        },
        updateQuestion(updatedQuestion) {
            const index = this.localAvailableQuestions.findIndex(
                (q) => q.id === updatedQuestion.id
            );
            if (index !== -1) {
                this.localAvailableQuestions.splice(index, 1, updatedQuestion);
            }
        },
    },
};
</script>
