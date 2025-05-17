<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList
                        :title="'Comenzile clienților'"
                        :description="'Aici poți vedea toate comenzile realizate în aplicație de către clienți!'"
                        :items="orders"
                        :entityName="'orders'"
                        :filters="filters"
                        :columns="columns"
                        :prevFilters="prevFilters"
                        :getRoute="'admin.clients_orders.index'"
                        :descriptionDetails="'Detalii comandă:'"
                        class="p-4"
                        :detailsLabel="detailsLabel"
                        :extraLabel="extraLabel"
                        :invoice="'clients'"
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
    components: {
        AuthenticatedLayout,
        GenericExpandedList,
    },

    computed: {
        columns() {
            return [
                { name: "name", label: "Nume client" },
                { name: "created_at", label: "Data comenzii", sorting: true },
                { name: "total_price", label: "Preț total", sorting: true },
            ];
        },

        filters() {
            return [
                {
                    model: "name",
                    label: "Caută după numele clientului",
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
                { name: "total_price", label: "Preț total" },
                { name: "total_products", label: "Număr de produse" },
            ];
        },
    },
};
</script>
