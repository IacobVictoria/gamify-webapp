<template>
  <div class="badge-section-container">
    <h2 class="section-title">Your Badges</h2>
    <div v-for="(category, index) in categorizedBadges" :key="index" class="badge-category">
      <h3 class="category-title">{{ category.label }}</h3>
      <div class="grid grid-cols-4 gap-4">
        <!-- Afișăm badge-urile owned mai întâi -->
        <div v-for="(badge, badgeIndex) in category.ownedBadges" :key="badgeIndex" class="badge-container owned">
          <img :src="badge.image_path" alt="Badge" class="badge-image" />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }} <span>pt</span></p>
          </div>
        </div>

        <!-- Afișăm badge-urile non-owned ulterior -->
        <div v-for="(badge, badgeIndex) in category.nonOwnedBadges" :key="badgeIndex" class="badge-container non-owned">
          <img :src="badge.image_path" alt="Badge" class="badge-image grayscale" />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }} <span>pt</span></p>
          </div>
        </div>
      </div>
      
      <!-- Secțiunea de instrucțiuni pentru obținerea insignelor -->
      <div class="instructions" :style="{ backgroundColor: getPastelColor(index) }">
        <h4 class="instructions-title">Cum poți obține aceste insigne?</h4>
        <img :src="imagePath('/user_dashboard/user-guide.png')" class="w-24" />
        <ul class="instructions-list">
          <li v-for="(badge, badgeIndex) in category.allBadges" :key="badgeIndex" class="instruction-item">
            <strong>{{ badge.name }}:</strong> {{ badge.description }}
          </li>
        </ul>
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
          allBadges: [],
        };
      });

      this.badges.forEach(badge => {
        const categoryValue = badge.category;
        if (categoriesMap[categoryValue]) {
          categoriesMap[categoryValue].allBadges.push(badge);
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
  methods: {
    getPastelColor(index) {
      const pastelColors = ["#FFDDC1", "#FFABAB", "#FFC3A0", "#D5AAFF", "#85E3FF", "#B9FBC0"];
      return pastelColors[index % pastelColors.length];
    }
  }
};
</script>

<style scoped>
.grayscale {
  filter: grayscale(100%);
}

.badge-section-container {
  text-align: center;
  padding: 20px;
}

.section-title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 20px;
}

.badge-category {
  margin-bottom: 40px;
}

.category-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #444;
  margin-bottom: 10px;
}

.instructions {
  margin-top: 20px;
  padding: 15px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

.instructions-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #555;
  margin-bottom: 10px;
}

.instructions-list {
  list-style-type: none;
  padding: 0;
}

.instruction-item {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 5px;
}

.badge-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 15px;
  border-radius: 10px;
  background-color: #f8f9fa;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.badge-container:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.badge-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  margin-bottom: 10px;
  border-radius: 50%;
}

.badge-name {
  font-weight: bold;
  color: #333;
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