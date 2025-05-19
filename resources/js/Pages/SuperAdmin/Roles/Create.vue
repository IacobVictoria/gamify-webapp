<template>
    <AuthenticatedLayout>
        <Head title="Creare Rol" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Creare Rol
            </h2>
        </template>

        <GenericCreateForm
            :fields="fields"
            :createRoute="createRoute"
            :title="'Creare Rol'"
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
            createRoute: route("super-admin.roles.store"),
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
            ],
        };
    },
};
</script>
