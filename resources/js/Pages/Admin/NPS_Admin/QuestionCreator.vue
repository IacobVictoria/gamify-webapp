<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-md shadow-md w-1/3">
      <h2 class="text-lg font-semibold mb-4">Add New Question</h2>

      <!-- Question Text -->
      <input v-model="question.text" type="text" placeholder="Question Text" class="w-full p-2 rounded border mb-4" />

      <!-- Question Type -->
      <select v-model="question.type" class="w-full p-2 rounded border mb-4" @change="handleTypeChange">
        <option value="binary">Binary</option>
        <option value="scale">Scale</option>
        <option value="open">Open</option>
        <option value="multiple_choice">Multiple Choice</option>
      </select>

      <!-- Scale Dropdown -->
      <div v-if="question.type === 'scale'">
        <h3 class="text-sm font-medium mb-2">Scale Options</h3>
        <select v-model="scaleOption" class="w-full p-2 rounded border">
          <option value="unlikely-likely">Unlikely → Likely</option>
          <option value="very_bad-very_good">Very Bad → Very Good</option>
          <option value="worse-excellent">Worse → Excellent</option>
        </select>
      </div>

      <!-- Binary Answers -->
      <div v-if="question.type === 'binary'">
        <h3 class="text-sm font-medium mb-2">Binary Answers</h3>
        <h3 class="text-sm font-medium mb-2">Choose the promoter choice! </h3>
        <ul>
          <li v-for="(answer, index) in answers" :key="index" class="flex items-center gap-2">
            <input type="radio" :value="index" v-model="promoterIndex" class="cursor-pointer" />
            <span class="text-gray-700">{{ answer.text }}</span>
          </li>
        </ul>
      </div>

      <!-- Multiple Choice Answers -->
      <div v-if="question.type === 'multiple_choice'">
        <h3 class="text-sm font-medium mb-2">Multiple Choice Answers</h3>
        <div v-for="(answer, index) in answers" :key="index" class="flex items-center gap-2 mb-2">
          <input v-model="answer.text" type="text" placeholder="Option text" class="w-full p-2 rounded border" />
          <button @click="removeAnswer(index)" class="text-red-500">
            Remove
          </button>
        </div>
        <button @click="addAnswer" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">
          Add Option
        </button>
      </div>

      <!-- Save & Cancel Buttons -->
      <div class="mt-4 flex justify-end gap-2">
        <button @click="saveQuestion" class="bg-blue-500 text-white py-2 px-4 rounded">
          Save Question
        </button>
        <button @click="$emit('close')" class="bg-red-500 text-white py-2 px-4 rounded">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      question: {
        text: "",
        type: "binary", // Default type
      },
      answers: [], // For storing binary or multiple-choice answers
      promoterIndex: 0, // Index of the promoter in binary answers
      scaleOption: "unlikely-likely", // Default scale description
    };
  },
  methods: {
    handleTypeChange() {
      if (this.question.type === "binary") {
        this.answers = [
          { text: "Yes", is_promoter: true },
          { text: "No", is_promoter: false },
        ];
        this.promoterIndex = 0; // Default to the first option as promoter
      } else if (this.question.type === "multiple_choice") {
        this.answers = [
          { text: "" },
          { text: "" },
          { text: "" },
          { text: "" },
        ];
      } else if (this.question.type === "scale") {
        this.answers = []; // Reset for scale
      } else {
        this.answers = [];
      }
    },
    addAnswer() {
      if (this.answers.length < 10) {
        this.answers.push({ text: "" });
      }
    },
    removeAnswer(index) {
      this.answers.splice(index, 1);
    },
    saveQuestion() {
      if (!this.question.text) {
        alert("Question text is required!");
        return;
      }

      const questionPayload = {
      //  survey_id: this.surveyId, // Asigură-te că ai surveyId setat
        text: this.question.text,
        type: this.question.type,
        answers:
          this.question.type === "scale"
            ? [{ text: this.scaleOption }] 
            : this.answers.map((answer) => ({
              text: answer.text,
              is_promoter: answer.is_promoter || false,
            })),
      };

      axios
        .post("/admin/nps/questions/store", questionPayload)
        .then((response) => {
          alert("Question saved successfully!");
          this.$emit("close");
        })
        .catch((error) => {
          console.error("Error saving question:", error);
          alert("Failed to save question.");
        });
    },

},
mounted() {
  this.handleTypeChange(); // Initialize answers for the default type
},
};

</script>