<template>
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
      <div class="w-full max-w-3xl bg-white p-6">
        <h1 class="text-2xl font-bold text-center text-indigo-700 mb-6">🧠 AI Diet Assistant</h1>
  
        <!-- Suggested Questions -->
        <div class="mb-6">
          <p class="text-sm text-gray-500 mb-2">Quick suggestions:</p>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="(q, index) in quickQuestions"
              :key="index"
              @click="sendQuestion(q)"
              class="px-4 py-2 bg-gray-100 hover:bg-indigo-100 text-sm rounded-full border border-gray-300 transition"
            >
              {{ q }}
            </button>
          </div>
        </div>
  
        <!-- Chat Box -->
        <div class="h-96 overflow-y-auto bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
          <div v-for="(message, index) in messages" :key="index" class="mb-3">
            <div :class="message.is_user ? 'text-right' : 'text-left'">
              <span
                :class="message.is_user ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800'
                "
                class="inline-block px-4 py-2 rounded-lg max-w-xs"
              >
                {{ message.content }}
              </span>
            </div>
          </div>
  
          <div v-if="isLoading" class="text-left text-sm text-gray-500 italic mb-2">
            <span class="inline-block px-4 py-2 rounded-lg bg-gray-100 animate-pulse">
              AI is typing...
            </span>
          </div>
  
          <div v-if="products.length" class="mt-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Suggested Products</h2>
            <ul class="text-sm text-gray-600 space-y-1">
              <li v-for="product in products" :key="product.id">
                <strong>{{ product.name }}</strong>: {{ product.description }} - <em>{{ product.price }} RON</em>
              </li>
            </ul>
          </div>
        </div>
  
        <!-- Input Area -->
        <form @submit.prevent="sendMessage" class="flex gap-2">
          <textarea
            v-model="newMessage"
            placeholder="Type your message..."
            rows="2"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 resize-none"
          ></textarea>
          <button
            type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-semibold"
            :disabled="isLoading"
          >
            Send
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const messages = ref([]);
      const newMessage = ref('');
      const products = ref([]);
      const isLoading = ref(false);
  
      const quickQuestions = [
        'Dietă pentru scădere în greutate',
        'Dietă pentru creștere musculară',
        'Menținere sănătate',
        'Care sunt cele mai bune alimente pentru creșterea masei musculare?',
        'Ce exerciții mă pot ajuta să ard calorii rapid?',
        'Cum pot înlocui zahărul în dieta mea?'
      ];
  
      const sendMessage = async () => {
        if (!newMessage.value.trim()) return;
  
        const userMsg = { content: newMessage.value, is_user: true };
        messages.value.push(userMsg);
  
        isLoading.value = true;
  
        try {
          const response = await axios.post('/user/wellness/openai/respond-messages', {
            message: newMessage.value
          });
  
          messages.value.push({ content: response.data.response, is_user: false });
          products.value = response.data.products || [];
          newMessage.value = '';
        } catch (error) {
          console.error('Error sending message:', error);
          messages.value.push({ content: '⚠️ Eroare. Încearcă din nou mai târziu.', is_user: false });
        } finally {
          isLoading.value = false;
        }
      };
  
      const sendQuestion = (question) => {
        newMessage.value = question;
        sendMessage();
      };
  
      return {
        messages,
        newMessage,
        sendMessage,
        sendQuestion,
        products,
        isLoading,
        quickQuestions
      };
    }
  };
  </script>
  
  <style scoped>
  textarea:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  </style>