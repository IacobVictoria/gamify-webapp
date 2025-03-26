<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericList :title="'Activities'" :description="'Here you can see all the activities.'"
                        :items="activities" :entityName="'activities'" :filters="filters" :getRoute="'admin-gamification.activities.index'"
                        :createRoute="'admin-gamification.activities.create'" :editRoute="'admin-gamification.activities.edit'"
                        :deleteRoute="'admin-gamification.activities.destroy'" :columns="columns" :prevFilters="prevFilters"
                        class="p-4" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from '@/Components/GenericList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
export default {
    name: 'Admin/Accounts/List',

    components: {
        AuthenticatedLayout,
        GenericList,
        Head,
      
    },

    props: {
        activities: {
            type: Object,
            required: true
        },

        prevFilters: {
            type: Array,
            required: true
        },
        types: {
            type: Array,
            required: true
        }
    },

    computed: {
        columns() {
            return [
                { name: 'title', label: 'Title' },
                { name: 'score', label: 'Score', sorting:true },
                { name: 'type', label: 'Type' },
                { name: 'created_at', label: 'Created', sorting:true },
                { name: 'is_published', label: 'Published' },
            ];
        },

        filters() {
            return [
                { model: 'searchTitle', label: 'Search by Title', type: 'text', placeholder: 'Search by title' },
                {
                    model: 'searchPublished', label: 'Search by published', type: 'select', placeholder: 'Search by published', options: [
                        { value: '', label: 'All' },
                        { value: 'true', label: 'Published' },
                        { value: 'false', label: 'Unpublished' }
                    ]
                },
                { model: 'searchType', label: 'Search by type', type: 'select', placeholder: 'Search by type', options: this.types.map(type => ({ value: type.value, label: type.label })) },
            ];
        },
    },
}
</script>