<template>
  <div class="bg-white p-5">
    <h2 class="text-xl font-semibold text-gray-900 mb-5">
      📊 Statistici de progres lunare
    </h2>

    <div v-if="progressBarStats">
      <!-- Comenzi cu coduri promoționale -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Comenzi cu coduri promoționale</span>
          <span class="text-gray-500">{{ progressBarStats.promoOrdersPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-green-500"
            :style="{ width: progressBarStats.promoOrdersPercentage ? progressBarStats.promoOrdersPercentage + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Rata de utilizare a reducerilor -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Rata de utilizare a reducerilor</span>
          <span class="text-gray-500">{{ progressBarStats.discountUsageRate }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-purple-500"
            :style="{ width: progressBarStats.discountUsageRate ? progressBarStats.discountUsageRate + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Produse adăugate la favorite -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Produse adăugate la favorite</span>
          <span class="text-gray-500">{{ progressBarStats.wishlistedPercentage }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
          <div
            class="h-3 rounded-md transition-all bg-blue-500"
            :style="{ width: progressBarStats.wishlistedPercentage ? progressBarStats.wishlistedPercentage + '%' : '0%' }"
          ></div>
        </div>
      </div>

      <!-- Rata de reaprovizionare sănătoasă -->
      <div class="mb-4">
        <div class="flex justify-between text-sm font-medium">
          <span>Rată de reaprovizionare sănătoasă</span>
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
      Se încarcă datele de progres...
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
