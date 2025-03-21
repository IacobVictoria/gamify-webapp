<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-4 mr-4">
    <!-- Weekly Sales -->
    <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-pink-400 to-red-500 shadow-xl rounded-2xl text-center">
      <h3 class="text-white font-semibold text-xl mb-2">Weekly Sales</h3>
      <p class="text-white text-4xl font-extrabold mb-1">${{ weeklySales }}</p>
      <p class="text-white text-sm">
        <span v-if="salesChange >= 0" class="text-green-200">↑ {{ salesChange }}%</span>
        <span v-else class="text-red-200">↓ {{ Math.abs(salesChange) }}%</span>
      </p>
    </div>

    <!-- Weekly Orders -->
    <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-blue-400 to-blue-600 shadow-xl rounded-2xl text-center">
      <h3 class="text-white font-semibold text-xl mb-2">Weekly Orders</h3>
      <p class="text-white text-4xl font-extrabold mb-1">{{ weeklyOrders }}</p>
      <p class="text-white text-sm">
        <span v-if="ordersChange >= 0" class="text-green-200">↑ {{ ordersChange }}%</span>
        <span v-else class="text-red-200">↓ {{ Math.abs(ordersChange) }}%</span>
      </p>
    </div>

    <!-- New Visitors -->
    <div class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-purple-500 to-indigo-500 shadow-xl rounded-2xl text-center">
      <h3 class="text-white font-semibold text-xl mb-2">Weekly New Visitors</h3>
      <p class="text-white text-4xl font-extrabold mb-1">{{ newVisitors }}</p>
      <p class="text-white text-sm">
        <span v-if="newVisitorsChange >= 0" class="text-green-200">↑ {{ newVisitorsChange }}%</span>
        <span v-else class="text-red-200">↓ {{ Math.abs(newVisitorsChange) }}%</span>
      </p>
    </div>
  </div>
</template>

  
  <script>
  export default {

    props: {
        weeklySales:Number,
        weeklyOrders:Number,
        newVisitors:Number,
        salesChange:Number,
        ordersChange:Number,
        newVisitorsChange:Number
    },
  };
  </script>
  