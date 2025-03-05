<template>
    <section class="max-w-3xl mx-auto my-12 px-4">
      <!-- Titlu cu gradient -->
      <h2 class="text-center text-2xl font-extrabold bg-gradient-to-r from-[#16a34a] to-[#166534] bg-clip-text text-transparent">
        ❓ Frequently Asked Questions
      </h2>
  
      <div class="mt-6 space-y-3">
        <div 
          v-for="(faq, index) in faqs" 
          :key="index" 
          class="bg-white border-l-8 p-4 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
          :class="faq.color"
        >
          <button 
            @click="toggleFAQ(index)" 
            class="flex justify-between items-center w-full text-left text-lg font-semibold"
          >
            <span>{{ faq.emoji }} {{ faq.question }}</span>
            <span class="ml-4 transform transition-transform" :class="{'rotate-180': activeFAQ === index}">
              <svg v-if="activeFAQ !== index" class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 00-.707.293l-4 4a1 1 0 101.414 1.414L10 7.414l3.293 3.293a1 1 0 101.414-1.414l-4-4A1 1 0 0010 5z" clip-rule="evenodd"/>
              </svg>
              <svg v-else class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 10a1 1 0 011.707-.707L10 12.586l3.293-3.293A1 1 0 1115.707 10l-4 4a1 1 0 01-1.414 0l-4-4A1 1 0 015 10z" clip-rule="evenodd"/>
              </svg>
            </span>
          </button>
  
          <div 
            v-show="activeFAQ === index" 
            class="mt-2 text-gray-700 text-md leading-6 transition-all duration-300"
          >
            {{ faq.answer }}
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  // Lista întrebărilor și răspunsurilor cu emoji și culori
  const faqs = ref([
    { question: "What is Crunchy Fuel?", answer: "Crunchy Fuel is your guide to healthy snacks and nutrition education.", emoji: "🍏", color: "border-green-400" },
    { question: "How can I place an order?", answer: "Simply browse our products, add them to your cart, and proceed to checkout.", emoji: "🛒", color: "border-blue-400" },
    { question: "Do you offer subscriptions?", answer: "Yes, we offer flexible subscription plans for recurring snack deliveries.", emoji: "📦", color: "border-yellow-400" },
    { question: "What payment methods do you accept?", answer: "We accept credit cards, PayPal, and other secure payment options.", emoji: "💳", color: "border-red-400" },
  ]);
  
  // Starea activă pentru a deschide/închide întrebările
  const activeFAQ = ref(null);
  
  // Funcția pentru a activa sau dezactiva un răspuns
  const toggleFAQ = (index) => {
    activeFAQ.value = activeFAQ.value === index ? null : index;
  };
  </script>
  
  <style scoped>
  /* Smooth hover effect */
  button {
    transition: color 0.3s ease-in-out;
  }
  
  button:hover {
    color: #16a34a;
  }
  </style>
  