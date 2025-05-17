<template>
    <Layout>
        <main>
            <div class="relative isolate mx-32">
                <div class="overflow-hidden">
                    <div class="mx-auto px-6 pb-16 pt-24 sm:pt-40">
                        <div class="p-8 bg-white shadow-2xl rounded-lg">
                            <!-- Titlul și descrierea Survey-ului -->
                            <div class="mb-8 text-center">
                                <h2 class="text-3xl font-bold text-indigo-600 mb-2 flex items-center justify-center">
                                    📝 {{ survey.title }}
                                </h2>
                                <p class="text-lg text-gray-600">{{ survey.description }}</p>
                            </div>

                            <!-- Întrebările Survey-ului -->
                            <div class="space-y-6">
                                <div v-for="(question, index) in questions" :key="question.id" class="mb-6">
                                    <div class="mb-3 font-semibold text-gray-800 text-lg flex items-center">
                                        <i class="fa fa-question-circle text-indigo-500 mr-2"></i> {{ question.text }}
                                    </div>

                                    <!-- Input text pentru întrebările de tip 'open' -->
                                    <div v-if="question.type === 'open'">
                                        <input v-model="responses[question.id]" type="text"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Your response..." />
                                    </div>

                                    <!-- Scale pentru întrebările de tip 'scale' -->
                                    <div v-if="question.type === 'scale'" class="text-center">
                                        <div class="flex justify-between text-sm text-gray-500 mb-4">
                                            <span>😡 {{ question.choices[0]?.text?.split('-')[0]?.trim() || '0'
                                            }}</span>
                                            <span>😊 {{ question.choices[0]?.text?.split('-')[1]?.trim() || '10'
                                            }}</span>
                                        </div>
                                        <div class="flex justify-center items-center space-x-2 mt-2">
                                            <button v-for="n in 11" :key="n - 1" :class="{
                                                'bg-indigo-500 text-white scale-110': responses[question.id] === n - 1,
                                                'bg-gray-200 text-black': responses[question.id] !== n - 1
                                            }" class="w-10 h-10 rounded-full text-lg font-semibold focus:outline-none transition-all hover:scale-110"
                                                @click="responses[question.id] = n - 1">
                                                {{ n - 1 }}
                                            </button>
                                        </div>
                                        <div class="text-center mt-2 font-bold">
                                            ✅ Selected: {{ responses[question.id] !== undefined ? responses[question.id]
                                                : "None" }}
                                        </div>
                                    </div>

                                    <!-- Binary (da/nu) pentru întrebările de tip 'binary' -->
                                    <div v-if="question.type === 'binary'" class="flex  space-x-4 mt-2">
                                        <button @click="responses[question.id] = 'Yes'"
                                            :class="{ 'bg-green-500 text-white': responses[question.id] === 'Yes', 'bg-gray-200 text-black': responses[question.id] !== 'Yes' }"
                                            class="w-32 flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium focus:outline-none hover:scale-105 transition">
                                            ✅ Da
                                        </button>
                                        <button @click="responses[question.id] = 'No'"
                                            :class="{ 'bg-red-500 text-white': responses[question.id] === 'No', 'bg-gray-200 text-black': responses[question.id] !== 'No' }"
                                            class="w-32 flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium focus:outline-none hover:scale-105 transition">
                                            ❌ Nu
                                        </button>
                                    </div>

                                    <!-- Multiple Choice pentru întrebările de tip 'multiple_choice' -->
                                    <div v-if="question.type === 'multiple_choice'" class="mt-2">
                                        <div v-for="choice in question.choices" :key="choice.id" class="mb-2">
                                            <button @click="responses[question.id] = choice.id"
                                                :class="{ 'bg-indigo-500 text-white': responses[question.id] === choice.id, 'bg-gray-200 text-black': responses[question.id] !== choice.id }"
                                                class="w-full flex items-center justify-start gap-2 px-4 py-3 rounded-lg font-medium">
                                                ✅ {{ choice.text }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buton Submit (mic și centrat) -->
                            <div class="flex justify-center mt-6">
                                <button @click="submitFeedback"
                                    class="bg-indigo-600 hover:bg-indigo-700 w-40 text-white px-4 py-2 rounded-lg text-md font-medium shadow flex items-center justify-center gap-2"
                                    :disabled="!isValidFeedback">
                                    🚀 Salvează
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';

export default {
    name: "NpsFeedback",
    props: {
        survey: {
            type: Object,
            required: true,
        },
        questions: {
            type: Array,
            required: true,
        },
    },
    components: {
        Layout
    },
    mounted() {
        console.log(this.survey.id);
    },
    data() {
        return {
            responses: {},
        };
    },
    computed: {
        isValidFeedback() {
            return Object.keys(this.responses).length === this.questions.length;
        },
    },
    methods: {
        async submitFeedback() {
            try {
                // Trimite răspunsurile la API-ul backend
                await axios.post("/nps/form/store", {
                    survey_id: this.survey.id,
                    responses: this.responses,
                });
                alert("Feedback submitted successfully!");
                this.resetForm();
            } catch (error) {
                console.error("Error submitting feedback:", error);
            }
        },
        resetForm() {
            this.responses = {};
        },
    },
};
</script>
<style scoped>
.w-12 {
    width: 3rem;
    height: 3rem;
}

.scale-125 {
    transform: scale(1.25);
    transition: transform 0.2s ease-in-out;
}
</style>