<template>
    <AuthenticatedLayout>
        <Head title="Create Medal" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Medal</h2>
        </template>

        <GenericCreateForm :fields="fields" :createRoute="createRoute" :title="'Create Medal'">
        </GenericCreateForm>
    </AuthenticatedLayout>
</template>

<script>
import GenericCreateForm from '@/Components/GenericCreateForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        GenericCreateForm,
        Head
    },
    data() {
        return {
            createRoute: route('admin-gamification.medals.store'),
            fields: [
                {
                    name: 'tier',
                    label: 'Tier',
                    type: 'input',
                    inputType: 'text',
                    placeholder: 'Enter tier unique',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'threshold',
                    label: 'Minimum Score Threshold',
                    type: 'input',
                    inputType: 'number',
                    placeholder: 'Enter score threshold',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'discount',
                    label: 'Discount (%)',
                    type: 'input',
                    inputType: 'number',
                    placeholder: 'Enter discount amount',
                    colSpan: 'sm:col-span-6'
                }
            ]
        };
    }
};
</script>