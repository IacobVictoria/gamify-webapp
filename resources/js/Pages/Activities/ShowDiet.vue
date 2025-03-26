<template>
    <div class="p-6 bg-green-50 rounded-xl shadow mb-12">
      <h1 class="text-3xl font-bold text-green-900 mb-2">
        🥗 {{ activity.title }}
      </h1>
      <p class="text-gray-700 italic mb-4">{{ activity.description }}</p>
  
      <h2 class="font-semibold text-lg text-green-800 mb-1">Recommendations</h2>
      <p class="mb-4">{{ activity.details.recommendations }}</p>
  
      <h2 class="font-semibold text-lg text-green-800 mb-1">Snacks</h2>
      <ul class="list-disc pl-6 mb-4 text-green-900">
        <li v-for="id in activity.details.snacks" :key="id">Product ID: {{ id }}</li>
      </ul>
  
      <h2 class="font-semibold text-lg text-green-800 mb-1">Nutrition Summary</h2>
      <ul class="text-gray-700">
        <li>✨ Calories: <strong>{{ activity.details.total_nutrition?.calories }}</strong></li>
        <li>🌟 Protein: <strong>{{ activity.details.total_nutrition?.protein }}</strong>g</li>
        <li>🌾 Carbs: <strong>{{ activity.details.total_nutrition?.carbs }}</strong>g</li>
        <li>🧈 Fats: <strong>{{ activity.details.total_nutrition?.fats }}</strong>g</li>
      </ul>
    </div>
  </template>
  
  <script setup>
  defineProps({ activity: Object });
  </script>