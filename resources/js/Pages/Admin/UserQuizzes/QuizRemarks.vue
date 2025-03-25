<template>
    <AuthenticatedLayout>
        <div v-if="$props.remarks.data.length > 0">
            <GenericList :title="'Quiz Remarks'" :description="'Here you can see all the feedbacks.'" :items="remarks"
                :entityName="'remarks'" :filters="filters" :getRoute="'admin-gamification.quiz_remarks.show'" :extraId="quizId"
                :columns="columns" :prevFilters="prevFilters" class="p-4">
            </GenericList>
        </div>
        <div v-else
            class="flex flex-col items-center justify-center bg-gray-100 text-gray-600 p-6 rounded-lg shadow-md mt-6">
            <span class="text-4xl">💬</span>
            <h2 class="text-xl font-semibold mt-2">No remarks yet!</h2>
            <p class="text-sm text-gray-500 mt-1">This quiz has not received any feedback yet.</p>
        </div>
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
            return this.$route('admin-gamification.quiz_remarks.show', { quizId: this.quizId });
        },
    },
}
</script>