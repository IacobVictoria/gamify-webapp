<template>
    <AuthenticatedLayout>

        <Head title="Update Badges" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Badge</h2>
        </template>

        <GenericEditForm :title="'Update badge'" :fields="fields" :initial-data="badge" :update-route="getUpdateRoute">

        </GenericEditForm>

    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from '@/Components/GenericEditForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        Head,
        GenericEditForm
    },

    props: {
        badge: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
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
            ]


        }
    },
    computed: {
        getUpdateRoute() {
            return route('admin.badges.update', { badgeId: this.badge.id });
        },
    },
}
</script>