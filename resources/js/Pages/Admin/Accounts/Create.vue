<template>
    <AuthenticatedLayout>

        <Head title="Create Accounts" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Accounts</h2>
        </template>

        <GenericCreateForm :fields="fields" :createRoute="createRoute" :title="'Create Account'"></GenericCreateForm>

    </AuthenticatedLayout>
</template>

<script>
import GenericCreateForm from '@/Components/GenericCreateForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'Accounts/Create',

    components: {
        AuthenticatedLayout,
        GenericCreateForm,
        Head
    },

    props: {
        roles: {
            type: Array,
            required: true
        }
    },

    data() {
        return {

            createRoute: route('admin.accounts.store'),
            fields: [
                {
                    name: 'name',
                    label: 'Name',
                    type: 'input', 
                    inputType: 'text',
                    autocomplete: 'name',
                    placeholder: 'Enter name',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'email',
                    label: 'Email',
                    type: 'input',
                    inputType: 'email',
                    autocomplete: 'email',
                    placeholder: 'Enter email',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'role_id',
                    label: 'Role',
                    type: 'select',
                    options: this.roles.map(role => ({
                        value: role.id,
                        label: role.name
                    })),
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'password',
                    label: 'Password',
                    type: 'input',
                    inputType: 'password',
                    autocomplete: 'new-password',
                    placeholder: 'Enter password',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'password_confirmation',
                    label: 'Confirm Password',
                    type: 'input',
                    inputType: 'password',
                    autocomplete: 'new-password',
                    placeholder: 'Confirm password',
                    colSpan: 'sm:col-span-6'
                },
            ]
        }
    }
}
</script>