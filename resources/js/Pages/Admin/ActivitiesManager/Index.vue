<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Activities'"
                        :description="'Aici poți vedea toate activitățile.'"
                        :items="activities"
                        :entityName="'activitate'"
                        :filters="filters"
                        :getRoute="'admin-gamification.activities.index'"
                        :createRoute="'admin-gamification.activities.create'"
                        :editRoute="'admin-gamification.activities.edit'"
                        :deleteRoute="'admin-gamification.activities.destroy'"
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
        activities: {
            type: Object,
            required: true,
        },

        prevFilters: {
            type: Array,
            required: true,
        },
        types: {
            type: Array,
            required: true,
        },
    },

    computed: {
        columns() {
            return [
                { name: "title", label: "Titlu" },
                { name: "score", label: "Scor", sorting: true },
                { name: "type", label: "Tip" },
                { name: "created_at", label: "Creat la", sorting: true },
                { name: "is_published", label: "Publicat" },
            ];
        },

        filters() {
            return [
                {
                    model: "searchTitle",
                    label: "Caută după titlu",
                    type: "text",
                    placeholder: "Caută după titlu",
                },
                {
                    model: "searchPublished",
                    label: "Caută după publicare",
                    type: "select",
                    placeholder: "Caută după publicare",
                    options: [
                        { value: "", label: "Toate" },
                        { value: "true", label: "Publicate" },
                        { value: "false", label: "Nepublicate" },
                    ],
                },
                {
                    model: "searchType",
                    label: "Caută după tip",
                    type: "select",
                    placeholder: "Caută după tip",
                    options: this.types.map((type) => ({
                        value: type.value,
                        label: type.label,
                    })),
                },
            ];
        },
    },
};
</script>
