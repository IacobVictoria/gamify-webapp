<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                       <h1 class="text-2xl font-bold mb-4">Comparație Produse</h1>
                        <TableComparison :products="products" :recommendations="recommendations"
                            v-if="products.length" />
                        <p v-else class="text-gray-500">Nu există produse pentru comparare.</p>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from "@/Layouts/Layout.vue";
import TableComparison from "./TableComparison.vue"; 

export default {
    components: { TableComparison ,Layout},
    props: {
        products: {
            type: Array,
            required: true 
        },
        recommendations: {
            type: Array,
            required: true
        }
    }
};
</script>