<template>
    <div class="p-4 mt-12">
        <!-- Titlu + Butoane Scroll -->
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-700">
                Most Played Quizzes (Last 7 Days)
            </h2>
            <div class="flex space-x-1">
                <button
                    @click="scrollLeft"
                    class="text-gray-600 hover:text-gray-900"
                >
                    ←
                </button>
                <button
                    @click="scrollRight"
                    class="text-gray-600 hover:text-gray-900"
                >
                    →
                </button>
            </div>
        </div>

        <!-- Carousel -->
        <div
            v-if="quizzes.length > 0"
            ref="carousel"
            class="flex space-x-4 overflow-x-auto scrollbar-hide"
        >
            <div
                v-for="(quiz, index) in quizzes"
                :key="index"
                class="max-w-[180px] border border-gray-200 rounded-md bg-white p-3 flex flex-col items-center"
            >
                <img
                    :src="quiz.image"
                    alt="Quiz Image"
                    class="w-32 h-32 object-cover rounded-md mb-2"
                />

                <h3 class="text-sm font-medium text-gray-800 text-center">
                    {{ quiz.title }}
                </h3>
                <p class="text-xs text-gray-500">
                    {{ quiz.appearances }} Plays
                </p>
            </div>
        </div>
        <div v-else class="text-center text-gray-500 mt-4 italic">
            💤 No quiz activity recorded this week.
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    quizzes: Array,
});

const carousel = ref(null);

const scrollLeft = () => {
    carousel.value?.scrollBy({ left: -200, behavior: "smooth" });
};

const scrollRight = () => {
    carousel.value?.scrollBy({ left: 200, behavior: "smooth" });
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
