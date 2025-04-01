<template>
    <div class="bg-white p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-5">
            📊 Weekly Gamification Insights
        </h2>

        <!-- Top Badges Earned -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                🏅 Most Earned Badges
            </h3>
            <div class="flex justify-around gap-4">
                <div
                    v-for="(badge, index) in variousStats.topBadges"
                    :key="index"
                    class="flex flex-col items-center rounded-xl shadow-md p-3 w-24"
                    :class="{
                        'border-2 border-yellow-500 scale-110': index === 0,
                    }"
                >
                    <p
                        :class="getRankingColor(index)"
                        class="text-lg font-bold"
                    >
                        #{{ index + 1 }}
                    </p>
                    <img
                        :src="badge.image"
                        alt="Badge Image"
                        class="w-14 h-14 rounded-md mt-2 mb-1"
                    />
                    <p class="text-xs font-semibold text-center text-gray-800">
                        {{ badge.name }}
                    </p>
                    <p class="text-xs text-gray-500">{{ badge.count }} times</p>
                </div>
            </div>
        </div>

        <!-- Top Rated Quizzes -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                🧠 Highest Rated Quizzes
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="(quiz, index) in variousStats.topRatedQuizzes"
                    :key="index"
                    class="bg-gray-50 p-4 rounded-md shadow-sm"
                >
                    <p class="text-sm font-medium text-gray-700 truncate">
                        📘 {{ quiz.title }}
                    </p>
                    <p class="text-xl font-bold text-indigo-600">
                        {{ quiz.avg_score }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Weekly Badge + Medal Count -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-green-50 p-4 rounded-md shadow">
                <h4 class="text-lg font-bold text-green-700">
                    {{ variousStats.weeklyBadgesCount }}
                </h4>
                <p class="text-sm text-gray-600 font-medium">
                    Badges Awarded This Week
                </p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-md shadow">
                <h4 class="text-lg font-bold text-yellow-700">
                    {{ variousStats.weeklyMedalsCount }}
                </h4>
                <p class="text-sm text-gray-600 font-medium">
                    Medals Awarded This Week
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        variousStats: {
            type: Object,
            required: true,
        },
    },
    methods: {
        getRankingColor(index) {
            return index === 0
                ? "text-yellow-500"
                : index === 1
                ? "text-gray-500"
                : "text-orange-500";
        },
    },
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
