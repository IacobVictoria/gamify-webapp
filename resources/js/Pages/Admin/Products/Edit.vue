<template>
    <AuthenticatedLayout>

        <Head title="Update Product" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Product</h2>
        </template>

        <GenericEditForm :updateRoute="getUpdateRoute" :fields="fields" :initialData="product" :title="'Update Product'"
            :isFile="true" :aditionalData="imageField" />
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
        product: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            fields: [
                {
                    name: 'category',
                    label: 'Categories',
                    type: 'select',
                    options: this.categories,
                    autocomplete: 'categories',
                    placeholder: 'Categories',
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
            ]
            , imageField: {
                name: 'image',
                label: 'Image',
                type: 'file',
                inputType: 'file',
                colSpan: 'sm:col-span-6'
            },

        };
    },
    computed: {
        getUpdateRoute() {
            return route('admin.products.update', { productId: this.product.id });
        },
    },
};
</script>
