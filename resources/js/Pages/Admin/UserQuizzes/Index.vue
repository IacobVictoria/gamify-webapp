<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <inertia-link :href="route('admin.user_quiz.create')"
                    class="inline-block px-4 py-2 mb-16 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                    Create Quiz
                </inertia-link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericQuizList :items="quizzes" :columns="columns" :getRoute="'admin.user_quiz.index'"
                        :filters="filters" :prevFilters="prevFilters" :updateRoute="'admin.quiz_manager.show'" class="p-4">
                    </GenericQuizList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>

import GenericQuizList from '@/Components/GenericQuizList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    components: {
        AuthenticatedLayout,
        GenericQuizList
    },
    props: {
        quizzes: {
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
                { name: 'title', label: 'Quiz Title', sorting: true },
                { name: 'difficulty', label: 'Difficulty', sorting: true },
                { name: 'max_score', label: 'Score', sorting: true },
                { name: 'created_at', label: 'created_at', sorting: true }
            ]
        },

        filters() {
            return [
                { model: 'searchTitle', label: 'Search by title', type: 'text', placeholder: 'Search by title' },
                { model: 'searchQuestion', label: 'Search by question', type: 'text', placeholder: 'Search by question' }
            ];
        },

    }
}
</script>