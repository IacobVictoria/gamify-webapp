<template>
    <AuthenticatedLayout>

        <Head title="Update Badges" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Badge</h2>
        </template>

        <GenericEditForm :title="'Update badge'" :fields="fields" :initial-data="badge" :update-route="getUpdateRoute"
            :isFile="true" :aditionalData="imageField">

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
        },
        categories: {
            type: Array,
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
                {
                    name: 'category',
                    label: 'Categories',
                    type: 'select',
                    options: this.categories,
                    autocomplete: 'categories',
                    placeholder: 'Categories',
                    colSpan: 'sm:col-span-6'
                },
            ]
            , imageField: {
                name: 'image',
                label: 'Image',
                type: 'file',
                inputType: 'file',
                colSpan: 'sm:col-span-6'
            },

        }
    },
    computed: {
        getUpdateRoute() {
            return route('admin-gamification.badges.update', { badgeId: this.badge.id });
        },
    },
}
</script>