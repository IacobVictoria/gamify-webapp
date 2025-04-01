<template>
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-4 mr-4">
      <!-- Badges Earned This Week -->
      <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-yellow-400 to-orange-500 shadow-xl rounded-2xl text-center">
        <h3 class="text-white font-semibold text-xl mb-2">Badges Earned</h3>
        <p class="text-white text-4xl font-extrabold mb-1">{{ weeklyBadges }}</p>
      </div>
  
      <!-- Medals Earned This Week -->
      <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-green-400 to-emerald-500 shadow-xl rounded-2xl text-center">
        <h3 class="text-white font-semibold text-xl mb-2">Medals Earned</h3>
        <p class="text-white text-4xl font-extrabold mb-1">{{ weeklyMedals }}</p>
      </div>
  
      <!-- Avg Quiz Score -->
      <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-blue-400 to-indigo-600 shadow-xl rounded-2xl text-center">
        <h3 class="text-white font-semibold text-xl mb-2">Avg Quiz Score</h3>
        <p class="text-white text-4xl font-extrabold mb-1">{{ avgQuizScore }}%</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      weeklyBadges: Number,
      weeklyMedals: Number,
      avgQuizScore: Number,
    },
  };
  </script>