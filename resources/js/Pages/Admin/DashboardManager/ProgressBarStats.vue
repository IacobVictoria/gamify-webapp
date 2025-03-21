<template>
  <div class="bg-white p-5">
    <h2 class="text-xl font-semibold text-gray-900 mb-5">📊 Weekly Progress Stats</h2>

    <div v-if="progressBarStats">
      <!-- Promo Orders -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Orders with Promo Codes this week</span>
          <span class="text-gray-500">{{ progressBarStats.promoOrdersPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div 
            class="h-3 rounded-md transition-all bg-green-500" 
            :style="{ width: progressBarStats.promoOrdersPercentage ? progressBarStats.promoOrdersPercentage + '%' : '0%' }">
          </div>
        </div>
      </div>

      <!-- Average Badges per User -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Average Badges per User</span>
          <span class="text-gray-500">{{ progressBarStats.averageBadgesPerUser ? progressBarStats.averageBadgesPerUser.toFixed(1) : 0 }}</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div 
            class="h-3 rounded-md transition-all bg-blue-500" 
            :style="{ width: progressBarStats.badgesPercentage ? progressBarStats.badgesPercentage + '%' : '0%' }">
          </div>
        </div>
      </div>

      <!-- Badge Collection Progress -->
      <div>
        <div class="flex justify-between text-sm font-medium">
          <span>Badge Collection Progress</span>
          <span class="text-gray-500">{{ progressBarStats.badgesPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div 
            class="h-3 rounded-md transition-all bg-purple-500" 
            :style="{ width: progressBarStats.badgesPercentage ? progressBarStats.badgesPercentage + '%' : '0%' }">
          </div>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 text-sm text-center">Loading progress data...</p>
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
