<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-4 mr-4">
        <!-- Total Accounts -->
        <div
            class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-teal-400 to-cyan-600 shadow-xl rounded-2xl text-center"
        >
            <h3 class="text-white font-semibold text-xl mb-2">
                Conturi totale
            </h3>
            <p class="text-white text-4xl font-extrabold mb-1">
                {{ totalAccounts }}
            </p>
        </div>

        <!-- Total Roles -->
        <div
            class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-yellow-400 to-yellow-600 shadow-xl rounded-2xl text-center"
        >
            <h3 class="text-white font-semibold text-xl mb-2">Roluri totale</h3>
            <p class="text-white text-4xl font-extrabold mb-1">
                {{ totalRoles }}
            </p>
        </div>

        <!-- Weekly New Accounts -->
        <div
            class="flex flex-col items-center justify-center h-48 p-6 bg-gradient-to-r from-pink-500 to-rose-500 shadow-xl rounded-2xl text-center"
        >
            <h3 class="text-white font-semibold text-xl mb-2">
                Conturi în această săptămână
            </h3>
            <p class="text-white text-4xl font-extrabold mb-1">
                {{ accountsThisWeek }}
            </p>
            <p class="text-white text-sm">
                <span v-if="weeklyGrowth >= 0" class="text-green-200"
                    >↑ {{ weeklyGrowth }}%</span
                >
                <span v-else class="text-red-200"
                    >↓ {{ Math.abs(weeklyGrowth) }}%</span
                >
            </p>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        totalAccounts: Number,
        totalRoles: Number,
        accountsThisWeek: Number,
        weeklyGrowth: Number,
    },
};
</script>
