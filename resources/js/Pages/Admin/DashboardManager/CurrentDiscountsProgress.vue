<template>
    <div class="bg-white p-5">
      <h2 class="text-xl font-semibold text-gray-900 mb-5">🔥 Active Discounts</h2>
  
      <div v-if="currentDiscounts.length > 0">
        <div v-for="(discount, index) in currentDiscounts" :key="index" 
             class="p-4 bg-gray-50 rounded-lg shadow-sm mb-4">
  
          <div class="flex justify-between items-center text-md font-medium text-gray-800 mb-1">
            <span class="font-semibold">{{ discount.title }}</span>
            <span class="text-sm text-gray-500">Expires: {{ discount.expire_date }}</span>
          </div>
  
          <div class="flex justify-between items-center text-gray-600 text-sm mb-2">
            <span class="text-blue-600 font-semibold">{{ discount.percentage }}% Off</span>
            <span v-if="discount.applyTo === 'categories'" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
              {{ discount.category }}
            </span>
          </div>
  
          <div class="w-full h-4 bg-gray-200 rounded-full">
            <div 
              class="h-full rounded-full  bg-gradient-to-r from-blue-400 to-blue-600"
              :style="{ width: discount.progress + '%' }">
            </div>
          </div>
  
        </div>
      </div>
  
      <p v-else class="text-gray-500 text-sm text-center">🚫 No active discounts available.</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      currentDiscounts: Array
    }
  }
  </script>
  