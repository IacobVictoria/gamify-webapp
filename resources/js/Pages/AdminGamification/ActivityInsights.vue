<template>
    <div class="bg-white p-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">🎯 Activities Insights</h2>
  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Total Activities -->
        <div class="p-4 rounded-lg shadow border-l-4 border-blue-500 bg-blue-50">
          <div class="text-blue-600 text-2xl font-bold">{{ stats.totalActivities }}</div>
          <div class="text-sm text-gray-600">Total Activities</div>
        </div>
  
        <!-- Participants This Week -->
        <div class="p-4 rounded-lg shadow border-l-4 border-green-500 bg-green-50">
          <div class="text-green-600 text-2xl font-bold">{{ stats.totalParticipantsThisWeek }}</div>
          <div class="text-sm text-gray-600">Participants This Week</div>
        </div>
  
        <!-- Average Score -->
        <div class="p-4 rounded-lg shadow border-l-4 border-indigo-500 bg-indigo-50">
          <div class="text-indigo-600 text-2xl font-bold">{{ stats.averageScorePerActivity }}</div>
          <div class="text-sm text-gray-600">Average Score per Activity</div>
        </div>
  
        <!-- Most Popular Activity -->
        <div v-if="stats.mostPopularActivity" class="p-4 rounded-lg shadow border-l-4 border-yellow-500 bg-yellow-50">
          <div class="text-yellow-700 font-semibold">
            {{ stats.mostPopularActivity.title }} ({{ stats.mostPopularActivity.type }})
          </div>
          <div class="text-sm text-gray-600">
            Score: {{ stats.mostPopularActivity.score }} · Participants: {{ stats.mostPopularActivity.participants }}
          </div>
        </div>
  
        <!-- Most Popular Type -->
        <div class="p-4 rounded-lg shadow border-l-4 border-pink-500 bg-pink-50">
          <div class="text-pink-600 text-xl font-bold">{{ stats.mostPopularType }}</div>
          <div class="text-sm text-gray-600">Most Popular Type of Activity</div>
        </div>
  
        <!-- Total Favorites -->
        <div class="p-4 rounded-lg shadow border-l-4 border-red-500 bg-red-50">
          <div class="text-red-600 text-2xl font-bold">{{ stats.totalFavorites }}</div>
          <div class="text-sm text-gray-600">Total Favorites (Saved)</div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    stats: Object
  });
  </script>
  