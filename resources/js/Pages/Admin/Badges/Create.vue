<template>
    <AuthenticatedLayout>

        <Head title="Create Badges" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Badges</h2>
        </template>

        <GenericCreateForm :fields="fields" :createRoute="createRoute" :title="'Create Badge'"></GenericCreateForm>
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
            createRoute: route('admin.badges.store'),
            fields: [
                {
                    name: 'name',
                    label: 'Name',
                    type: 'input',
                    inputType: 'text',
                    autocomplete: 'name',
                    placeholder: 'Enter name',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'description',
                    label: 'Description',
                    type: 'textarea',
                    inputType: 'textarea',
                    autocomplete: 'description',
                    placeholder: 'Enter description',
                    colSpan: 'sm:col-span-6'
                },
                {
                    name: 'score',
                    label: 'Score',
                    type: 'input',
                    inputType: 'number',
                    autocomplete: 'score',
                    placeholder: 'Enter score',
                    colSpan: 'sm:col-span-6'
                },
            ],

        }
    }
}
</script>