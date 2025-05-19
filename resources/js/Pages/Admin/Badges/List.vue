<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏅 Panou Admin – Gestionare Insigne ✨
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <inertia-link
                    :href="route('admin-gamification.badges.create')"
                    class="inline-block no-underline px-4 py-2 mb-16 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75"
                >
                    Creare Insignă
                </inertia-link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList
                        :items="badges"
                        :title="'Insigne pentru utilizatori'"
                        :description="'Afișează toate insignele disponibile'"
                        :columns="columns"
                        :getRoute="'admin-gamification.badges.index'"
                        :extraLabel="detailsLabel"
                        :filters="filters"
                        :prevFilters="prevFilters"
                        :updateRoute="'admin-gamification.badges.edit'"
                        :deleteRoute="'admin-gamification.badges.destroy'"
                        class="p-4"
                    >
                    </GenericExpandedList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericExpandedList from "@/Components/GenericExpandedList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        AuthenticatedLayout,
        GenericExpandedList,
    },
    props: {
        badges: {
            type: Object,
            required: true,
        },
        prevFilters: {
            type: Array,
            required: true,
        },
        categories: Array,
    },

    computed: {
        columns() {
            return [
                { name: "name", label: "Insignă" },
                { name: "score", label: "Scor", sorting: true },
                { name: "category", label: "Categorie" },
                { name: "created_at", label: "Data Creării", sorting: true },
            ];
        },

        detailsLabel() {
            return [{ name: "description", label: "Descriere" }];
        },

        filters() {
            return [
                {
                    model: "searchName",
                    label: "Caută după nume insignă",
                    type: "text",
                    placeholder: "Introduceți nume insignă",
                },
                {
                    model: "searchCategory",
                    label: "Caută după categorie",
                    type: "select",
                    placeholder: "Selectați o categorie",
                    options: this.categories.map((c) => ({
                        value: c.value,
                        label: c.label,
                    })),
                },
            ];
        },
    },
};
</script>
