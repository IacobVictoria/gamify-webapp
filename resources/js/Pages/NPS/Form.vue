<template>
    <Layout>
        <main>
            <div class="relative isolate">
                <div class="overflow-hidden">
                    <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:px-8 lg:pt-32">
                            <div class="p-4">
                                <!-- Titlul și descrierea Survey-ului -->
                                <div class="mb-4">
                                    <h2 class="text-2xl font-semibold">{{ survey.title }}</h2>
                                    <p class="text-lg text-gray-600">{{ survey.description }}</p>
                                </div>

                                <!-- Întrebările Survey-ului -->
                                <div >
                                    <div v-for="(question, index) in questions" :key="question.id" class="mb-4">
                                        <div class="mb-2 font-medium">{{ question.text }}</div>

                                        <!-- Input text pentru întrebările de tip 'open' -->
                                        <div v-if="question.type === 'open'">
                                            <input v-model="responses[question.id]" type="text"
                                                class="w-full p-2 border border-gray-300 rounded"
                                                placeholder="Your response..." />
                                        </div>

                                        <!-- Scale pentru întrebările de tip 'scale' -->
                                        <div v-if="question.type === 'scale'">
                                            <div class="flex justify-between">
                                                <!-- Afișează extremele scalei doar dacă choices.text este definit -->
                                                <span>{{ question.choices[0]?.text?.split('-')[0]?.trim() || '0'
                                                    }}</span>
                                                <span>{{ question.choices[0]?.text?.split('-')[1]?.trim() || '10'
                                                    }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <!-- Afișează butoane pentru fiecare punct de pe scară -->
                                                <button v-for="n in 11" :key="n - 1" :class="{
                                                    'bg-blue-500 text-white': responses[question.id] === n - 1,
                                                    'bg-gray-200 text-black': responses[question.id] !== n - 1
                                                }" class="w-8 h-8 rounded-full text-center" @click="responses[question.id] = n - 1">
                                                    {{ n - 1 }}
                                                </button>
                                            </div>
                                            <!-- Afișează valoarea selectată -->
                                            <div class="text-center mt-2 font-bold">
                                                Selected: {{ responses[question.id] !== undefined ?
                                                responses[question.id] : "None" }}
                                            </div>
                                        </div>

                                        <!-- Binary (da/nu) pentru întrebările de tip 'binary' -->
                                        <div v-if="question.type === 'binary'">
                                            <label class="mr-4">
                                                <input type="radio" v-model="responses[question.id]" :value="'Yes'"
                                                    class="mr-2" />
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" v-model="responses[question.id]" :value="'No'"
                                                    class="mr-2" />
                                                No
                                            </label>
                                        </div>

                                        <!-- Multiple Choice pentru întrebările de tip 'multiple_choice' -->
                                        <div v-if="question.type === 'multiple_choice'">
                                            <div v-for="choice in question.choices" :key="choice.id" class="mb-2">
                                                <label>
                                                    <input type="radio" v-model="responses[question.id]"
                                                        :value="choice.id" class="mr-2" />
                                                    {{ choice.text }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button @click="submitFeedback" class="bg-blue-500 text-white px-4 py-2 rounded-lg"
                                    :disabled="!isValidFeedback">
                                    Submit Feedback
                                </button>
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
            } catch (error) {
                console.error("Error submitting feedback:", error);
            }
        },
    },
};
</script>