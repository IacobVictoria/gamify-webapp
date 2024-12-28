<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Surveys</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Iterează prin survey-uri și creează carduri -->
                <div v-for="survey in surveys" :key="survey.id" class="bg-white shadow-md rounded-lg p-4"
                    @click="goToSurveyDetail(survey.id)">
                    <h2 class="text-xl font-semibold">{{ survey.title }}</h2>
                    <p class="text-gray-600">{{ survey.description || 'No description available' }}</p>
                    <p :class="{
                        'text-green-500 font-semibold': survey.is_published,
                        'text-red-500 font-semibold': !survey.is_published,
                    }">
                        {{ survey.is_published ? 'Published' : 'Not Published' }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    props: {
        surveys: Array
    },
    components: {
        AuthenticatedLayout
    },
    methods: {
        goToSurveyDetail(surveyId) {
            this.$inertia.visit(route('admin.nps.show.survey', { surveyId: surveyId }));
        }
    }
}

</script>