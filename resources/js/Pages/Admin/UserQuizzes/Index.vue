<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✍️ Quizzes Manager
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <inertia-link
                    :href="route('admin-gamification.user_quiz.create')"
                    class="inline-block no-underline px-4 py-2 mb-16 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75"
                >
                    Create Quiz
                </inertia-link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <GenericQuizList
                        :items="quizzes"
                        :columns="columns"
                        :getRoute="'admin-gamification.user_quiz.index'"
                        :filters="filters"
                        :prevFilters="prevFilters"
                        :updateRoute="'admin-gamification.quiz_manager.show'"
                        class="p-4"
                    >
                    </GenericQuizList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import GenericQuizList from "@/Components/GenericQuizList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
    components: {
        AuthenticatedLayout,
        GenericQuizList,
    },
    props: {
        quizzes: {
            type: Object,
            required: true,
        },
        prevFilters: {
            type: Array,
            required: true,
        },
        difficulties: Array,
    },

    computed: {
        columns() {
            return [
                { name: "title", label: "Quiz Title" },
                { name: "difficulty", label: "Difficulty" },
                { name: "max_score", label: "Total Score" },
                { name: "created_at", label: "Created At", sorting: true },
                { name: 'is_published', label: 'Published' },
            ];
        },

        filters() {
            return [
                {
                    model: "searchTitle",
                    label: "Search by title",
                    type: "text",
                    placeholder: "Search by title",
                },
                {
                    model: "searchQuestion",
                    label: "Search by question",
                    type: "text",
                    placeholder: "Search by question",
                },
                {
                    model: "searchDifficulty",
                    label: "Search by difficulty",
                    type: "select",
                    placeholder: "Select difficulty",
                    options: this.difficulties.map((d) => ({
                        value: d.value,
                        label: d.label,
                    })),
                },
                {
                    model: "searchPublished",
                    label: "Search by published",
                    type: "select",
                    placeholder: "Search by published",
                    options: [
                        { value: "", label: "All" },
                        { value: "true", label: "Published" },
                        { value: "false", label: "Unpublished" },
                    ],
                },
            ];
        },
    },
};
</script>
