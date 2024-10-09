<template>

    <Head title="Achievements" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Achievements</h2>
        </template>
        <div>Medals throught time </div>
        <div v-for="medal in medals" :key="medal.id" class="mb-4">
            <h1 class="text-lg font-semibold">{{ medal.tier }}</h1>
            <p class="text-gray-600">Awarded on: {{ new Date(medal.pivot.created_at).toLocaleDateString() }}</p>
        </div>

        
        <div class="mt-4 xl:mt-0">
           <QrScannerProduct :type="'patch'"></QrScannerProduct>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import QrScannerProduct from '../QrCodes/QrScannerProduct.vue';

const props = defineProps({
    medals: Array,
});

</script>