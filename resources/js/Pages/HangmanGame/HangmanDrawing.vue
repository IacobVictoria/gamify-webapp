<template>
    <div>
      <svg width="200" height="300" class="hangman-drawing">
        <!-- Structura spânzurătoarei -->
        <line x1="50" y1="250" x2="150" y2="250" stroke="black" stroke-width="3" /> <!-- Bază -->
        <line x1="100" y1="250" x2="100" y2="50" stroke="black" stroke-width="3" /> <!-- Stâlp -->
        <line x1="100" y1="50" x2="150" y2="50" stroke="black" stroke-width="3" /> <!-- Bară orizontală -->
        <line x1="150" y1="50" x2="150" y2="70" stroke="black" stroke-width="3" /> <!-- Sfoară -->
  
        <!-- Părți ale Hangman-ului -->
        <circle v-if="mistakes >= getThreshold(1)" cx="150" cy="90" r="20" stroke="black" fill="none" stroke-width="2" /> <!-- Cap -->
        <line v-if="mistakes >= getThreshold(2)" x1="150" y1="110" x2="150" y2="180" stroke="black" stroke-width="2" /> <!-- Corp -->
        <line v-if="mistakes >= getThreshold(3)" x1="150" y1="130" x2="130" y2="160" stroke="black" stroke-width="2" /> <!-- Braț stâng -->
        <line v-if="mistakes >= getThreshold(4)" x1="150" y1="130" x2="170" y2="160" stroke="black" stroke-width="2" /> <!-- Braț drept -->
        <line v-if="mistakes >= getThreshold(5)" x1="150" y1="180" x2="130" y2="220" stroke="black" stroke-width="2" /> <!-- Picior stâng -->
        <line v-if="mistakes >= getThreshold(6)" x1="150" y1="180" x2="170" y2="220" stroke="black" stroke-width="2" /> <!-- Picior drept -->
      </svg>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      mistakes: {
        type: Number,
        required: true,
      },
      maxMistakes: {
        type: Number,
        required: true, // nr maxim de greșeli
      },
    },
    methods: {
      getThreshold(partIndex) {
        //  pragul pentru fiecare parte a Hangman-ului
        return Math.ceil((this.maxMistakes / 6) * partIndex);
      },
    },
  };
  </script>
  
  <style scoped>
  .hangman-drawing {
    margin: 0 auto;
    display: block;
  }
  </style>
  