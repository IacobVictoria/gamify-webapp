<template>
    <AuthenticatedLayout>
        <Head title="Creare Insignă" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Creare Insignă
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Creare Insigne'"
            :isFile="true"
        >
        </GenericCreateForm>
    </AuthenticatedLayout>
</template>

<script>
import GenericCreateForm from "@/Components/GenericCreateForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        GenericCreateForm,
        Head,
    },
    props: {
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            createRoute: route("admin-gamification.badges.store"),
            fields: [
                {
                    name: "name",
                    label: "Nume",
                    type: "input",
                    inputType: "text",
                    autocomplete: "name",
                    placeholder: "Introduceți numele",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "description",
                    label: "Descriere",
                    type: "textarea",
                    inputType: "textarea",
                    autocomplete: "description",
                    placeholder: "Introduceți o descriere",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "score",
                    label: "Scor",
                    type: "input",
                    inputType: "number",
                    autocomplete: "score",
                    placeholder: "Introduceți scorul",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "category",
                    label: "Categorie",
                    type: "select",
                    options: this.categories,
                    autocomplete: "categories",
                    placeholder: "Selectați o categorie",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
};
</script>
