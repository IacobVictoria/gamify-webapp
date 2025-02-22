<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <inertia-link :href="route('admin.badges.create')"
                    class="inline-block px-4 py-2 mb-16 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                    Create badge
                </inertia-link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericExpandedList :items="badges" :title="'Badges for users'" 
                        :description="'Show all Badges available'" :columns="columns" :getRoute="'admin.badges.index'"
                        :extraLabel="detailsLabel" :filters="filters" :prevFilters="prevFilters"
                        :updateRoute="'admin.badges.edit'" :deleteRoute="'admin.badges.destroy'" class="p-4">
                    </GenericExpandedList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import GenericExpandedList from '@/Components/GenericExpandedList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout,
        GenericExpandedList
    },
    props: {
        badges: {
            type: Object,
            required: true
        },
        prevFilters: {
            type: Array,
            required: true
        }
    },

    computed: {
        columns() {
            return [
                { name: 'name', label: 'Badge' },
                { name: 'score', label: 'Score' },
                { name: 'created_at', label: 'created_at' }
            ]
        },

        detailsLabel() {
            return [
                { name: 'description', label: 'Description' }
            ]
        },

        filters() {
            return [
                { model: 'searchName', label: 'Search by user name', type: 'text', placeholder: 'Search by name' },
            ];
        },
    }
}
</script>