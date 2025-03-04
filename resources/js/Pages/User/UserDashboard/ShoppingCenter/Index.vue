<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-white leading-tight text-center bg-gradient-to-r from-orange-500 to-red-500 p-4 rounded-lg shadow-lg">
                🛒 Your Orders - Ready for Delivery! 📦
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Noua secțiune Bonus -->
                    <BonusSection :discounts="discounts"></BonusSection>
                    <IngoingOrdersSection :activeOrders="activeOrders"></IngoingOrdersSection>
                    <OrdersArchive :orders="orders" :prev-filters="prevFilters"></OrdersArchive>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import OrdersArchive from './OrdersArchive.vue';
import IngoingOrdersSection from './IngoingOrdersSection.vue'
import BonusSection from './BonusSection.vue';
export default {
    components: {
        AuthenticatedLayout,
        OrdersArchive,
        IngoingOrdersSection,
        BonusSection
    },
    props: {
        orders: {
            type: Object,
            required: true,
        },
        prevFilters: {
            type: Object,
            required: true
        },
        activeOrders: {
            type: Array,
            required: true,
        },
        discounts: {
            type: Array,
            required: true,
        }
    },
}
</script>
