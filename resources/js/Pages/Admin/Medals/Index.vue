<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎖️ Panou Admin – Gestionare Medalii 🥇
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Medalii'"
                        :description="'Aici poți vedea toate medaliile.'"
                        :items="medals"
                        :entityName="'medalie'"
                        :filters="filters"
                        :getRoute="'admin-gamification.medals.index'"
                        :editRoute="'admin-gamification.medals.edit'"
                        :create-route="'admin-gamification.medals.store'"
                        :deleteRoute="'admin-gamification.medals.destroy'"
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

export default {
    name: "Admin/Products/List",

    components: {
        AuthenticatedLayout,
        GenericList,
    },

    props: {
        medals: {
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
                { name: "tier", label: "Nivel" },
                { name: "threshold", label: "Puncte minime", sorting: true },
                { name: "discount", label: "Reducere", sorting: true },
                { name: "created_at", label: "Creat la", sorting: true },
            ];
        },

        filters() {
            return [
                {
                    model: "searchTier",
                    label: "Caută după nivel",
                    type: "text",
                    placeholder: "Introduceți nivelul",
                },
            ];
        },
    },
};
</script>
