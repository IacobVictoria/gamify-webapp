<template>
    <div>
  
      <ul v-if="recommendations.length">
        <li v-for="product in recommendations" :key="product.id">
          {{ product.name }} - {{ product.price }} RON
        </li>
      </ul>
      <p v-else>Nu sunt recomandări disponibile.</p>
      <p v-if="error">{{ error }}</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      recommendations: Array,
      error: String,
    },
    data() {
      return {
         // Obține ID-ul utilizatorului din rută
      };
    },
  };
  </script>
  
  <style scoped>
  /* Stiluri pentru componentă */
  </style>
  