<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Suppliers'"
                        :description="'Aici poți vedea toți furnizorii.'"
                        :items="suppliers"
                        :entityName="'furnizor'"
                        :filters="filters"
                        :getRoute="'admin.suppliers.index'"
                        :createRoute="'admin.suppliers.create'"
                        :editRoute="'admin.suppliers.update'"
                        :deleteRoute="'admin.suppliers.destroy'"
                        :columns="columns"
                        :prevFilters="prevFilters"
                        class="p-4"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from "@/Components/GenericList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
export default {
    name: "Admin/Accounts/List",

    components: {
        AuthenticatedLayout,
        GenericList,
        Head,
    },

    props: {
        suppliers: {
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
                { name: "name", label: "Nume" },
                { name: "email", label: "Email" },
                { name: "created_at", label: "Creat la" },
            ];
        },

        filters() {
            return [
                {
                    model: "searchName",
                    label: "Caută după nume",
                    type: "text",
                    placeholder: "Introdu numele",
                },
                {
                    model: "searchEmail",
                    label: "Caută după email",
                    type: "text",
                    placeholder: "Introdu adresa de email",
                },
            ];
        },
    },
};
</script>
