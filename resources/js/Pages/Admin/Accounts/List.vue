<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList :title="'Accounts'" :description="'Here you can see all the accounts.'"
                        :items="accounts" :entityName="'accounts'" :filters="filters" :getRoute="'admin.accounts.index'"
                        :createRoute="'admin.accounts.create'" :editRoute="'admin.accounts.edit'"
                        :deleteRoute="'admin.accounts.destroy'" :columns="columns" :prevFilters="prevFilters"
                        class="p-4" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from '@/Components/GenericList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
export default {
    name: 'Admin/Accounts/List',

    components: {
        AuthenticatedLayout,
        GenericList,
        Head
    },

    props: {
        accounts: {
            type: Array,
            required: true
        },

        roles: {
            type: Array,
            required: true
        },

        prevFilters: {
            type: Array,
            required: true
        }
    },

    computed: {
        columns() {
            return [
                { name: 'name', label: 'Name' },
                { name: 'email', label: 'Email' },
                { name: 'roles', label: 'Role' },
                { name: 'created_at', label: 'Created' },
            ];
        },

        filters() {
            return [
                { model: 'searchName', label: 'Search by Name', type: 'text', placeholder: 'Search by name' },
                { model: 'searchEmail', label: 'Search by Email', type: 'text', placeholder: 'Search by email' },
                { model: 'searchRole', label: 'Search by role', type: 'select', placeholder: 'Search by role', options: this.roles.map(role => ({ value: role.name, label: role.name })) },
            ];
        },
    },
}
</script>