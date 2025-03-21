<template>
    <div class="bg-white p-6 ">
      <h2 class="text-xl font-semibold text-gray-900 mb-5">📊 Weekly Insights</h2>
  
      <!-- Leaderboard -->
      <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-3">🏆 Top 5 Active Users</h3>
        <div class="flex justify-around gap-4">
          <div v-for="(user, index) in variousStats.topUsers" :key="user.id"
            class="flex flex-col items-center rounded-xl shadow-md p-3 w-24"
            :class="{ 'border-2 border-yellow-500 scale-110': index === 0 }">
            <p :class="getRankingColor(index)" class="text-lg font-bold">#{{ index + 1 }}</p>
            <img :src="getAvatar(user)" alt="User Avatar" class="w-14 h-14 rounded-full mt-2 mb-1" />
            <p class="text-xs font-semibold text-gray-800 text-center">{{ user.name }}</p>
            <p class="text-xs text-gray-500">{{ user.score }} pts</p>
          </div>
        </div>
      </div>
  
      <!-- Weekly QR Scans -->
      <div class="mb-6 flex items-center bg-gray-150 p-4 rounded-lg shadow-xl">
        <img :src="imagePath('qr-code.png')" alt="QR Scan Icon" class="w-16 h-16 mr-3" />
        <div>
          <p class="text-lg font-bold text-gray-800">{{ variousStats.weeklyQrScans }}</p>
          <p class="text-sm text-gray-500">QR Scans This Week</p>
        </div>
      </div>
  
      <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-3">💖 Most Wishlisted Products</h3>
      <div  class="flex space-x-4 overflow-x-auto scrollbar-hide">
        <div v-for="(product, index) in variousStats.topWishlistProducts" :key="index"
          class="min-w-[180px] border border-gray-200 rounded-md bg-white p-3 flex flex-col items-center">
          
          <img :src="product.image_url || defaultImage" alt="Product Image"
            class="w-32 h-32 object-cover rounded-md mb-2" />
          
          <h3 class="text-sm font-medium text-gray-800 text-center">{{ product.name }}</h3>
          <p class="text-xs text-gray-500">Wishlisted {{ product.wishlists_count }} times</p>
        </div>
      </div>
    </div>
   <!-- Conversion Rate -->
<div class="text-center bg-green-100 p-4 rounded-lg shadow">
  <p class="text-lg font-bold text-green-700">{{ variousStats.conversionRate }}%</p>
  <p class="text-sm text-gray-500 font-semibold">User Conversion Rate</p>
  <p class="text-xs text-gray-600 mt-1">
    Din 100 de utilizatori care și-au creat cont săptămâna aceasta, 
    {{ variousStats.conversionRate }} au plasat o comandă.
  </p>
</div>

    </div>
  </template>
  
  <script>
  export default {
    props: {
        variousStats: Object,
    },
    methods: {
      getAvatar(user) {
        return user.gender === 'Male' ? '/images/male.png' : '/images/female.png';
      },
      getRankingColor(index) {
        return index === 0 ? 'text-yellow-500' : index === 1 ? 'text-gray-600' : 'text-brown-500';
      },
    }
  };
  </script>
  
  <style scoped>
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  </style>
  