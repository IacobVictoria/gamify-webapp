<template>
    <AuthenticatedLayout>
        <Head title="Create Suppliers" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Adaugă Furnizori
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Adaugă Furnizor'"
        ></GenericCreateForm>
    </AuthenticatedLayout>
</template>

<script>
import GenericCreateForm from "@/Components/GenericCreateForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    name: "Accounts/Create",

    components: {
        AuthenticatedLayout,
        GenericCreateForm,
        Head,
    },

    props: {},

    data() {
        return {
            createRoute: route("admin.suppliers.store"),
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
                    name: "email",
                    label: "Email",
                    type: "input",
                    inputType: "email",
                    autocomplete: "email",
                    placeholder: "Introdu adresa de email",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "phone",
                    label: "Telefon",
                    type: "input",
                    inputType: "tel",
                    autocomplete: "phone",
                    placeholder: "Introdu numărul de telefon",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
};
</script>
