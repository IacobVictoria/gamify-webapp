<template>
    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white p-3.5 shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ title }}</h2>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <FormInput v-for="(field, index) in fields" :key="index" :field="field" v-model="form[field.name]"
                        :error="errors[field.name]" />
                </div>
                <div v-if="includeFile">
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                    <input id="image" @input="form.image = $event.target.files[0]" type="file"
                        @change="handleImageUpload" class="mt-1 block w-full border-gray-300 shadow-sm rounded-md" />
                    <p v-if="imagePreview" class="mt-2 text-sm text-gray-500">Preview:</p>
                    <img v-if="imagePreview" :src="imagePreview" alt="Image preview"
                        class="mt-2 max-h-32 rounded-lg shadow" />
                </div>
                <div class="flex items-center justify-end mt-6 gap-x-4">
                    <button type="submit" v-if="this.form.isDirty"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import FormInput from './FormInput.vue';
import { useForm, router } from '@inertiajs/vue3';
import { toRefs } from 'vue';

export default {
    name: 'GenericEditForm',

    components: {
        FormInput,
    },

    props: {
        updateRoute: {
            type: String,
            required: true,
        },
        fields: {
            type: Array,
            required: true,
        },
        initialData: {
            type: Object,
            required: true,
        },
        title: {
            type: String,
            required: true
        },
        isFile: {
            type: Boolean,
            required: false
        },
        aditionalData: {
            type: Object,
            required: false
        }
    },

    data() {
        return {
            includeFile: this.isFile,
            form: this.createForm(),
            errors: {},
            imagePreview: this.initialData.image_path || this.initialData.image_url || null,
            imageFile: null

        };
    },
    methods: {
        createForm() {
            let formFields = {};
            if (this.aditionalData) {
                formFields[this.aditionalData.name] = null;
            }
            this.fields.forEach((field) => {
                if (field.type === 'checkbox') {
                    formFields[field.name] = !!this.initialData[field.name];
                } else {
                    formFields[field.name] = this.initialData[field.name] || '';
                }
            });
            console.log(useForm(formFields))
            return useForm(formFields);
        },

        submit() {
            router.post(this.updateRoute, {
                ...this.form.data(),
                _method: 'put'
            })
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
                this.imageFile = file;
            }
        }
    },
};
</script>
