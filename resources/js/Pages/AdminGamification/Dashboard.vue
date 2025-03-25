<template>
    <AuthenticatedLayout :toggleAdminGamification="toggleAdminGamification" ></AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    toggleAdminGamification: Boolean
})
</script>