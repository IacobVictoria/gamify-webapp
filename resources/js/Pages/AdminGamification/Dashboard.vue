<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout :toggleAdminGamification="toggleAdminGamification">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🔥 Panou de Administrare Gamificare
            </h2>
        </template>

        <div class="py-8">
            <div class="px-32">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="mb-10 w-full bg-[#334155] text-white p-10 text-left"
                    >
                        <span class="text-xl block text-left ml-0"
                            >Salut, {{ $page.props.user.name }} 👋</span
                        >
                    </div>

                    <div class="py-6 px-8">
                        <Stats
                            :weeklyBadges="weeklyBadges"
                            :weeklyMedals="weeklyMedals"
                            :avgQuizScore="avgQuizScore"
                        />
                        <TopQuizzes :quizzes="topQuizzes"></TopQuizzes>
                        <ProgressBarStats
                            :progressBarStats="progressBarStats"
                        />
                        <VariousStats :variousStats="variousStats" />

                        <div class="flex flex-col md:flex-row gap-12 mb-12">
                            <!-- GlobalStats ocupa 50% -->
                            <GlobalStats
                                :globalStats="globalStats"
                                class="w-full md:w-1/2"
                            />
                            <div class="w-full md:w-1/2 flex flex-col gap-5">
                                <ActivityInsights :stats="activitiesInsights" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Stats from "./Stats.vue";
import TopQuizzes from "./TopQuizzes.vue";
import ProgressBarStats from "./ProgressBarStats.vue";
import VariousStats from "./VariousStats.vue";
import GlobalStats from "./GlobalStats.vue";
import ActivityInsights from "./ActivityInsights.vue";

const props = defineProps({
    toggleAdminGamification: Boolean,
    weeklyBadges: Number,
    weeklyMedals: Number,
    avgQuizScore: Number,
    hangmanRate: Number,
    topQuizzes: Array,
    progressBarStats: Object,
    variousStats: Object,
    globalStats: Object,
    activitiesInsights: Object,
});
</script>
