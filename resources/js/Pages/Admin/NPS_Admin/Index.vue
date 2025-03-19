<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-6 text-blue-700">Understanding NPS Surveys</h1>

            <p class="mb-6 text-gray-700 text-lg">
                The <strong>Net Promoter Score (NPS)</strong> is a powerful tool that helps businesses measure customer
                loyalty
                and satisfaction. By asking the right questions, companies can identify promoters, passives, and
                detractors,
                gaining valuable insights into customer sentiment. A well-designed NPS survey can lead to better
                engagement,
                increased customer retention, and actionable feedback for business growth.
            </p>

            <p class="mb-6 text-gray-700 text-lg">
                To get the most out of your NPS surveys, consider:
            <ul class="list-disc list-inside text-gray-600 mt-2">
                <li>Keeping the survey short and focused.</li>
                <li>Using clear and concise language.</li>
                <li>Including an open-ended question to capture qualitative feedback.</li>
                <li>Making the survey visually appealing and interactive.</li>
            </ul>
            </p>

            <inertia-link :href="route('admin.nps.survey.create')"
                class="inline-block bg-blue-600 text-white no-underline mb-12 font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                Create new NPS Survey!
            </inertia-link>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="survey in surveys" :key="survey.id"
                    class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg cursor-pointer transition duration-200"
                    @click="goToSurveyDetail(survey.id)">
                    <h2 class="text-xl font-semibold text-gray-900">{{ survey.title }}</h2>
                    <p class="text-gray-600 mb-2">{{ survey.description || 'No description available' }}</p>
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