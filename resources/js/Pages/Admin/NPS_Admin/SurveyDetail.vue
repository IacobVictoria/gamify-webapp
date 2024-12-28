<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ survey.title }}</h1>
            <p class="text-gray-600 mb-4">{{ survey.description || 'No description available' }}</p>
            <p class="text-sm font-medium mb-4">
                Status:
                <span :class="{
                    'text-green-500 font-semibold': survey.is_published,
                    'text-red-500 font-semibold': !survey.is_published,
                }">
                    {{ survey.is_published ? 'Published' : 'Not Published' }}
                </span>
            </p>

            <div class="flex space-x-4 mb-6">
                <button @click="editSurvey" class="bg-yellow-500 text-white py-2 px-4 rounded">
                    Edit Survey
                </button>
                <button @click="deleteSurvey" class="bg-red-500 text-white py-2 px-4 rounded">
                    Delete Survey
                </button>
            </div>

            <div class="bg-gray-100 p-4 rounded-md">
                <h2 class="text-xl font-semibold mb-4">Questions</h2>
                <div v-for="question in survey.questions" :key="question.id"
                    class="bg-white p-3 mb-2 rounded shadow-md">
                    <p class="font-medium">{{ question.text }}</p>
                    <p class="text-sm text-gray-500">{{ question.type }}</p>
                    <div class="flex space-x-2 mt-2">
                        <button @click="editQuestion(question)"
                            class="bg-yellow-500 text-white py-1 px-3 rounded text-sm">
                            Edit
                        </button>
                        <button @click="deleteQuestion(question.id)"
                            class="bg-red-500 text-white py-1 px-3 rounded text-sm">
                            Delete
                        </button>
                    </div>
                    <div v-if="question.type === 'multiple_choice'" class="mt-2">
                        <button @click="viewChoices(question)" class="text-blue-500 text-sm mt-2">
                            {{ activeQuestion && activeQuestion.id === question.id ? 'Hide Choices' : 'View Choices' }}
                        </button>

                        <!-- Show choices only if the question is active -->
                        <div v-if="activeQuestion && activeQuestion.id === question.id" class="mt-2">
                            <ul>
                                <li v-for="choice in question.choices" :key="choice.id" class="text-sm text-gray-600">
                                    {{ choice.text }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <QuestionEdit v-if="showQuestionEditor" :questionData="editingQuestion" @close="closeQuestionEditor"
        @questionUpdated="updateQuestion" />
    <SurveyEdit v-if="showSurveyEditor" :surveyData="editingSurvey" @close="closeSurveyEditor"
        @surveyUpdated="updateSurvey" />
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import QuestionEdit from './QuestionEdit.vue';
import SurveyEdit from './SurveyEdit.vue';

export default {
    components: {
        AuthenticatedLayout,
        QuestionEdit,
        SurveyEdit
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
            editingSurvey: null
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
            if (confirm('Are you sure you want to delete this survey?')) {
                axios
                    .delete(`/admin/nps/surveys/delete/${this.survey.id}`)
                    .then(() => {
                        alert('Survey deleted successfully!');
                        router.visit('/admin/nps/surveys');
                    })
                    .catch((error) => {
                        console.error('Error deleting survey:', error);
                        alert('Failed to delete survey.');
                    });
            }
        },
        closeQuestionEditor() {
            this.showQuestionEditor = false;
            this.editingQuestion = null;
        },
        editQuestion(question) {
            this.editingQuestion = { ...question };
            this.showQuestionEditor = true;
        },
        deleteQuestion(questionId) {
            alert(`Delete question with ID: ${questionId}`);
        },
        viewChoices(question) {
            this.activeQuestion = this.activeQuestion && this.activeQuestion.id === question.id ? null : question;
        },
        async deleteQuestion(questionId) {
            if (confirm("Are you sure you want to delete this question?")) {
                axios
                    .delete(`/admin/nps/questions/delete/${questionId}`)
                    .then((response) => {
                        alert(response.data.message);
                    })
                    .catch((error) => {
                        console.error("Error deleting question:", error);
                        alert("Failed to delete question.");
                    });
            }
        },
    },
};
</script>
