<template>
    <div class="space-y-4"> 
      <h3 class="text-base font-semibold text-gray-900 text-center">Select an Action</h3>
      
      <div class="flex flex-col space-y-2">
        <button
          @click="setSelectedType('order')"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
        >
          Add Command
        </button>
        <button
          @click="setSelectedType('discount')"
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500"
        >
          Add Discount
        </button>
      </div>

    </div>
  </template>
  
  <script setup>
  import { defineEmits } from 'vue'
  
  const emits = defineEmits(['setSelectedType', 'close'])
  
  function setSelectedType(type) {
    emits('setSelectedType', type)
  }
  
  </script>
  
  <style scoped>
  button {
    transition: background-color 0.3s ease;
  }
  
  button:hover {
    transform: scale(1.05);
  }
  </style>
  