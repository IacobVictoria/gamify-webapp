<template>
    <AuthenticatedLayout>
        <Head title="Actualizare Insignă" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizare Insignă
            </h2>
        </template>

        <GenericEditForm
            :title="'Actualizare Insignă'"
            :fields="fields"
            :initial-data="badge"
            :update-route="getUpdateRoute"
            :isFile="true"
            :aditionalData="imageField"
        >
        </GenericEditForm>
    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from "@/Components/GenericEditForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        GenericEditForm,
    },

    props: {
        badge: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
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
            imageField: {
                name: "image",
                label: "Imagine",
                type: "file",
                inputType: "file",
                colSpan: "sm:col-span-6",
            },
        };
    },
    computed: {
        getUpdateRoute() {
            return route("admin-gamification.badges.update", {
                badgeId: this.badge.id,
            });
        },
    },
};
</script>
