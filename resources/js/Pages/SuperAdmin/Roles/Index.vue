<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList :title="'Roles'" :description="'Here you can see all the roles.'"
                        :items="roles" :entityName="'roles'" :filters="filters" :getRoute="'super-admin.roles.index'"
                        :createRoute="'super-admin.roles.create'" :editRoute="'super-admin.roles.edit'"
                        :deleteRoute="'super-admin.roles.destroy'" :columns="columns" :prevFilters="prevFilters"
                        class="p-4" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from '@/Components/GenericList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CheckIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
export default {
    name: 'SuperAdmin/Roles/List',

    components: {
        AuthenticatedLayout,
        GenericList,
        Head,
      
    },

    props: {
        roles: {
            type: Object,
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
                { name: 'created_at', label: 'Created' },
            ];
        },

        filters() {
            return [
                { model: 'searchName', label: 'Search by Name', type: 'text', placeholder: 'Search by name' },
            ];
        },
    },
}
</script>