<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
          <h1 class="text-3xl font-bold mb-6 text-blue-700">Înțelegerea sondajelor NPS</h1>

<p class="mb-6 text-gray-700 text-lg">
    <strong>Scorul Net Promoter (NPS)</strong> este un instrument puternic care ajută afacerile să măsoare loialitatea
    și satisfacția clienților. Prin întrebări bine formulate, companiile pot identifica promotorii, neutrii și
    detractorii, obținând perspective valoroase despre sentimentele clienților. Un sondaj NPS bine conceput poate duce
    la o implicare mai mare, o retenție crescută a clienților și feedback valoros pentru creșterea afacerii.
</p>

<p class="mb-6 text-gray-700 text-lg">
    Pentru a obține cele mai bune rezultate din sondajele NPS, ia în considerare:
<ul class="list-disc list-inside text-gray-600 mt-2">
    <li>Păstrarea sondajului scurt și la obiect.</li>
    <li>Folosirea unui limbaj clar și concis.</li>
    <li>Adăugarea unei întrebări deschise pentru a colecta feedback calitativ.</li>
    <li>Crearea unui design atractiv și interactiv pentru sondaj.</li>
</ul>
</p>

<inertia-link :href="route('admin.nps.survey.create')"
    class="inline-block bg-blue-600 text-white no-underline mb-12 font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
    Creează un sondaj NPS nou!
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
                        {{ survey.is_published ? 'Publicat' : 'Nepublicat' }}
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