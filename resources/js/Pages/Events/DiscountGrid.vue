<template> 
    <div v-if="discounts.length" class="hidden lg:block mt-8 max-w-5xl mx-auto">
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-12">🔥 Active Discounts</h2>

        <div v-for="(discount, index) in discounts" :key="discount.id" class="grid grid-cols-2 items-center gap-x-14 gap-y-8 mb-20">
            <!-- Imagine / Elipsă -->
            <div :class="{
                'order-1': index % 2 === 0,
                'order-2': index % 2 !== 0
            }" class="">
                <img :src="imagePath(discountImages[index % discountImages.length])" :alt="discount.title" 
                    class="relative z-10 w-64 h-64 object-contain" />
            </div>

            <!-- Text -->
            <div :class="{
                'order-2 text-left': index % 2 === 0,
                'order-1 text-right': index % 2 !== 0
            }" class="flex flex-col justify-center gap-5">
                <h3 class="text-3xl font-bold text-[#FA902F]">
                    {{ discount.title }}
                </h3>
                <p class="text-base leading-6 text-gray-800">
                    {{ discount.description }}
                </p>
                <p class="text-lg font-semibold text-gray-600">
                    🎁 {{ discount.details.discount }}% OFF
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

// Array de imagini pe care să le alternezi
const discountImages = [
    '/custom/discount1.png',
    '/custom/discount2.png',
    '/custom/discount3.png'
];

defineProps({
    discounts: {
        type: Array,
        required: true
    }
});
</script>
