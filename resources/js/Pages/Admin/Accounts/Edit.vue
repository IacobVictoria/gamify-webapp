<template>
    <AuthenticatedLayout>
        <Head title="Actualizare Conturi" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizare Conturi
            </h2>
        </template>

        <GenericEditForm
            :updateRoute="getUpdateRoute"
            :fields="formFields"
            :initialData="account"
            :title="'Actualizare Cont'"
        />
    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from "@/Components/GenericEditForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        GenericEditForm,
        Head,
        AuthenticatedLayout,
    },
    props: {
        account: {
            type: Object,
            required: true,
        },

        roles: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            formFields: [
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
                    hint: "Lăsați câmpul gol dacă nu doriți să schimbați parola.",
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
    computed: {
        getUpdateRoute() {
            return route("super-admin.accounts.update", {
                accountId: this.account.id,
            });
        },
    },
};
</script>
