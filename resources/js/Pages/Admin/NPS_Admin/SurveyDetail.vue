<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ survey.title }}</h1>
            <p class="text-gray-600 mb-4">
                {{ survey.description || "No description available" }}
            </p>
            <p class="text-sm font-medium mb-4">
                Status:
                <span
                    :class="{
                        'text-green-500 font-semibold': survey.is_published,
                        'text-red-500 font-semibold': !survey.is_published,
                    }"
                >
                    {{ survey.is_published ? "Published" : "Not Published" }}
                </span>
            </p>

            <div class="flex space-x-4 mb-6">
                <button
                    @click="editSurvey"
                    class="bg-yellow-500 text-white py-2 px-4 rounded"
                >
                    Editează
                </button>
                <button
                    @click="deleteSurvey"
                    class="bg-red-500 text-white py-2 px-4 rounded"
                >
                    Șterge
                </button>
            </div>

            <div class="bg-gray-100 p-4 rounded-md">
                <h2 class="text-xl font-semibold mb-4">Întrebări</h2>
                <div
                    v-for="question in survey.questions"
                    :key="question.id"
                    class="bg-white p-3 mb-2 rounded shadow-md"
                >
                    <p class="font-medium">{{ question.text }}</p>
                    <p class="text-sm text-gray-500">{{ question.type }}</p>
                    <div class="flex space-x-2 mt-2">
                        <button
                            @click="editQuestion(question)"
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
                    <div
                        v-if="question.type === 'multiple_choice'"
                        class="mt-2"
                    >
                        <button
                            @click="viewChoices(question)"
                            class="text-blue-500 text-sm mt-2"
                        >
                            {{
                                activeQuestion &&
                                activeQuestion.id === question.id
                                    ? "Hide Choices"
                                    : "View Choices"
                            }}
                        </button>

                        <!-- Show choices only if the question is active -->
                        <div
                            v-if="
                                activeQuestion &&
                                activeQuestion.id === question.id
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
            </div>
        </div>
    </AuthenticatedLayout>
    <QuestionEdit
        v-if="showQuestionEditor"
        :questionData="editingQuestion"
        @close="closeQuestionEditor"
        @questionUpdated="updateQuestion"
    />
    <SurveyEdit
        v-if="showSurveyEditor"
        :surveyData="editingSurvey"
        @close="closeSurveyEditor"
        @surveyUpdated="updateSurvey"
    />
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import QuestionEdit from "./QuestionEdit.vue";
import SurveyEdit from "./SurveyEdit.vue";
import Swal from "sweetalert2";

export default {
    components: {
        AuthenticatedLayout,
        QuestionEdit,
        SurveyEdit,
    },
    props: {
        survey: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            activeQuestion: null,
            editingQuestion: null,
            showQuestionEditor: false,
            showSurveyEditor: false,
            editingSurvey: null,
        };
    },
    methods: {
        editSurvey() {
            this.editingSurvey = { ...this.survey };
            this.showSurveyEditor = true;
        },
        closeSurveyEditor() {
            this.showSurveyEditor = false;
            this.editingSurvey = null;
        },
        deleteSurvey() {
            Swal.fire({
                title: "Șterge sondaj?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Da",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/admin/nps/delete/${this.survey.id}`)
                        .then(() => {
                            router.visit("/admin/nps/index");
                        })
                        .catch(() =>
                            Swal.fire("Eroare", "Nu se poate șterge.", "error")
                        );
                }
            });
        },
        closeQuestionEditor() {
            this.showQuestionEditor = false;
            this.editingQuestion = null;
        },
        editQuestion(question) {
            this.editingQuestion = { ...question };
            this.showQuestionEditor = true;
        },
        viewChoices(question) {
            this.activeQuestion =
                this.activeQuestion && this.activeQuestion.id === question.id
                    ? null
                    : question;
        },
        async deleteQuestion(questionId) {
            const result = await Swal.fire({
                title: "Șterge întrebare?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Da",
                cancelButtonText: "Nu",
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.delete(
                        `/admin/nps/questions/delete/${questionId}`
                    );

                    const index = this.survey.questions.findIndex(
                        (q) => q.id === questionId
                    );
                    if (index !== -1) {
                        this.survey.questions.splice(index, 1);
                    }

                } catch (error) {
                    console.error("Error deleting question:", error);
                    Swal.fire(
                        "Eroare",
                        "A apărut o eroare la ștergere.",
                        "error"
                    );
                }
            }
        },
        updateSurvey(updatedSurvey) {
            this.survey.title = updatedSurvey.title;
            this.survey.description = updatedSurvey.description;
            this.survey.is_published = updatedSurvey.is_published;
        },
        updateQuestion(updatedQuestion) {
            const index = this.survey.questions.findIndex(
                (q) => q.id === updatedQuestion.id
            );
            if (index !== -1) {
                this.survey.questions.splice(index, 1, updatedQuestion);
            }
        },
    },
};
</script>
