<template>
  <div class="p-6 bg-white rounded-lg shadow-md">
    <h2
      class="text-3xl mb-12 font-extrabold text-gray-900 bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
      <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12l2 2 4-4M3 12a9 9 0 1118 0A9 9 0 013 12z" />
      </svg>
      <span>Track Your Ingoing Orders</span>
    </h2>

    <div v-if="activeOrders.length > 0">
      <div v-for="order in activeOrders" :key="order.id"
        class="border rounded-lg p-4 mb-4 shadow-sm flex items-center justify-between">

        <!-- Order ID & Date -->
        <div>
          <p class="text-gray-600 text-sm">Order ID: <strong class="text-gray-900">{{ order.id }}</strong></p>
          <p class="text-gray-600 text-sm">Placed on: <strong class="text-gray-900">{{ order.date }}</strong></p>
        </div>

        <!-- Order Status & Image -->
        <div class="flex items-center space-x-4">
          <span :class="statusClasses(order.status)">
            {{ getStatusMessage(order) }}
          </span>
          <img :src="getStatusImage(order.status)" alt="Status Icon" class="w-8 h-8" />
        </div>

      </div>
    </div>

    <div v-else class="text-center text-gray-500 mt-4">
      <p>No active orders found.</p>
    </div>
  </div>
</template>

<script>
import { usePage } from '@inertiajs/vue3';

export default {
  props: {
    activeOrders: {
      type: Array,
      required: true,
    },
  },
  beforeMount() {

    const { user } = usePage().props;
    // Ascultă evenimentul OrderExpedited și actualizează statusul
    window.Echo.private(`user.${user.id}`).listen(".OrderExpedited", (event) => {
      const order = this.activeOrders.find(order => order.id === event.order_id);
      if (order) {
        order.status = event.order_status;
      }
    });

    // Ascultă evenimentul OrderDelivered și actualizează statusul
    window.Echo.private(`user.${user.id}`).listen(".OrderDelivered", (event) => {
      const order = this.activeOrders.find(order => order.id === event.order_id);
      if (order) {
        order.status = event.order_status;
      }
    });
  }
  ,
  methods: {
    getStatusMessage(order) {
      switch (order.status) {
        case "Waiting":
          return "⏳ Waiting for it to be expedited...";
        case "Expedited":
          return "🚚 Expedited - Waiting 3 days for delivery...";
        case "Delivered":
          return "✅ Order Delivered!";
        default:
          return "⏳ Processing...";
      }
    },
    statusClasses(status) {
      switch (status) {
        case "Waiting":
          return "text-yellow-500 font-semibold";
        case "Expedited":
          return "text-blue-500 font-semibold";
        case "Delivered":
          return "text-green-500 font-semibold";
        default:
          return "text-gray-500 font-semibold";
      }
    },
    getStatusImage(status) {
      switch (status) {
        case "Waiting":
          return "/images/orders/order.png";
        case "Expedited":
          return "/images/orders/expedited.png";
        case "Delivered":
          return "/images/orders/delivered.png";
        default:
          return "/images/orders/order.png";
      }
    }
  }
};
</script>

<style scoped>
/* Stiluri suplimentare dacă sunt necesare */
</style>
