<template>
    <div class="badge-section-container">
      <h2 class="section-title">Your Badges</h2>
      <div><inertia-link :href="route('user.badge.instructions')">Set instructiuni atingere insigne </inertia-link></div>
      <div v-for="(category, index) in categorizedBadges" :key="index" class="badge-category">
        <h3 class="category-title">{{ category.label }}</h3>
        <div class="grid grid-cols-4 gap-4">
          <!-- Afișăm badge-urile owned mai întâi -->
          <div v-for="(badge, badgeIndex) in category.ownedBadges" :key="badgeIndex" class="badge-container">
            <img :src="badge.image_path" alt="Badge" :class="['w-20 h-20', badge.owned ? '' : 'grayscale']"
              class="badge-image" />
            <div class="text-center">
              <p class="badge-name">{{ badge.name }}</p>
              <p class="badge-score">{{ badge.score }} <span>pt</span></p>
            </div>
          </div>
  
          <!-- Afișăm badge-urile non-owned ulterior -->
          <div v-for="(badge, badgeIndex) in category.nonOwnedBadges" :key="badgeIndex" class="badge-container">
            <img :src="badge.image_path" alt="Badge" :class="['w-20 h-20', badge.owned ? '' : 'grayscale']"
              class="badge-image" />
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
            badges: [],
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
  
  .badge-description {
    font-size: 0.875rem;
    color: #777;
    margin-top: 5px;
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
  