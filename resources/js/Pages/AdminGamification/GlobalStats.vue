<template>
    <div class="bg-white p-6 mb-10">
     <h2 class="text-2xl font-semibold text-gray-800 mb-6">🎯 Statistici gamificare</h2>
  
      <ul class="space-y-5">
        <!-- Average Quizzes Completed -->
        <li class="flex items-center gap-4">
          <div class="bg-blue-100 text-blue-600 p-3 rounded-full text-xl">📘</div>
          <div>
            <p class="text-lg font-bold text-gray-800">{{ globalStats.avgQuizzesCompleted.toFixed(1) }}</p>
            <p class="text-sm text-gray-500">Media quiz-uri finalizate per utilizator</p>
          </div>
        </li>
  
        <!-- Hangman Completion Rate -->
        <li class="flex items-center gap-4">
          <div class="bg-purple-100 text-purple-600 p-3 rounded-full text-xl">🎮</div>
          <div>
            <p class="text-lg font-bold text-gray-800">{{ globalStats.hangmanCompletionRate }}%</p>
            <p class="text-sm text-gray-500">Rata de finalizare a jocurilor Hangman</p>
          </div>
        </li>
  
        <!-- Quiz Success Rates -->
        <li class="flex items-center gap-4">
          <div class="bg-green-100 text-green-600 p-3 rounded-full text-xl">✅</div>
          <div>
            <p class="text-lg font-bold text-gray-800">
              {{ globalStats.quizSuccessRates.length }}
            </p>
            <p class="text-sm text-gray-500">Quiz-uri finalizate cu succes (scor &gt;= 80%)</p>
          </div>
        </li>
  
        <!-- Average Quiz Retries -->
        <li class="flex items-center gap-4">
          <div class="bg-orange-100 text-orange-600 p-3 rounded-full text-xl">🔁</div>
          <div>
            <p class="text-lg font-bold text-gray-800">{{ globalStats.avgQuizRetries.toFixed(2) }}</p>
            <p class="text-sm text-gray-500">Media reîncercărilor per quiz</p>
          </div>
        </li>
  
        <!-- Most Popular Difficulty (Top 1) -->
        <li class="flex items-center gap-4" v-if="globalStats.mostPopularDifficulties.length">
          <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full text-xl">📊</div>
          <div>
            <p class="text-lg font-bold text-gray-800">
              {{ globalStats.mostPopularDifficulties[0].difficulty }}
            </p>
            <p class="text-sm text-gray-500">Cel mai popular nivel de dificultate</p>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  defineProps({
    globalStats: Object
  });
  </script>
  