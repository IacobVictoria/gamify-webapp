<template>
  <div>
    <h2>Your Badges</h2>
    <div v-for="(category, index) in categorizedBadges" :key="index">
      <h3 class="font-semibold">{{ category.label }}</h3>
      <div class="grid grid-cols-4 gap-4"> <!-- Grid pentru a alinia badge-urile pe rând -->
        <!-- Afișăm badge-urile owned mai întâi -->
        <div
          v-for="(badge, badgeIndex) in category.ownedBadges"
          :key="badgeIndex"
          class="badge-container"
        >
          <img
            :src="badge.image_path"
            alt="Badge"
            :class="['w-20 h-20', badge.owned ? '' : 'grayscale']"
            class="badge-image"
          />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }} <span>pt</span></p> <!-- Adăugăm scorul badge-ului -->
          </div>
        </div>

        <!-- Afișăm badge-urile non-owned ulterior -->
        <div
          v-for="(badge, badgeIndex) in category.nonOwnedBadges"
          :key="badgeIndex"
          class="badge-container"
        >
          <img
            :src="badge.image_path"
            alt="Badge"
            :class="['w-20 h-20', badge.owned ? '' : 'grayscale']"
            class="badge-image"
          />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }}<span>pt</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    badges: {
      type: Array,
      required: true,
    },
    categories: {
      type: Array,
      required: true,
    },
  },
  computed: {
    categorizedBadges() {
      const categoriesMap = {};

      this.categories.forEach(category => {
        categoriesMap[category.value] = {
          label: category.label,
          ownedBadges: [],
          nonOwnedBadges: [],
        };
      });

      this.badges.forEach(badge => {
        const categoryValue = badge.category;
        if (categoriesMap[categoryValue]) {
          if (badge.owned) {
            categoriesMap[categoryValue].ownedBadges.push(badge);
          } else {
            categoriesMap[categoryValue].nonOwnedBadges.push(badge);
          }
        }
      });

      return Object.values(categoriesMap);
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
