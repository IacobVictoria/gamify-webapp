<template>
    <div
      class="relative p-6 rounded-xl shadow-xl flex flex-col items-center text-center transition-transform transform hover:scale-105"
      :class="discountStyle"
    >
      <!-- Badge Emoji -->
      <div class="text-5xl mb-2 animate-bounce">{{ getDiscountEmoji }}</div>
  
      <!-- Discount Title -->
      <h3 class="text-2xl font-extrabold text-white">{{ discount.title }}</h3>
  
      <!-- Description -->
      <p class="text-md text-white opacity-90 mt-2">{{ discount.description }}</p>
  
      <!-- Discount Details -->
      <p class="text-sm text-white mt-1">
        📅 {{ formatDate(discount.start) }} - {{ formatDate(discount.end) }}
      </p>
  
      <!-- Discount Application -->
      <div v-if="discount.details.applyTo === 'all'" class="mt-3 text-lg text-white font-semibold">
        💰 <span class="text-yellow-300">{{ discount.details.discount }}%</span> off on all products!
      </div>
  
      <div v-if="discount.details.applyTo === 'categories' && discount.details.category" class="mt-3 text-lg text-white font-semibold">
        🛒 {{ discount.details.discount }}% discount on <span class="text-yellow-300">{{ discount.details.category }}</span>!
      </div>
  
      <!-- Glow effect -->
      <div class="absolute inset-0 bg-white/10 rounded-xl opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
    </div>
  </template>
  
  <script setup>
  import { computed } from "vue";
  
  const props = defineProps({
    discount: Object,
  });
  
  const getDiscountEmoji = computed(() => {
    if (props.discount.details.discount >= 30) {
      return "🔥";
    } else if (props.discount.details.discount >= 20) {
      return "💥";
    }
    return "🎉";
  });
  
  const discountStyle = computed(() => {
    if (props.discount.details.discount >= 30) {
      return "bg-gradient-to-r from-red-500 to-orange-400 border-red-700";
    } else if (props.discount.details.discount >= 20) {
      return "bg-gradient-to-r from-purple-500 to-pink-500 border-purple-700";
    }
    return "bg-gradient-to-r from-blue-500 to-cyan-400 border-blue-700";
  });
  
  const formatDate = (date) => new Date(date).toLocaleDateString();
  </script>
  
  <style scoped>
  .shadow-xl {
    border: 3px solid;
    transition: box-shadow 0.3s ease-in-out;
  }
  
  .shadow-xl:hover {
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
  }
  </style>
  