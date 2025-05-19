<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✍️ Manager Quizuri
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <inertia-link
                    :href="route('admin-gamification.user_quiz.create')"
                    class="inline-block no-underline px-4 py-2 mb-16 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75"
                >
                    Creează Quiz
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
                { name: "title", label: "Titlu Quiz" },
                { name: "difficulty", label: "Dificultate" },
                { name: "max_score", label: "Scor Total" },
                { name: "created_at", label: "Creat la", sorting: true },
                { name: "is_published", label: "Publicat" },
            ];
        },
        filters() {
            return [
                {
                    model: "searchTitle",
                    label: "Caută după titlu",
                    type: "text",
                    placeholder: "Introduceți titlul",
                },
                {
                    model: "searchQuestion",
                    label: "Caută după întrebare",
                    type: "text",
                    placeholder: "Introduceți întrebarea",
                },
                {
                    model: "searchDifficulty",
                    label: "Caută după dificultate",
                    type: "select",
                    placeholder: "Selectați dificultatea",
                    options: this.difficulties.map((d) => ({
                        value: d.value,
                        label: d.label,
                    })),
                },
                {
                    model: "searchPublished",
                    label: "Caută după publicare",
                    type: "select",
                    placeholder: "Selectați starea",
                    options: [
                        { value: "", label: "Toate" },
                        { value: "true", label: "Publicate" },
                        { value: "false", label: "Nepublicate" },
                    ],
                },
            ];
        },
    },
};
</script>
