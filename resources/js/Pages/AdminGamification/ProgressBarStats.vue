<template>
    <div class="bg-white p-5 ">
      <h2 class="text-xl font-semibold text-gray-900 mb-5">📊 Gamification Progress</h2>
  
      <div v-if="progressBarStats">
        <!-- Average Badges per User -->
        <div class="mb-4">
          <div class="flex justify-between text-sm font-medium">
            <span>Average Badges per User</span>
            <span class="text-gray-500">{{ progressBarStats.averageBadgesPerUser.toFixed(1) }}</span>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
            <div
              class="h-3 rounded-md transition-all bg-blue-500"
              :style="{ width: progressBarStats.badgesPercentage + '%' }"
            ></div>
          </div>
        </div>
  
        <!-- Badge Category Progress -->
        <div class="mb-4">
          <div class="flex justify-between text-sm font-medium">
            <span>Avg. Badge Completion per Category</span>
            <span class="text-gray-500">{{ progressBarStats.categoryBadgeProgress }}%</span>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
            <div
              class="h-3 rounded-md transition-all bg-purple-500"
              :style="{ width: progressBarStats.categoryBadgeProgress + '%' }"
            ></div>
          </div>
        </div>
  
        <!-- Hangman Completion Rate -->
        <div class="mb-4">
          <div class="flex justify-between text-sm font-medium">
            <span>Hangman Completion Rate</span>
            <span class="text-gray-500">{{ progressBarStats.hangmanCompletionRate }}%</span>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
            <div
              class="h-3 rounded-md transition-all bg-green-500"
              :style="{ width: progressBarStats.hangmanCompletionRate + '%' }"
            ></div>
          </div>
        </div>
  
        <!-- Quiz Retry Efficiency (inverted %) -->
        <div class="mb-4">
          <div class="flex justify-between text-sm font-medium">
            <span>Quiz Retry Efficiency</span>
            <span class="text-gray-500">{{ progressBarStats.quizRetryRate }}%</span>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
            <div
              class="h-3 rounded-md transition-all bg-yellow-400"
              :style="{ width: progressBarStats.quizRetryRate + '%' }"
            ></div>
          </div>
        </div>
  
        <!-- Quiz Success Rate (80%+) -->
        <div class="mb-1">
          <div class="flex justify-between text-sm font-medium">
            <span>Quiz Success Rate (80%+)</span>
            <span class="text-gray-500">{{ progressBarStats.quizSuccessRate }}%</span>
          </div>
          <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
            <div
              class="h-3 rounded-md transition-all bg-emerald-500"
              :style="{ width: progressBarStats.quizSuccessRate + '%' }"
            ></div>
          </div>
        </div>
      </div>
  
      <p v-else class="text-gray-500 text-sm text-center">Loading gamification stats...</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      progressBarStats: {
        type: Object,
        default: () => ({})
      }
    }
  }
  </script>
  