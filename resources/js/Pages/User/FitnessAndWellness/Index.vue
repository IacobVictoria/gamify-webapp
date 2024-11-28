<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CalorieTracker from './CalorieTracker.vue';
import AiAssistent from './AiAssistent.vue';
</script>

<template>
    <AuthenticatedLayout>
        <CalorieTracker></CalorieTracker>
        <AiAssistent></AiAssistent>
    </AuthenticatedLayout>
</template>