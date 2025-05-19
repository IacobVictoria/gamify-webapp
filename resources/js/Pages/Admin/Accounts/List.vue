<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList
                        :title="'Conturi'"
                        :description="'Aici poți vedea toate conturile.'"
                        :items="accounts"
                        :entityName="'cont'"
                        :filters="filters"
                        :getRoute="'super-admin.accounts.index'"
                        :createRoute="'super-admin.accounts.create'"
                        :editRoute="'super-admin.accounts.edit'"
                        :deleteRoute="'super-admin.accounts.destroy'"
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
import { CheckIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
export default {
    name: "Admin/Accounts/List",

    components: {
        AuthenticatedLayout,
        GenericList,
        Head,
    },

    props: {
        accounts: {
            type: Object,
            required: true,
        },

        roles: {
            type: Array,
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
                { name: "name", label: "Nume" },
                { name: "email", label: "Email" },
                { name: "roles", label: "Rol" },
                { name: "created_at", label: "Creat la" },
            ];
        },

        filters() {
            return [
                {
                    model: "searchName",
                    label: "Caută după nume",
                    type: "text",
                    placeholder: "Introduceți numele",
                },
                {
                    model: "searchEmail",
                    label: "Caută după email",
                    type: "text",
                    placeholder: "Introduceți adresa de email",
                },
                {
                    model: "searchRole",
                    label: "Caută după rol",
                    type: "select",
                    placeholder: "Selectați un rol",
                    options: this.roles.map((role) => ({
                        value: role.name,
                        label: role.name,
                    })),
                },
            ];
        },
    },
};
</script>
