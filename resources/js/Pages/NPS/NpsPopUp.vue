<template>
  <div
    v-if="isVisible"
    class="fixed bottom-4 right-4 z-50 p-4 bg-white shadow-lg rounded-lg flex items-center space-x-4"
  >
    <div>
      <p class="text-lg font-semibold">Give us feedback!</p>
      <p class="text-sm text-gray-500">We'd love to hear your thoughts.</p>
    </div>
    <div>
      <button
        @click="handleGiveFeedback"
        class="bg-blue-500 text-white px-3 py-2 rounded-lg shadow hover:bg-blue-600"
      >
        Give Feedback
      </button>
    </div>
    <button
      @click="closePopup"
      class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
    >
      ✕
    </button>
  </div>
</template>

<script>

export default {
  name: "NpsPopup",
  data() {
    return {
      isVisible: true,
    };
  },
  methods: {
    handleGiveFeedback() {
      // Navighează la ruta specificată (fără reîncărcare completă a aplicației)
      this.navigateTo('/nps/form');  // Înlocuiește cu ruta ta exactă
      this.isVisible = false; // Ascunde popup-ul
    },
    navigateTo(route) {
      // Navighează la ruta specificată
      window.location.href = route; // Schimbă locația paginii
    },
    closePopup() {
      this.isVisible = false;
    },
  },
};

</script>

<style scoped>
/* Stiluri pentru popup */
</style>
