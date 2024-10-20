<template>
    <div v-if="filter.type === 'sorting'"> 
        <label :for="filter.id" class="block text-sm font-medium text-gray-700">{{ filter.label }}</label>
        <select v-model="selectedOption" @change="handleChange">
            <option value="" disabled>{{ filter.placeholder }}</option>
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props: {
        filter: {
            type: Object,
            required: true,
        },
        modelValue: { 
            type: [String, Number, Array],
            default: '',
        },
        options: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            selectedOption: this.modelValue || '', 
        }
    },

    emits: ['update:modelValue'], 

    methods: {
        handleChange() {
            this.$emit('update:modelValue', this.selectedOption); 
        },
    },
    watch: {
        modelValue(newVal) {
            this.selectedOption = newVal || ''; 
        },
    },
};
</script>
