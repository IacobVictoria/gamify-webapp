<template>
    <div>
      <h2>Your Badges</h2>
      <ul>
        <li v-for="(badge, index) in badges" :key="index">
          <img :src="badge.image" alt="Badge" class="w-10 h-10" />
          {{ badge.name }}
        </li>
      </ul>
    </div>
  </template>
<!-- sa am pe categorii badges urile si sa am alea unlocked cu negru -->
  
  <script>
  export default {
    props: {
      badges: {
        type: Array,
        default: () => [],
      },
    },
  };
  </script>
  