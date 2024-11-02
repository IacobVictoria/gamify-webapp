<template>
    <AuthenticatedLayout>
        <GenericList :title="'Quiz Remarks'" :description="'Here you can see all the feedbacks.'" :items="remarks"
            :entityName="'remarks'" :filters="filters" :getRoute="'admin.quiz_remarks.show'" :extraId="quizId"
            :columns="columns" :prevFilters="prevFilters" class="p-4">
        </GenericList>
    </AuthenticatedLayout>
</template>

<script>
import GenericList from '@/Components/GenericList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    components: {
        AuthenticatedLayout,
        GenericList
    },
    props: {
        remarks: {
            type: Array,
            required: true
        },
        quizId: {
            type: String
        },
        prevFilters: {
            type: Array,
            required: true
        }
    },
    computed: {
        columns() {
            return [
                { name: 'user_name', label: 'Name' },
                { name: 'description', label: 'Description' },
                { name: 'created_at', label: 'Created' },
            ];
        },

        filters() {
            return [
                { model: 'searchName', label: 'Search by Name', type: 'text', placeholder: 'Search by name' },
            ];
        },

    },
    methods: {
        generateRoute(remarkId) {
            // Construiți ruta dinamic cu quizId și remarkId
            return this.$route('admin.quiz_remarks.show', { quizId: this.quizId });
        },
    },
}
</script>