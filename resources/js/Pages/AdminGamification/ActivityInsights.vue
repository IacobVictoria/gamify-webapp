<template>
    <div class="bg-white p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">
            🎯 Statistici Activități
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Total Activități -->
            <div
                class="p-4 rounded-lg shadow border-l-4 border-blue-500 bg-blue-50"
            >
                <div class="text-blue-600 text-2xl font-bold">
                    {{ stats.totalActivities }}
                </div>
                <div class="text-sm text-gray-600">
                    Număr total de activități
                </div>
            </div>

            <!-- Participanți săptămâna aceasta -->
            <div
                class="p-4 rounded-lg shadow border-l-4 border-green-500 bg-green-50"
            >
                <div class="text-green-600 text-2xl font-bold">
                    {{ stats.totalParticipantsThisWeek }}
                </div>
                <div class="text-sm text-gray-600">
                    Participanți în această săptămână
                </div>
            </div>

            <!-- Scor mediu -->
            <div
                class="p-4 rounded-lg shadow border-l-4 border-indigo-500 bg-indigo-50"
            >
                <div class="text-indigo-600 text-2xl font-bold">
                    {{ stats.averageScorePerActivity }}
                </div>
                <div class="text-sm text-gray-600">
                    Scor mediu per activitate
                </div>
            </div>

            <!-- Cea mai populară activitate -->
            <div
                v-if="stats.mostPopularActivity"
                class="p-4 rounded-lg shadow border-l-4 border-yellow-500 bg-yellow-50"
            >
                <div class="text-yellow-700 font-semibold">
                    {{ stats.mostPopularActivity.title }} ({{
                        stats.mostPopularActivity.type
                    }})
                </div>
                <div class="text-sm text-gray-600">
                    Scor: {{ stats.mostPopularActivity.score }} · Participanți:
                    {{ stats.mostPopularActivity.participants }}
                </div>
            </div>

            <!-- Cel mai popular tip -->
            <div
                class="p-4 rounded-lg shadow border-l-4 border-pink-500 bg-pink-50"
            >
                <div class="text-pink-600 text-xl font-bold">
                    {{ stats.mostPopularType }}
                </div>
                <div class="text-sm text-gray-600">
                    Cel mai popular tip de activitate
                </div>
            </div>

            <!-- Total favorite -->
            <div
                class="p-4 rounded-lg shadow border-l-4 border-red-500 bg-red-50"
            >
                <div class="text-red-600 text-2xl font-bold">
                    {{ stats.totalFavorites }}
                </div>
                <div class="text-sm text-gray-600">
                    Total salvări la favorite
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
defineProps({
    stats: Object,
});
</script>
