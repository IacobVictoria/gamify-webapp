<template>
    <div :class="field.colSpan">
        <label :for="field.name" class="block text-sm font-medium leading-6 text-gray-900">
            {{ field.label }}
        </label>
        <div class="mt-2">
            <input v-if="field.type === 'input'" :id="field.name" :name="field.name" :type="field.inputType"
                :autocomplete="field.autocomplete" :placeholder="field.placeholder" :value="modelValue"
                @input="updateValue"
                class="block w-full rounded-md border border-gray-300 text-gray-900 shadow-sm placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />

            <DropdownInput v-if="field.type === 'select'" :modelValue="modelValue" :options="field.options"
                @update:modelValue="updateValue" />

            <textarea v-if="field.type === 'textarea'" :id="field.name" :name="field.name"
                :placeholder="field.placeholder" :value="modelValue" @input="updateValue"
                class="block w-full rounded-md border border-gray-300 text-gray-900 shadow-sm placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />

            <span v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</span>
        </div>
    </div>
</template>

<script>
import DropdownInput from './DropdownInput.vue';

export default {
    name: 'FormInput',
    components: {
        DropdownInput
    },
    props: {
        field: {
            type: Object,
            required: true,
        },
        modelValue: {
            type: [String, Number, Boolean],
            default: '',
        },
        error: {
            type: String,
            default: '',
        },
    },
    emits: ['update:modelValue'],
    methods: {
        updateValue(event) {
            const value = event.target ? event.target.value : event;
            this.$emit('update:modelValue', value);
        },
    },
};
</script>
