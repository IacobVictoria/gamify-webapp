<template>
  <section class="flex flex-col items-center text-center my-16 mt-32">
    <!-- Titlu -->
    <h2
      class="text-3xl font-extrabold text-gray-900 bg-gradient-to-r from-green-600 to-teal-500 bg-clip-text text-transparent">
      🚀 Unlock Your Rewards!
    </h2>
    <p class="mt-2 text-gray-700 text-lg mb-16 ">Earn exclusive badges & perks as you progress. Sign up and start
      achieving today! 🎖️</p>

    <!-- Progress Bar -->
    <div class="relative mt-10 w-10/12 md:w-7/12 lg:w-6/12">
      <!-- Background Bar -->
      <div class="overflow-hidden rounded-full bg-gray-200">
        <div class="h-3 rounded-full bg-gradient-to-r from-orange-400 to-yellow-500 transition-all duration-700"
          :style="{ width: progress + '%' }">
        </div>
      </div>

      <!-- Milestone Bubbles -->
      <div class="absolute top-[-30px] flex justify-between w-full text-sm font-medium text-gray-600">
        <span class="text-green-600">Sign Up 🎉</span>
        <span class="text-orange-500 text-center relative">Bronze 🥉
          <span
            class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs shadow-md">
            +100 XP
          </span>
        </span>
        <span class="text-blue-500 text-center relative">
          Silver 🥈
          <span
            class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs shadow-md">
            +100 XP
          </span>
        </span>
        <span class="text-yellow-500 text-right relative">
          Gold 🥇
          <span
            class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs shadow-md">
            +250 XP
          </span>
        </span>
      </div>
    </div>

    <!-- CTA Button -->

    <div v-if="isLoggedIn()"
      class="mt-8 px-6 py-3 no-underline font-semibold text-white bg-green-600 rounded-lg shadow-md transition-transform duration-300 hover:scale-105 hover:bg-green-700">
      <div v-if="authUserHasRole('User')">
        <inertia-link :href="route('user.dashboard')" class="no-underline font-semibold text-white">
          Let's play! Enter your dashboard!
        </inertia-link>
      </div>
      <div v-if="authUserHasRole('Admin') || authUserHasRole('Admin-Gamification')">
        <inertia-link :href="route('admin.dashboard')" class="no-underline font-semibold text-white">
          Let's play! Enter your dashboard!
        </inertia-link>
      </div>
    </div>
    <div v-else
      class="mt-8 px-6 py-3 no-underline font-semibold text-white bg-green-600 rounded-lg shadow-md transition-transform duration-300 hover:scale-105 hover:bg-green-700">
      <inertia-link :href="route('login')" class="no-underline font-semibold text-white">
        Create an Account Now!
      </inertia-link>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const progress = ref(25); // 25% progres

</script>