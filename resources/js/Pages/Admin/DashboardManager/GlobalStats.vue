<template>
  <div class="bg-white p-6 mb-10">
   <h2 class="text-2xl font-semibold text-gray-800 mb-6">🌐 Perspective Globale</h2>

    <ul class="space-y-5">
      <li class="flex items-center gap-4">
        <div class="bg-green-100 text-green-600 p-3 rounded-full">
          📝
        </div>
        <div>
          <p class="text-lg font-bold text-gray-800">{{ globalStats.avgReviewsPerUser.toFixed(2) }}</p>
          <p class="text-sm text-gray-500">Media recenziilor per utilizator</p>
        </div>
      </li>

      <li class="flex items-center gap-4">
        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
          💸
        </div>
        <div>
          <p class="text-lg font-bold text-gray-800">{{ globalStats.ordersWithDiscount }}</p>
          <p class="text-sm text-gray-500">Comenzi cu cod de reducere</p>
        </div>
      </li>

      <li class="flex items-center gap-4">
        <div class="bg-red-100 text-red-600 p-3 rounded-full">
          🚫
        </div>
        <div>
          <p class="text-lg font-bold text-gray-800">{{ globalStats.ordersWithoutDiscount }}</p>
          <p class="text-sm text-gray-500">Comenzi fără cod de reducere</p>
        </div>
      </li>

      <li class="flex items-center gap-4">
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
          ✅
        </div>
        <div>
          <p class="text-lg font-bold text-gray-800">{{ globalStats.usersWithAllDiscountsUsed }}</p>
          <p class="text-sm text-gray-500">Utilizatori care au folosit toate promoțiile</p>
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
