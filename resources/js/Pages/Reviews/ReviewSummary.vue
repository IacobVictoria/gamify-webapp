<template>
    <div v-if="statistics.length!=0" class="flex flex-row justify-between mx-auto p-5 bg-gray-100 rounded-lg shadow-md mt-16 mb-16">
        <div class="flex flex-col space-y-3 w-2/3">
            <div v-for="(value, key) in statistics" :key="key" class="flex items-center">
                <div class="progress-label font-bold text-sm w-1/4">{{ key }} ({{ value.toFixed(2) }}%)</div>
                <div class="bg-gray-300 rounded-lg h-5 w-3/4">
                    <div class="h-full transition-all duration-500"
                        :style="{ width: value.toFixed(2) + '%', backgroundColor: getColor(parseFloat(key)) }">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center text-right w-1/3">
            <div class="average-rating text-lg font-bold text-gray-700">
                Average Rating: {{ averageRating.toFixed(2) }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        averageRating: {
            type: Number,
            required: true
        },
        statistics: {
            type: Map,
            required: true
        }
    },

    methods: {
        getColor(key) {
            switch (key) {
                case 0:
                    return '#f44336';
                case 0.5:
                    return '#ff7043';
                case 1:
                    return '#ff9800';
                case 1.5:
                    return '#ffb74d';
                case 2:
                    return '#ffeb3b';
                case 2.5:
                    return '#dce775';
                case 3:
                    return '#8bc34a';
                case 3.5:
                    return '#c5e1a5';
                case 4:
                    return '#4caf50';
                case 4.5:
                    return '#388e3c';
                case 5:
                    return '#1b5e20';
                default:
                    return '#999';
            }
        }
    }
}
</script>

<style scoped>
.progress-bar {
    transition: width 0.5s;

}
</style>
