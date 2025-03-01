<template>
    <div class="p-6 mt-6 ">
        <h2 class="text-lg font-semibold mb-4">Bonusuri Disponibile</h2>

        <div v-if="discounts.length > 0">
            <div v-for="discount in discounts" :key="discount.code" 
                class="border p-3 rounded mb-2 flex justify-between items-center" 
                :class="discount.used ? 'bg-gray-300' : 'bg-green-100'">

                <div>
                    <p class="font-semibold">Reducere: {{ discount.discount }}%</p>
                    <p v-if="!discount.used" class="text-sm text-gray-600">
                        Cod promoțional: <span class="font-bold">{{ discount.code }}</span>
                    </p>
                    <p v-else class="text-sm text-gray-600 italic">Folosite</p>
                </div>
            </div>
        </div>

        <p v-else class="text-gray-500">Momentan nu ai discount-uri disponibile.</p>
    </div>
</template>

<script>
export default {
    props: {
        discounts: Array
    }
};
</script>
