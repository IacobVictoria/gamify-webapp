<template>
    <AuthenticatedLayout :toggleSuperAdmin="toggleSuperAdmin"> </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    toggleSuperAdmin: Boolean
})
</script>
