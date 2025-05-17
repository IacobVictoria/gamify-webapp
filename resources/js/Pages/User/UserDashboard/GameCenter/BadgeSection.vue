<template>
    <div class="badge-section-container">
       <h2 class="section-title">🏆 Insignele tale grozave</h2>
        <div
            v-for="(category, index) in categorizedBadges"
            :key="index"
            class="badge-category"
        >
            <h3
                class="category-title"
                :style="{ background: getCategoryColor(index) }"
            >
                {{ category.label }}
            </h3>

            <div class="grid grid-cols-4 gap-4">
                <!-- Afișăm badge-urile owned -->
                <div
                    v-for="(badge, badgeIndex) in category.ownedBadges"
                    :key="badgeIndex"
                    class="badge-container owned"
                    @click="openBadgePopup(badge)"
                >
                    <img
                        :src="badge.image_path"
                        alt="Badge"
                        class="badge-image"
                    />
                    <div class="text-center">
                        <p class="badge-name">{{ badge.name }}</p>
                        <p class="badge-score">
                            {{ badge.score }} <span>pt</span>
                        </p>
                    </div>
                </div>

                <!-- Afișăm badge-urile non-owned -->
                <div
                    v-for="(badge, badgeIndex) in category.nonOwnedBadges"
                    :key="badgeIndex"
                    class="badge-container non-owned"
                    @click="openBadgePopup(badge)"
                >
                    <img
                        :src="badge.image_path"
                        alt="Badge"
                        class="badge-image grayscale"
                    />
                    <div class="text-center">
                        <p class="badge-name">{{ badge.name }}</p>
                        <p class="badge-score">
                            {{ badge.score }} <span>pt</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Popup pentru detalii badge -->
        <div v-if="selectedBadge" class="popup-overlay" @click="closePopup">
            <div class="popup-content" @click.stop>
                <button class="close-btn" @click="closePopup">&times;</button>

                <div class="emoji-top">🏅</div>

                <h3 class="popup-title">{{ selectedBadge.name }}</h3>

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

            this.categories.forEach((category) => {
                categoriesMap[category.value] = {
                    label: category.label,
                    ownedBadges: [],
                    nonOwnedBadges: [],
                    allBadges: [],
                };
            });

            this.badges.forEach((badge) => {
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
    },
};
</script>
<style>
.badge-section-container {
    text-align: center;
    padding: 20px;
}

.section-title {
    font-size: 2.2rem;
    font-weight: bold;
    text-transform: uppercase;
    background: linear-gradient(90deg, #4f46e5, #00c6ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 1.5px;
    margin-bottom: 30px;
    display: inline-block;
    padding-bottom: 5px;
}

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

.category-title:hover {
    opacity: 0.85;
    transform: scale(1.02);
    transition: all 0.3s ease-in-out;
}

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

.popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 50;
}

.popup-content {
    background-color: #ffffff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 360px;
    height: 280px;
    position: relative;
    animation: fadeIn 0.25s ease-in-out;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.emoji-top {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.popup-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.popup-description {
    font-size: 1rem;
    color: #555;
    padding: 0 10px;
}

.close-btn {
    position: absolute;
    top: 12px;
    right: 15px;
    font-size: 22px;
    background: none;
    border: none;
    color: #888;
    cursor: pointer;
    transition: color 0.2s ease;
}

.close-btn:hover {
    color: #e53935;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
