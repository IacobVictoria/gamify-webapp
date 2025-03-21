<template>
    <div class="p-4 mt-12">
      <!-- Titlu + Butoane Scroll -->
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-700">Top Selling Products (Last 7 Days)</h2>
        <div class="flex space-x-1">
          <button @click="scrollLeft" class="text-gray-600 hover:text-gray-900">
            ←
          </button>
          <button @click="scrollRight" class="text-gray-600 hover:text-gray-900">
            →
          </button>
        </div>
      </div>
  
      <!-- Carousel -->
      <div ref="carousel" class="flex space-x-4 overflow-x-auto scrollbar-hide">
        <div v-for="(product, index) in topProductsWeekly" :key="index"
          class="min-w-[180px] border border-gray-200 rounded-md bg-white p-3 flex flex-col items-center">
          
          <img :src="product.image" alt="Product Image"
            class="w-32 h-32 object-cover rounded-md mb-2" />
          
          <h3 class="text-sm font-medium text-gray-800 text-center">{{ product.product_name }}</h3>
          <p class="text-xs text-gray-500">{{ product.total_sold }} Pcs</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const props = defineProps({
    topProductsWeekly: Array
  });
  
  const carousel = ref(null);

  // Funcție pentru scroll stânga
  const scrollLeft = () => {
    if (carousel.value) {
      carousel.value.scrollBy({ left: -200, behavior: "smooth" });
    }
  };
  
  // Funcție pentru scroll dreapta
  const scrollRight = () => {
    if (carousel.value) {
      carousel.value.scrollBy({ left: 200, behavior: "smooth" });
    }
  };
  </script>
  
  <style>
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  </style>
  