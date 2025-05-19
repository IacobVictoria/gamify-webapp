<template>
    <div class="bg-white p-5">
        <h2 class="text-xl font-semibold text-gray-900 mb-5">
            📊 Progres Gamificare
        </h2>

        <div v-if="progressBarStats">
            <!-- Medii Badge-uri pe Utilizator -->
            <div class="mb-4">
                <div class="flex justify-between text-sm font-medium">
                    <span>Medie Badge-uri per Utilizator</span>
                    <span class="text-gray-500">{{
                        progressBarStats.averageBadgesPerUser.toFixed(1)
                    }}</span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
                    <div
                        class="h-3 rounded-md transition-all bg-blue-500"
                        :style="{
                            width: progressBarStats.badgesPercentage + '%',
                        }"
                    ></div>
                </div>
            </div>

            <!-- Progres pe Categorii de Badge-uri -->
            <div class="mb-4">
                <div class="flex justify-between text-sm font-medium">
                    <span>Medie Finalizare Badge-uri pe Categorie</span>
                    <span class="text-gray-500"
                        >{{ progressBarStats.categoryBadgeProgress }}%</span
                    >
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
                    <div
                        class="h-3 rounded-md transition-all bg-purple-500"
                        :style="{
                            width: progressBarStats.categoryBadgeProgress + '%',
                        }"
                    ></div>
                </div>
            </div>

            <!-- Rată Finalizare Hangman -->
            <div class="mb-4">
                <div class="flex justify-between text-sm font-medium">
                    <span>Rată de Finalizare Hangman</span>
                    <span class="text-gray-500"
                        >{{ progressBarStats.hangmanCompletionRate }}%</span
                    >
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
                    <div
                        class="h-3 rounded-md transition-all bg-green-500"
                        :style="{
                            width: progressBarStats.hangmanCompletionRate + '%',
                        }"
                    ></div>
                </div>
            </div>

            <!-- Eficiență Reîncercare Quiz -->
            <div class="mb-4">
                <div class="flex justify-between text-sm font-medium">
                    <span>Eficiență Reîncercare Quiz</span>
                    <span class="text-gray-500"
                        >{{ progressBarStats.quizRetryRate }}%</span
                    >
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
                    <div
                        class="h-3 rounded-md transition-all bg-yellow-400"
                        :style="{ width: progressBarStats.quizRetryRate + '%' }"
                    ></div>
                </div>
            </div>

            <!-- Rată Succes Quiz (80%+) -->
            <div class="mb-1">
                <div class="flex justify-between text-sm font-medium">
                    <span>Rată de Succes la Quiz (80%+)</span>
                    <span class="text-gray-500"
                        >{{ progressBarStats.quizSuccessRate }}%</span
                    >
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-md mt-1">
                    <div
                        class="h-3 rounded-md transition-all bg-emerald-500"
                        :style="{
                            width: progressBarStats.quizSuccessRate + '%',
                        }"
                    ></div>
                </div>
            </div>
        </div>

        <p v-else class="text-gray-500 text-sm text-center">
            Se încarcă statisticile de gamificare...
        </p>
    </div>
</template>
<script>
export default {
    props: {
        progressBarStats: {
            type: Object,
            default: () => ({}),
        },
    },
};
</script>
