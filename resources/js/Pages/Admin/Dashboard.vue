<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import NpsChart from "./NPS_Admin/NpsChart.vue";
import Stats from "./DashboardManager/Stats.vue";
import TopSellingProducts from "./DashboardManager/TopSellingProducts.vue";
import CurrentDiscountsProgress from "./DashboardManager/CurrentDiscountsProgress.vue";
import ProgressBarStats from "./DashboardManager/ProgressBarStats.vue";
import VariousStats from "./DashboardManager/VariousStats.vue";
import GlobalStats from "./DashboardManager/GlobalStats.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :toggleAdmin="toggleAdmin">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Admin Dashboard
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

                    <Stats
                        :weeklySales="weeklySales"
                        :weeklyOrders="weeklyOrders"
                        :newVisitors="newVisitors"
                        :salesChange="salesChange"
                        :ordersChange="ordersChange"
                        :newVisitorsChange="newVisitorsChange"
                    ></Stats>
                    <TopSellingProducts
                        :topProductsWeekly="topProductsWeekly"
                    />
                    <CurrentDiscountsProgress
                        :currentDiscounts="currentDiscounts"
                    />
                    <ProgressBarStats :progressBarStats="progressBarStats" />
                    <VariousStats :variousStats="variousStats" />
                    <div class="flex flex-col md:flex-row gap-12 mb-12">
                        <!-- GlobalStats ocupa 50% -->
                        <GlobalStats
                            :globalStats="globalStats"
                            class="w-full md:w-1/2"
                        />

                        <!-- NPS & Chart ocupa 50% -->
                        <div class="w-full md:w-1/2 flex flex-col gap-5">
                            <div class="bg-white p-4">
                                <h1
                                    class="title text-left text-xl font-semibold"
                                >
                                    {{ nps !== null ? nps : "N/A" }}
                                </h1>
                                <p class="text-gray-500">
                                    (NPS)
                                </p>
                            </div>

                            <NpsChart :monthly-nps-data="monthlyNpsData" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "Admin/Dashboard",

    props: {
        nps: Number,
        monthlyNpsData: Array,
        weeklySales: Number,
        weeklyOrders: Number,
        newVisitors: Number,
        salesChange: Number,
        ordersChange: Number,
        newVisitorsChange: Number,
        topProductsWeekly: Array,
        currentDiscounts: Array,
        progressBarStats: Object,
        variousStats: Object,
        globalStats: Object,
        toggleAdmin: Boolean,
    },
    components: {
        NpsChart,
    },
};
</script>
