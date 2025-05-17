<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList
                        :items="orders"
                        title="Comenzi către furnizori"
                        description="Urmărește istoricul comenzilor plasate către furnizori"
                        :columns="columns"
                        :filters="filters"
                        :entity-name="'Order'"
                        :get-route="'admin.suppliers_orders.index'"
                        :descriptionDetails="'Mai multe detalii despre comandă'"
                        class="p-4"
                        :detailsLabel="detailsLabel"
                        :extraLabel="extraLabel"
                        :invoice="'suppliers'"
                        :prevFilters="prevFilters"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import GenericExpandedList from "@/Components/GenericExpandedList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    name: "Admin/Supplier_Orders/List",

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
            type: Array,
            required: true,
        },
    },

    computed: {
        columns() {
            return [
                { name: "name", label: "Nume Furnizor" },
                { name: "order_date", label: "Data Comenzii", sorting: true },
                { name: "total_price", label: "Preț Total", sorting: true },
            ];
        },

        filters() {
            return [
                {
                    model: "name",
                    label: "Caută după numele furnizorului",
                    type: "text",
                    placeholder: "Caută după nume",
                },
            ];
        },

        detailsLabel() {
            return [
                { name: "name", label: "Produs" },
                { name: "quantity", label: "Cantitate" },
                { name: "price", label: "Preț" },
            ];
        },

        extraLabel() {
            return [
                { name: "total_price", label: "Preț Total" },
                { name: "total_products", label: "Număr Produse" },
            ];
        },
    },
};
</script>
