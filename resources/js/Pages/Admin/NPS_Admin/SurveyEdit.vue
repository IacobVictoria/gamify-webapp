<template>
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-md shadow-md w-1/3">
            <h2 class="text-lg font-semibold mb-4">Editează sondaj</h2>

            <!-- Survey Title -->
            <input v-model="survey.title" type="text" placeholder="Titlu"
                class="w-full p-2 rounded border mb-4" />

            <!-- Survey Description -->
            <textarea v-model="survey.description" rows="3" placeholder="Descriere"
                class="w-full p-2 rounded border mb-4"></textarea>

            <!-- Is Published -->
            <div class="flex items-center space-x-2 mb-4">
                <input type="checkbox" v-model="isPublished" id="is_published" />
                <label for="is_published" class="text-sm font-medium">Publică sondaj</label>
            </div>

            <!-- Save & Cancel Buttons -->
            <div class="mt-4 flex justify-end gap-2">
                <button @click="updateSurvey" class="bg-blue-500 text-white py-2 px-4 rounded">
                    Salvează sondaj
                </button>
                <button @click="$emit('close')" class="bg-red-500 text-white py-2 px-4 rounded">
                    Anulează
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        surveyData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            survey: { ...this.surveyData },
        };
    },
    computed: {
        isPublished: {
            get() {
                return this.survey.is_published === 1 || this.survey.is_published === true;
            },
            set(value) {
                this.survey.is_published = value ? 1 : 0;
            },
        },
    },
    methods: {
        updateSurvey() {
            if (!this.survey.title) {
                alert('Title is required!');
                return;
            }

            axios
                .put(`/admin/nps/surveys/update/${this.survey.id}`, this.survey)
                .then((response) => {
                    this.$emit('surveyUpdated', response.data.survey);
                    this.$emit('close');
                })
                .catch((error) => {
                    console.error('Error updating survey:', error);
                });
        },
    },
};
</script>
