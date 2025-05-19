<template>
    <AuthenticatedLayout>
        <Head title="Creare Medalie" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Creare Medalie
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Creare Medalie'"
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
    data() {
        return {
            createRoute: route("admin-gamification.medals.store"),
            fields: [
                {
                    name: "tier",
                    label: "Nivel",
                    type: "input",
                    inputType: "text",
                    placeholder:
                        "Introduceți un identificator unic pentru nivel",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "threshold",
                    label: "Prag minim de scor",
                    type: "input",
                    inputType: "number",
                    placeholder: "Introduceți pragul de scor",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "discount",
                    label: "Reducere (%)",
                    type: "input",
                    inputType: "number",
                    placeholder: "Introduceți valoarea reducerii",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
};
</script>
