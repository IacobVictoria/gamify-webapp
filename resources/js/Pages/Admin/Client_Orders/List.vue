<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList :title="'Clients orders'" :description="'Here you can see all orders made in app by clients!'"
                        :items="orders" :entityName="'orders'" :filters="filters" :columns="columns"
                        :prevFilters="prevFilters" :getRoute="'admin.clients_orders.index'"
                        :descriptionDetails="'Order details:'" class="p-4" :detailsLabel="detailsLabel" :extraLabel="extraLabel" :invoice="'clients'" />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
<script>
import GenericExpandedList from '@/Components/GenericExpandedList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    props: {
        orders: {
            type: Object,
            required: true
        },

        prevFilters: {
            type: Array,
            required: true
        }
    },
    components: {
        AuthenticatedLayout,
        GenericExpandedList
    },

    computed: {
        columns() {
            return [
                { name: 'name', label: 'Client name' },
                { name: 'date', label: 'Date of order' },
                { name: 'total_price', label: 'Total Price' }
            ]
        },

        filters() {
            return [
                { model: 'name', label: 'Search by user name', type: 'text', placeholder: 'Search by name' },
                { model: 'sortDate', label: 'Sort by Date', type: 'sorting', placeholder: 'Search by date' },
            ];
        },

        detailsLabel() {
            return [
                { name: 'name', label: 'Product' },
                { name: 'quantity', label: 'Quantity' },
                { name: 'price', label: 'Price' },

            ]

        }
        ,
        extraLabel() {
            return [
                { name: 'total_price', label: 'Total price' },
                { name: 'total_products', label: 'Number of products' },
            ]
        }
    }
}
</script>