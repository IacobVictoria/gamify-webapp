<template>
    <div class="feedback-container">
      <h1>Send Feedback</h1>
      <form @submit.prevent="sendFeedback">
        <textarea v-model="feedback" placeholder="Write your feedback here..." required></textarea>
        <button type="submit" :disabled="loading">Send</button>
      </form>
    </div>
  </template>
  
  <script>
  import { ref } from "vue";
  import { useForm } from "@inertiajs/vue3";
  
  export default {
    setup() {
      const feedback = ref("");
      const loading = ref(false);
  
      const sendFeedback = () => {
        loading.value = true;
        useForm({
          feedback: feedback.value,
        }).post("/feedback", {
          onFinish: () => {
            feedback.value = "";
            loading.value = false;
            alert("Feedback sent!");
          },
        });
      };
  
      return { feedback, sendFeedback, loading };
    },
  };
  </script>
  
  <style scoped>
  .feedback-container {
    max-width: 500px;
    margin: 0 auto;
  }
  textarea {
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
  }
  button {
    background-color: #5865f2;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }
  button:disabled {
    background-color: #ccc;
  }
  </style>
  