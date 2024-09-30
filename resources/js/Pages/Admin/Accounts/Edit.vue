<template>
    <AuthenticatedLayout>

        <Head title="Update Accounts" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Accounts</h2>
        </template>

        <GenericEditForm :updateRoute="getUpdateRoute" :fields="formFields" :initialData="user" :title="'Update Account'" />
    </AuthenticatedLayout>

</template>

<script>
import GenericEditForm from '@/Components/GenericEditForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        GenericEditForm,
        Head,
        AuthenticatedLayout

    },
    props: {
        user: {
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
            ],
        };
    },
    computed: {
        getUpdateRoute() {
            return route('admin.accounts.update', { accountId: this.user.id }); 
        },
    },
};
</script>
