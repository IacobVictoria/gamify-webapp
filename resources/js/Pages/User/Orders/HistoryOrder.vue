<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList :title="'Orders History'" :description="'Here you can see all your Orders.'"
                        :items="orders" :entityName="'orders'" :filters="filters" :columns="columns"
                        :prevFilters="prevFilters" :getRoute="'user.order_history.index'"
                        :descriptionDetails="'Detalii Produse:'" class="p-4"
                        :detailsLabel="detailsLabel" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GenericExpandedList from '@/Components/GenericExpandedList.vue';

export default {
    name: 'User/Orders/OrderHistory',

    components: {
        AuthenticatedLayout,
        GenericExpandedList,
    },
    props: {
        orders: {
            type: Object,
            required: true,
        },
        prevFilters: {
            type: Object,
            required: true
        }
    },

    computed: {
        columns() {
            return [
                { name: 'date', label: 'Order Date' },
                { name: 'total', label: 'Total Price' },
            ];
        },

        filters() {
            return [
                { model: 'sortTotal', label: 'Sort by Total', type: 'sorting', placeholder: 'Search by total' },
                { model: 'sortDate', label: 'Sort by Date', type: 'sorting', placeholder: 'Search by date' },
            ];
        },

        detailsLabel() {
            return [
                {name: 'name', label: 'Product'},
                {name: 'quantity', label:'Quantity'},
                {name: 'price', label: 'Price'},

            ]

        }
    },
};
</script>

<style scoped>
.filters {
    margin-bottom: 20px;
    display: flex;
    gap: 20px;
}

.order-expanded-list {
    margin: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.order-header {
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.order-item {
    border-bottom: 1px solid #eee;
    padding: 10px;
    cursor: pointer;
}

.order-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.details {
    margin-top: 10px;
    padding-left: 20px;
}
</style>
