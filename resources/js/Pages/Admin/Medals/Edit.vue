<template>
    <AuthenticatedLayout>
        <Head title="Update Medal" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Medal</h2>
        </template>

        <GenericEditForm
            :title="'Update Medal'"
            :fields="fields"
            :initial-data="medal"
            :update-route="getUpdateRoute"
        />
    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from '@/Components/GenericEditForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        GenericEditForm,
        Head
    },

    props: {
        medal: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            fields: [
                {
                    name: 'tier',
                    label: 'Tier',
                    type: 'input',
                    inputType: 'text',
                    placeholder: 'Enter tier (bronze/silver/gold)',
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
    },

    computed: {
        getUpdateRoute() {
            return route('admin-gamification.medals.update', { medalId: this.medal.id });
        }
    }
};
</script>