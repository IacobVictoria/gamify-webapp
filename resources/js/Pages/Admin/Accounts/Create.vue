<template>
    <AuthenticatedLayout>
        <Head title="Creare Conturi" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Creare Conturi
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Creare Cont'"
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

    props: {
        roles: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            createRoute: route("super-admin.accounts.store"),
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
                    name: "email",
                    label: "Email",
                    type: "input",
                    inputType: "email",
                    autocomplete: "email",
                    placeholder: "Introduceți adresa de email",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "role_ids",
                    label: "Roluri",
                    type: "checkbox-group",
                    options: this.roles.map((role) => ({
                        value: role.id,
                        label: role.name,
                    })),
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "password",
                    label: "Parolă",
                    type: "input",
                    inputType: "password",
                    autocomplete: "new-password",
                    placeholder: "Introduceți parola",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "password_confirmation",
                    label: "Confirmare Parolă",
                    type: "input",
                    inputType: "password",
                    autocomplete: "new-password",
                    placeholder: "Confirmați parola",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
};
</script>
