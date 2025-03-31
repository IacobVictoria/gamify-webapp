<template>
    <AuthenticatedLayout>

        <Head title="Create Roles" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Roles</h2>
        </template>

        <GenericCreateForm 
        :fields="fields" 
        :createRoute="createRoute" 
        :title="'Create Role'" 
    />
    </AuthenticatedLayout>

</template>

<script>
import GenericCreateForm from '@/Components/GenericCreateForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        GenericCreateForm,
        Head,
        AuthenticatedLayout,

    },
    data() {
        return {
            createRoute: route('super-admin.roles.store'),

            fields: [
                {
                    name: 'name',
                    label: 'Name',
                    type: 'input',
                    inputType: 'text',
                    autocomplete: 'name',
                    placeholder: 'Enter name',
                    colSpan: 'sm:col-span-6'
                }
            ]

        };
    },
};
</script>
