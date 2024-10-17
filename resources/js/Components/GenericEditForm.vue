<template>
    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white p-3.5 shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{title}}</h2>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <FormInput v-for="(field, index) in fields" :key="index" :field="field" v-model="form[field.name]"
                        :error="errors[field.name]" />
                </div>
                <div class="flex items-center justify-end mt-6 gap-x-4">
                    <button type="submit"
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
import { useForm } from '@inertiajs/vue3';
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
        }
    },

    data() {
        return {
            form: this.createForm(),
            errors: {},
        };
    },
    methods: {
        createForm() {
            let formFields = {};
            this.fields.forEach((field) => {
                formFields[field.name] = this.initialData[field.name] || '';
                console.log(`Initial value for ${field.name}:`, formFields[field.name]);
            });
            return useForm(formFields);
        },

        submit() {
            this.form.put(this.updateRoute, {
                onError: (errors) => {
                    console.log('NOT submitted successfully:', errors);
                    this.errors = errors;
                },
                onSuccess: () => {
                    console.log('Form submitted successfully:');
                    this.form.reset();
                    this.$emit('close');
                },
            });
        },
    },
};
</script>
