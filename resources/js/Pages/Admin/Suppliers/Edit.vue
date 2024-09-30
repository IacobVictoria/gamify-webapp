<template>
    <AuthenticatedLayout>

        <Head title="Update Suppliers" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Suppliers</h2>
        </template>

        <GenericEditForm :updateRoute="getUpdateRoute" :fields="formFields" :initialData="supplier" :title="'Update Supplier'" />
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
        supplier: {
            type: Object,
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
                    name: 'phone',
                    label: 'Phone',
                    type: 'input',
                    inputType: 'tel',
                    autocomplete: 'phone',
                    placeholder: 'Enter phone',
                    colSpan: 'sm:col-span-6'
                }
            ],
        };
    },
    computed: {
        getUpdateRoute() {
            return route('admin.suppliers.update', { supplierId: this.supplier.id }); 
        },
    },
};
</script>
