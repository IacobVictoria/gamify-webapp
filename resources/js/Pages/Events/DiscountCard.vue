<template>
    <div class="bg-blue-100 p-6 rounded-lg shadow-lg flex flex-col items-center">
        <div class="text-3xl mb-4">{{ getDiscountEmoji }}🎉</div>
        <h3 class="text-xl font-semibold">{{ discount.title }}</h3>
        <p class="text-sm">{{ discount.description }}</p>
        <p class="text-sm">Start: {{ discount.start }} - End: {{ discount.end }}</p>

        <div v-if="discount.details.applyTo === 'all'">
            <p class="text-md mt-2">A discount of {{ discount.details.discount }}% is applied to all products!</p>
        </div>
        <div v-if="discount.details.applyTo === 'categories' && discount.details.category">
            <p class="text-md mt-2">A discount of {{ discount.details.discount }}% is applied to the category: <strong>{{ discount.details.category }}</strong>!</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        discount: Object,
    },
    computed: {
        getDiscountEmoji() {
            if (this.discount.details.discount >= 30) {
                return '🔥';
            } else if (this.discount.details.discount >= 20) {
                return '💥';
            }
            return '🎉';
        },
    },
};
</script>

<style scoped>
.bg-blue-100 {
    background-color: #d1e7ff;
}
</style>
