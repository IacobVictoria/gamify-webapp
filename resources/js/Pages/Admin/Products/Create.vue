<template>
    <AuthenticatedLayout>
        <Head title="Create Products" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Creare produse
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Creează produs'"
        />
    </AuthenticatedLayout>
</template>

<script>
import GenericCreateForm from "@/Components/GenericCreateForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        GenericCreateForm,
        Head,
        AuthenticatedLayout,
    },
    data() {
        return {
            createRoute: route("admin.products.store"),
            fields: [
                {
                    name: "name",
                    label: "Nume",
                    type: "input",
                    inputType: "text",
                    autocomplete: "name",
                    placeholder: "Introdu numele",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "category",
                    label: "Categorie",
                    type: "input",
                    inputType: "text",
                    autocomplete: "category",
                    placeholder: "Introdu categoria",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "description",
                    label: "Descriere",
                    type: "textarea",
                    placeholder: "Introdu descrierea",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "score",
                    label: "Scor",
                    type: "input",
                    inputType: "number",
                    autocomplete: "score",
                    placeholder: "Introdu scorul",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "price",
                    label: "Preț",
                    type: "input",
                    inputType: "number",
                    autocomplete: "price",
                    placeholder: "Introdu prețul",
                    colSpan: "sm:col-span-6",
                    step: "0.01",
                },
                {
                    name: "stock",
                    label: "Stoc",
                    type: "input",
                    inputType: "number",
                    autocomplete: "stock",
                    placeholder: "Introdu stocul",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
};
</script>
