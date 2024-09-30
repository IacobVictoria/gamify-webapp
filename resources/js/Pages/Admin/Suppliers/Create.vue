<template>
    <AuthenticatedLayout>

        <Head title="Create Suppliers" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Suppliers</h2>
        </template>

        <GenericCreateForm :fields="fields" :createRoute="createRoute" :title="'Create Supplier'"></GenericCreateForm>

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
    },

    data() {
        return {

            createRoute: route('admin.suppliers.store'),
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
                    name: 'phone',
                    label: 'Phone',
                    type: 'input',
                    inputType: 'tel',
                    autocomplete: 'phone',
                    placeholder: 'Enter phone',
                    colSpan: 'sm:col-span-6'
                }
            ]
        }
    }
}
</script>