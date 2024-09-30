<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList :title="'Products'" :description="'Here you can see all the products.'"
                        :items="products" :entityName="'products'" :filters="filters" :getRoute="'admin.products.index'"
                        :createRoute="'admin.products.create'" :editRoute="'admin.products.edit'"
                        :deleteRoute="'admin.products.destroy'" :columns="columns" :prevFilters="prevFilters"
                        class="p-4" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from '@/Components/GenericList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    name: 'Admin/Products/List',

    components: {
        AuthenticatedLayout,
        GenericList
    },

    props: {
        products: {
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
                { name: 'price', label: 'Price' },
                { name: 'score', label: 'Score' },
                { name: 'created_at', label: 'Created' },
            ];
        },

        filters() {
            return [
                { model: 'searchName', label: 'Search by Name', type: 'text', placeholder: 'Search by name' },
                { model: 'searchPrice', label: 'Search by Price', type: 'number', placeholder: 'Search by Price' },
                { model: 'searchScore', label: 'Search by Score', type: 'number', placeholder: 'Search by Score' }
            ]
        },
    }
}
</script>