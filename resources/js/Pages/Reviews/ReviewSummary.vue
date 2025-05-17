<template>
    <div
        v-if="statistics.length != 0"
        class="max-w-3xl mx-auto p-8 mt-16 mb-16"
    >
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">
            📊 Detalierea evaluărilor
        </h2>

        <div class="flex flex-col space-y-4">
            <div
                v-for="(value, key) in statistics"
                :key="key"
                class="flex items-center"
            >
                <div
                    class="progress-label font-semibold text-gray-700 w-1/4 text-sm"
                >
                    ⭐ {{ key }} ({{ value.toFixed(2) }}%)
                </div>
                <div
                    class="w-3/4 h-5 bg-gray-200 rounded-lg overflow-hidden shadow-inner"
                >
                    <div
                        class="h-full transition-all duration-500 rounded-lg progress-bar"
                        :style="{
                            width: value.toFixed(2) + '%',
                            background: getColor(parseFloat(key)),
                        }"
                    ></div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center mt-6">
            <div
                class="average-rating px-6 py-3 text-lg font-bold text-gray-100 text-black rounded-full shadow-md"
            >
                ⭐ Evaluare medie: {{ averageRating.toFixed(2) }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        averageRating: {
            type: Number,
            required: true,
        },
        statistics: {
            type: Map,
            required: true,
        },
    },

    methods: {
        getColor(key) {
            switch (key) {
                case 0:
                    return "#f44336";
                case 0.5:
                    return "#ff7043";
                case 1:
                    return "#ff9800";
                case 1.5:
                    return "#ffb74d";
                case 2:
                    return "#ffeb3b";
                case 2.5:
                    return "#dce775";
                case 3:
                    return "#8bc34a";
                case 3.5:
                    return "#c5e1a5";
                case 4:
                    return "#4caf50";
                case 4.5:
                    return "#388e3c";
                case 5:
                    return "#1b5e20";
                default:
                    return "#999";
            }
        },
    },
};
</script>

<style scoped>
.progress-bar {
    transition: width 0.5s;
}
</style>
