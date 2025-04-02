<template>
  <div class="bg-white p-5">
    <h2 class="text-xl font-semibold text-gray-900 mb-5">
      📊 Weekly Progress Stats
    </h2>

    <div v-if="progressBarStats">
      <!-- Orders with Promo Codes -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Orders with Promo Codes this week</span>
          <span class="text-gray-500">{{ progressBarStats.promoOrdersPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-green-500"
            :style="{ width: progressBarStats.promoOrdersPercentage ? progressBarStats.promoOrdersPercentage + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Discount Usage Rate -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Discount Usage Rate</span>
          <span class="text-gray-500">{{ progressBarStats.discountUsageRate }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-purple-500"
            :style="{ width: progressBarStats.discountUsageRate ? progressBarStats.discountUsageRate + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Products Added to Wishlist -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Products Added to Wishlist</span>
          <span class="text-gray-500">{{ progressBarStats.wishlistedPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-blue-500"
            :style="{ width: progressBarStats.wishlistedPercentage ? progressBarStats.wishlistedPercentage + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Replenishment Rate -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Healthy Replenishment Rate</span>
          <span class="text-gray-500">{{ progressBarStats.replenishmentRate }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-yellow-500"
            :style="{ width: progressBarStats.replenishmentRate ? progressBarStats.replenishmentRate + '%' : '0%' }"
          ></div>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 text-sm text-center">
      Loading progress data...
    </p>
  </div>
</template>

<script>
export default {
  props: {
    progressBarStats: {
      type: Object,
      default: () => ({}),
    },
  },
};
</script>
