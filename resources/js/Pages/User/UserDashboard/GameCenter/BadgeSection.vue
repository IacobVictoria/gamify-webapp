<template>
  <div class="badge-section-container">
    <h2 class="section-title">Your Badges</h2>

    <div v-for="(category, index) in categorizedBadges" :key="index" class="badge-category">
      <h3 class="category-title" :style="{ background: getCategoryColor(index) }">
        {{ category.label }}
      </h3>

      <div class="grid grid-cols-4 gap-4">
        <!-- Afișăm badge-urile owned -->
        <div v-for="(badge, badgeIndex) in category.ownedBadges" :key="badgeIndex" class="badge-container owned"
          @click="openBadgePopup(badge)">
          <img :src="badge.image_path" alt="Badge" class="badge-image" />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }} <span>pt</span></p>
          </div>
        </div>

        <!-- Afișăm badge-urile non-owned -->
        <div v-for="(badge, badgeIndex) in category.nonOwnedBadges" :key="badgeIndex" class="badge-container non-owned"
          @click="openBadgePopup(badge)">
          <img :src="badge.image_path" alt="Badge" class="badge-image grayscale" />
          <div class="text-center">
            <p class="badge-name">{{ badge.name }}</p>
            <p class="badge-score">{{ badge.score }} <span>pt</span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Popup pentru detalii badge -->
    <div v-if="selectedBadge" class="popup-overlay" @click="closePopup">
      <div class="popup-content" @click.stop>
        <button class="close-btn" @click="closePopup">&times;</button>
        <h3 class="popup-title">{{ selectedBadge.name }}</h3>
        <img :src="imagePath('/user_dashboard/user-guide.png')" class="user-guide-img" />
        <p class="popup-description">{{ selectedBadge.description }}</p>
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
  data() {
    return {
      selectedBadge: null,
    };
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
    openBadgePopup(badge) {
      this.selectedBadge = badge;
    },
    closePopup() {
      this.selectedBadge = null;
    },
    getCategoryColor(index) {
      const colors = [
        "linear-gradient(90deg, #4F46E5, #00C6FF)", // Albastru
        "linear-gradient(90deg, #FF6B6B, #FFA502)", // Roșu - Portocaliu
        "linear-gradient(90deg, #1DD1A1, #10AC84)", // Verde
        "linear-gradient(90deg, #EE82EE, #C71585)", // Mov
      ];
      return colors[index % colors.length];
    },
  }
};
</script>
<style>
/* 🌟 Secțiunea principală */
.badge-section-container {
  text-align: center;
  padding: 20px;
}

/* 🌟 Titlul principal */
.section-title {
  font-size: 2.2rem;
  font-weight: bold;
  text-transform: uppercase;
  background: linear-gradient(90deg, #4F46E5, #00C6FF);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 1.5px;
  margin-bottom: 30px;
  display: inline-block;
  padding-bottom: 5px;
}

/* 🌟 Titlurile categoriilor */
.category-title {
  font-size: 1.6rem;
  font-weight: bold;
  text-align: left;
  letter-spacing: 1px;
  padding: 10px 15px;
  color: white;
  border-radius: 8px;
  margin-bottom: 2em;
  margin-top: 2em;
 width: 10em;
}

/* 🌟 Efect de hover pe titlurile categoriilor */
.category-title:hover {
  opacity: 0.85;
  transform: scale(1.02);
  transition: all 0.3s ease-in-out;
}

/* 🌟 Container pentru insigne */
.badge-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 15px;
  border-radius: 12px;
  background-color: #f8f9fa;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
  cursor: pointer;
}

.badge-container:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* 🌟 Imagine insigne */
.badge-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  margin-bottom: 10px;
  border-radius: 50%;
}

/* 🌟 Stilurile pentru nume și scor */
.badge-name {
  font-weight: bold;
  color: #333;
}

.badge-score {
  font-size: 0.875rem;
  color: #555;
}

/* 🌟 POPUP DESIGN */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-width: 400px;
  width: 90%;
  position: relative;
  animation: fadeIn 0.3s ease-in-out;
}

/* 🌟 Animație popup */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }

  to {
    opacity: 1;
    transform: scale(1);
  }
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  background: none;
  border: none;
  cursor: pointer;
}

/* 🌟 Stiluri pentru imaginea de user guide */
.user-guide-img {
  width: 100px;
  margin: 10px auto;
  display: block;
}
</style>