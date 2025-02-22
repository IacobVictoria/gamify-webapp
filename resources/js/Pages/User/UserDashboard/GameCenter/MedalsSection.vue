<template>
  <div>
    <h2>Your Medals</h2>
    <div class="grid grid-cols-4 gap-4"> <!-- Grid pentru a aranja medaliile pe linie -->
      <!-- Afișăm medaliile owned mai întâi -->
      <div
        v-for="(medal, medalIndex) in ownedMedals"
        :key="medalIndex"
        class="badge-container"
      >
        <img
          :src="imagePath(`/medals/${medal.tier}-medal.png`)"
          alt="Medal"
          :class="['w-20 h-20', medal.owned ? '' : 'grayscale']"
          class="badge-image"
        />
        <div class="text-center">
          <p class="badge-name">{{ medal.tier }}</p>
        </div>
      </div>

      <!-- Afișăm medaliile non-owned ulterior -->
      <div
        v-for="(medal, medalIndex) in nonOwnedMedals"
        :key="medalIndex"
        class="badge-container"
      >
        <img
          :src="imagePath(`/medals/${medal.tier}-medal.png`)"
          alt="Medal"
          :class="['w-20 h-20', medal.owned ? '' : 'grayscale']"
          class="badge-image"
        />
        <div class="text-center">
          <p class="badge-name">{{ medal.tier }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    medals: {
      type: Array,
      required: true,
    },
  },
  computed: {
    // Calculăm medaliile deținute și non-deținute
    ownedMedals() {
      return this.medals.filter((medal) => medal.owned);
    },
    nonOwnedMedals() {
      return this.medals.filter((medal) => !medal.owned);
    },
  },

};
</script>

<style scoped>
.grayscale {
  filter: grayscale(100%);
}

.badge-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.badge-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  margin-bottom: 5px;
}

.badge-name {
  font-weight: bold;
}

.badge-score {
  font-size: 0.875rem;
  color: #555;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 16px;
}
</style>
