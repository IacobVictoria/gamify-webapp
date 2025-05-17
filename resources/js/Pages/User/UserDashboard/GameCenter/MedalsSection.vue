<template>
    <div>
        <h2 class="section-title">Medaliile tale</h2>
        <div class="grid grid-cols-4 gap-4">
            <!-- Afișăm toate medaliile în ordinea corectă -->
            <div
                v-for="(medal, medalIndex) in sortedMedals"
                :key="medalIndex"
                class="medal-container"
                :class="{ 'non-owned': !medal.owned }"
            >
                <img
                    :src="imagePath(`/medals/${medal.tier}-medal.png`)"
                    alt="Medal"
                    class="medal-image"
                    :class="{ grayscale: !medal.owned }"
                />
                <div class="text-center">
                    <p class="medal-name">
                        {{ capitalizeFirstLetter(translateTier(medal.tier)) }}
                    </p>
                    <p class="medal-description">{{ medal.description }}</p>
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
        sortedMedals() {
            const order = { bronze: 1, silver: 2, gold: 3 }; // Ordinea medaliilor
            return [...this.medals].sort(
                (a, b) => order[a.tier] - order[b.tier]
            ); // Sortare după tier
        },
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        translateTier(tier) {
            const translations = {
                bronze: "Bronz",
                silver: "Argint",
                gold: "Aur",
            };
            return translations[tier] || tier;
        },
    },
};
</script>
<style scoped>
.grayscale {
    filter: grayscale(100%);
}

.medal-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 140px;
    height: 180px;
}

.medal-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}

.medal-name {
    font-weight: bold;
    font-size: 1rem;
    color: #333;
    margin-bottom: 5px;
}

.medal-description {
    font-size: 0.85rem;
    color: #666;
    text-align: center;
    max-width: 120px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 16px;
}

.non-owned {
    opacity: 0.6;
}
</style>
