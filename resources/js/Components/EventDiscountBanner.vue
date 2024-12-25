<template>
    <div v-if="activeDiscounts"
        class="bg-gradient-to-r from-yellow-400 to-orange-500 py-10 px-6 rounded-lg shadow-lg mb-8">
        <div class="flex items-center gap-6">
            <img src="/images/gift.png" alt="Gift Icon" class="h-16 w-16" />

            <div class="text-white">
                <h2 class="text-2xl font-bold">Active Discounts</h2>
                <ul class="mt-4 space-y-2">
                    <li v-for="discount in activeDiscounts" key="discount.id" class="text-lg">
                        <span class="font-semibold">{{ discount.title }}</span>: Save {{ discount.details.percentage }}%!
                        Valid until <span class="font-semibold">{{ formatDate(discount.end) }}</span>.
                    </li>
                </ul>
            </div>

            <img src="/images/percentage.png" alt="Percentage Icon" class="h-16 w-16 ml-auto hidden sm:block" />
        </div>
    </div>
</template>

<script>
export default {
    name: 'EventDiscountBanner',

    props: {
        activeDiscounts: {
            type: Array,
            required: true,
        },
    },

    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
    },
};
</script>

<style scoped>
/* Custom styles for the banner */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, #f59e0b, #f97316);
}
</style>