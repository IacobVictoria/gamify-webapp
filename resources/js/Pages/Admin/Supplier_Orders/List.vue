<template>
    <AuthenticatedLayout>
        <inertia-link :href="route('admin.suppliers_products.index')" class="border bg-gray-700">Purchase
            products</inertia-link>

    </AuthenticatedLayout>

</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    name: 'Admin/Supplier_Orders/List',

    components: {
        AuthenticatedLayout
    },

    props: {

    },


}
</script>