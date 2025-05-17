<template>
    <div class="w-2/6 mx-auto bg-white rounded-2xl shadow-lg p-6 leaderboard-container">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Clasament</h2>
  
      <div class="flex justify-around gap-4">
        <div
          v-for="(user, index) in leaderboard"
          :key="user.user_id"
          :class="[
            'flex flex-col items-center rounded-xl shadow-md p-2 w-28',
            index === 0 ? 'border-2 border-yellow-500 h-55' : 'border border-gray-200 h-52',
            index === 0 ? 'transform scale-110 h-50' : '',
          ]">
          <p
            :class="{
              'text-yellow-500': index === 0,
              'text-gray-600': index === 1,
              'text-brown-500': index === 2,
            }"
            class="text-lg font-bold mb-2">
            #{{ index + 1 }}
          </p>
  
          <div class="flex flex-col items-center gap-2">
            <img
              :src="getAvatar(user)"
              alt="User Avatar"
              class="w-16 h-16 rounded-full mb-2"
            />
            <p class="text-sm font-semibold text-gray-800">{{ user.name }}</p>
            <p class="text-sm text-gray-500">{{ user.total_score }} puncte</p>
          </div>

        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      leaderboard: {
        type: Array,
        required: true,
      },
    },
    methods: {
      getAvatar(user) {
        return user.gender === 'Male' ? '/images/male.png' : '/images/female.png';
      },
    },
  };
  </script>
  
  <style scoped>
  .leaderboard-container {
    min-height: 20em;
  }

  .text-brown-500 {
    color: #a52a2a;
  }
  </style>
  