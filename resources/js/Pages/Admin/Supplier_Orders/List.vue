<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList :items="orders" title="Supplier Orders"
                        description="Track the history of supplier's orders" :columns="columns" :filters="filters"
                        :entity-name="'Order'" :get-route="'admin.suppliers_orders.index'"
                        :descriptionDetails="'More details about the order'" class="p-4" :detailsLabel="detailsLabel"
                        :extraLabel="extraLabel" :invoice="'suppliers'" :prevFilters="prevFilters" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
<script>
import GenericExpandedList from '@/Components/GenericExpandedList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    name: 'Admin/Supplier_Orders/List',

    components: {
        AuthenticatedLayout,
        GenericExpandedList
    },

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

    computed: {
        columns() {
            return [
                { name: 'name', label: 'Supplier Name' },
                { name: 'order_date', label: 'Order Date', sorting: true },
                { name: 'total_price', label: 'Total Price', sorting: true },
            ];
        },

        filters() {
            return [
                { model: 'name', label: 'Search by Supplier Name', type: 'text', placeholder: 'Search by name' },
            ]
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