<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your Orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
export default {
    components: {
        AuthenticatedLayout,
        OrdersArchive,
        IngoingOrdersSection
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
        activeOrders:{
            type: Array,
            required: true,
        }
    },
}
</script>
