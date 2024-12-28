<template>
  <AuthenticatedLayout>
    <div class="survey-builder flex space-x-4">
      <!-- Available Questions List -->
      <div class="w-1/3 bg-gray-100 p-4 rounded-md">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Available Questions</h2>
          <button @click="openQuestionCreator" class="bg-green-500 text-white py-1 px-3 rounded">
            Add Question
          </button>
        </div>
        <div v-for="question in localAvailableQuestions" :key="question.id" class="bg-white p-3 mb-2 rounded shadow-md"
          draggable="true" @dragstart="startDrag(question)">
          <p class="font-medium">{{ question.text }}</p>
          <p class="text-sm text-gray-500">{{ question.type }}</p>
          <div class="flex space-x-2 mt-2">
            <button @click="openQuestionEditor(question)" class="bg-yellow-500 text-white py-1 px-3 rounded text-sm">
              Edit
            </button>
            <button @click="deleteQuestion(question.id)" class="bg-red-500 text-white py-1 px-3 rounded text-sm">
              Delete
            </button>
          </div>

          <button @click="viewChoices(question)" v-if="question.type === 'multiple_choice'"
            class="text-blue-500 text-sm mt-2">
            View Choices
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

      <div class="w-2/3 bg-gray-200 p-4 rounded-md">
        <h2 class="text-lg font-semibold mb-4">Create Survey</h2>
        <input v-model="survey.title" type="text" class="mb-4 w-full p-2 rounded border" placeholder="Survey Title" />
        <textarea v-model="survey.description" rows="3" class="mb-4 w-full p-2 rounded border"
          placeholder="Survey Description"></textarea>
        <!-- Checkbox for is_published -->
        <div class="mb-4">
          <label class="inline-flex items-center">
            <input type="checkbox" v-model="survey.is_published" class="form-checkbox">
            <span class="ml-2">Publish Survey</span>
          </label>
        </div>
        <div class="bg-gray-100 p-4 rounded-md min-h-[200px]" @dragover.prevent @drop="onDrop">
          <p v-if="!survey.questions.length" class="text-gray-500">
            Drag questions here to add them to the survey.
          </p>
          <div v-for="(question, index) in survey.questions" :key="question.id"
            class="bg-white p-3 mb-2 rounded shadow-md flex justify-between items-center">
            <p>{{ question.text }}</p>
            <button @click="removeQuestion(index)" class="text-red-500">Remove</button>
          </div>
        </div>

        <button @click="submitSurvey" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded"
          :disabled="!survey.title || !survey.questions.length">
          Create Survey
        </button>
      </div>
    </div>

    <QuestionCreator v-if="showQuestionCreator" @close="showQuestionCreator = false" @questionAdded="addQuestion" />
    <QuestionEdit v-if="showQuestionEditor" :questionData="editingQuestion" @close="closeQuestionEditor"
      @questionUpdated="updateQuestion" />
  </AuthenticatedLayout>
</template>

<script>
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import QuestionCreator from "./QuestionCreator.vue";
import QuestionEdit from "./QuestionEdit.vue";

export default {
  props: {
    availableQuestions: Array
  },
  data() {
    return {
      // Creăm o copie locală a listelor de întrebări disponibile pentru manipulare
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
      activeQuestion: null,  // Variabilă pentru a ține evidența întrebării curente pentru care afișezi alegerile
    };
  },
  components: {
    AuthenticatedLayout,
    QuestionCreator,
    QuestionEdit
  },
  methods: {
    startDrag(question) {
      this.draggedQuestion = question;
    },
    onDrop() {
      if (this.draggedQuestion && !this.survey.questions.some((q) => q.id === this.draggedQuestion.id)) {
        // Adăugăm întrebarea în survey
        this.survey.questions.push(this.draggedQuestion);
        // Eliminăm întrebarea din lista locală de întrebări disponibile
        this.localAvailableQuestions = this.localAvailableQuestions.filter(q => q.id !== this.draggedQuestion.id);
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
        const response = await axios.post("/admin/nps/survey/store", {
          title: this.survey.title,
          description: this.survey.description,
          is_published: this.survey.is_published,
          question_ids: this.survey.questions.map((q) => q.id),
        });
        alert("Survey created successfully!");
        this.survey.title = "";
        this.survey.description = "";
        this.survey.questions = [];
      } catch (error) {
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
      this.activeQuestion = this.activeQuestion && this.activeQuestion.id === question.id ? null : question;
    },
    openQuestionEditor(question) {
      this.editingQuestion = { ...question };
      this.showQuestionEditor = true;
      console.log(this.editingQuestion.choices);
    },
    closeQuestionEditor() {
      this.showQuestionEditor = false;
      this.editingQuestion = null;
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
