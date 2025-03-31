<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout :toggleSuperAdmin="toggleSuperAdmin"> 
        <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        📊 Super Admin Dashboard 
      </h2>
    </template>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    toggleSuperAdmin: Boolean
})
</script>
