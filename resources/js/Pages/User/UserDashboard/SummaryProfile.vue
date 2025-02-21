<template>
    <div class="user-summary-card bg-gradient-to-r from-purple-500 to-blue-500 p-4 rounded-lg shadow-md text-white">
        <div class="profile-section text-center">
            <img :src="profilePicture" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto shadow-lg" />
            <h2 class="mt-2 font-bold text-xl">{{ userName }}</h2>
        </div>
        <div class="stats-section flex justify-around mt-4">
            <div class="stat">
                <h3 class="text-lg font-semibold">Points</h3>
                <p class="text-2xl font-bold">{{ points }}</p>
            </div>
            <div class="stat">
                <h3 class="text-lg font-semibold">Top</h3>
                <p class="text-2xl font-bold">#{{ topRank }}</p>
            </div>
            <div class="stat">
                <h3 class="text-lg font-semibold">Badges</h3>
                <p class="text-2xl font-bold">{{ badges }}</p>
            </div>
        </div>
        <div class="progress-bar-section mt-6">
            <h3 class="text-center mb-2 text-sm">Next Medal: {{ nextMedal }}</h3>
            <div class="progress-bar bg-gray-700 h-4 rounded-full overflow-hidden">
                <div class="h-4 rounded-full transition-all"
                    :style="{ width: progressPercentage + '%', backgroundColor: progressColor }"></div>
            </div>
            <p class="text-center mt-2 text-sm">{{ pointsNeeded }} points to {{ nextMedal }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        profilePicture: String,
        userName: String,
        points: Number,
        topRank: Number,
        badges: Number,
        nextMedal: String,
        progressPercentage: Number,
        progressColor: String,
        pointsNeeded: Number,
    },
};
</script>

<style scoped>
.user-summary-card {
    max-width: 400px;
    margin: auto;
}

.progress-bar-section {
    text-align: center;
}
</style>