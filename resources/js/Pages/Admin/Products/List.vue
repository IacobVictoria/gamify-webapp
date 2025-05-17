<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Produse'"
                        :description="'Aici poți vedea toate produsele.'"
                        :items="products"
                        :entityName="'products'"
                        :filters="filters"
                        :getRoute="'admin.products.index'"
                        :editRoute="'admin.products.edit'"
                        :deleteRoute="'admin.products.destroy'"
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
        products: {
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
                { name: "name", label: "Denumire" },
                { name: "price", label: "Preț", sorting: true },
                { name: "score", label: "Scor", sorting: true },
                { name: "created_at", label: "Creat la", sorting: true },
                { name: "is_published", label: "Publicat" },
            ];
        },

        filters() {
            return [
                {
                    model: "searchName",
                    label: "Caută după nume",
                    type: "text",
                    placeholder: "Introduceți un nume",
                },
                {
                    model: "searchPublished",
                    label: "Filtrare după publicare",
                    type: "select",
                    placeholder: "Alege starea de publicare",
                    options: [
                        { value: "", label: "Toate" },
                        { value: "true", label: "Publicate" },
                        { value: "false", label: "Nepublicate" },
                    ],
                },
            ];
        },
    },
};
</script>
