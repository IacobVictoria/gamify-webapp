<template>
    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white p-3.5 shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                {{ title }}
            </h2>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <FormInput
                        v-for="(field, index) in fields"
                        :key="index"
                        :field="field"
                        v-model="form[field.name]"
                        :error="errors[field.name]"
                    />
                </div>
                <div v-if="includeFile">
                    <label
                        for="image"
                        class="block text-sm font-medium text-gray-700"
                        >Încarcă imaginea</label
                    >
                    <input
                        id="image"
                        type="file"
                        @change="handleImageUpload"
                        accept="image/*"
                        class="mt-1 block w-full border-gray-300 shadow-sm rounded-md"
                    />
                    <p v-if="imagePreview" class="mt-2 text-sm text-gray-500">
                          Previzualizare:
                    </p>
                    <img
                        v-if="imagePreview"
                        :src="imagePreview"
                        alt="Image preview"
                        class="mt-2 max-h-32 rounded-lg shadow"
                    />
                </div>
                <div class="flex items-center justify-end mt-6 gap-x-4">
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition"
                    >
                        Salvează
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import FormInput from "./FormInput.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    name: "GenericCreateForm",

    components: {
        FormInput,
    },
    props: {
        createRoute: {
            type: String,
            required: true,
        },
        fields: {
            type: Array,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        objectId: {
            type: String,
        },
        isFile: {
            type: Boolean,
        },
    },

    data() {
        return {
            form: this.createForm(),
            errors: {},
            imagePreview: null,
            includeFile: this.isFile,
            imageFile: null,
        };
    },

    methods: {
        createForm() {
            let formFields = {};
            this.fields.forEach((field) => {
                formFields[field.name] =
                    field.type === "checkbox-group" ? [] : "";
            });
            return useForm(formFields);
        },

        submit() {
            const formData = new FormData();

            for (const [key, value] of Object.entries(this.form.data())) {
                if (Array.isArray(value)) {
                    // Trimite multiple valori corect
                    value.forEach((val) => formData.append(`${key}[]`, val));
                } else {
                    formData.append(key, value);
                }
            }

            if (this.imageFile) {
                formData.append("image", this.imageFile);
            }
            console.log(formData.image);
            this.$inertia.post(this.createRoute, formData, {
                onError: (errors) => {
                    console.log("Form not submitted successfully:", errors);
                    this.errors = errors;
                },
                onSuccess: () => {
                    console.log("Form submitted successfully");
                    this.form.reset();
                },
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageFile = file;
                this.imagePreview = URL.createObjectURL(file);
            }
        },
    },
};
</script>
