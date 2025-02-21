<template>
    <div>
      <h2>Your Medals</h2>
      <ul>
        <li v-for="(medal, index) in medals" :key="index">
          <img :src="medal.image" alt="Medal" class="w-10 h-10" />
          {{ medal.name }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      medals: {
        type: Array,
        default: () => [],
      },
    },
  };
  </script>
  