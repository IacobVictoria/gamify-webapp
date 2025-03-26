<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎖️ Admin Dashboard – Medals Manager 🥇
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Medals'"
                        :description="'Here you can see all the medals.'"
                        :items="medals"
                        :entityName="'medals'"
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
                { name: "tier", label: "Tier" },
                { name: "threshold", label: "Min points", sorting: true },
                { name: "discount", label: "Discount", sorting: true },
                { name: "created_at", label: "Created", sorting: true },
            ];
        },

        filters() {
            return [
                {
                    model: "searchTier",
                    label: "Search by Tier",
                    type: "text",
                    placeholder: "Search by tier",
                },
            ];
        },
    },
};
</script>
