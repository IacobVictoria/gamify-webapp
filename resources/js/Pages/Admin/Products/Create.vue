<template>
    <AuthenticatedLayout>

        <Head title="Create Products" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Products</h2>
        </template>

        <GenericCreateForm 
        :fields="fields" 
        :createRoute="createRoute" 
        :title="'Create Product'" 
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
    props: {
    },
    data() {
        return {
            createRoute: route('admin.products.store'),

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
                    name: 'category',
                    label: 'Category',
                    type: 'input',
                    inputType: 'text',
                    autocomplete: 'category',
                    placeholder: 'Enter category',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'description',
                    label: 'Description',
                    type: 'textarea',
                    placeholder: 'Enter description',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'score',
                    label: 'Score',
                    type: 'input',
                    inputType: 'number',
                    autocomplete: 'score',
                    placeholder: 'Enter score',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'price',
                    label: 'Price',
                    type: 'input',
                    inputType: 'number',
                    autocomplete: 'price',
                    placeholder: 'Enter price',
                    colSpan: 'sm:col-span-6',
                    step: '0.01'
                },
                {
                    name: 'stock',
                    label: 'Stock',
                    type: 'input',
                    inputType: 'number',
                    autocomplete: 'stock',
                    placeholder: 'Enter stock',
                    colSpan: 'sm:col-span-6'
                }
            ]

        };
    },
    computed: {
        getUpdateRoute() {
            return route('admin.accounts.update', { accountId: this.user.id });
        },
    },
};
</script>
