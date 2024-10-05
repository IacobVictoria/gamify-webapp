<template>
    <div>
        <label :for="filter.id" class="block text-sm font-medium text-gray-700">{{ filter.label }}</label>
        <!-- type text -->
        <template v-if="filter.type === 'text'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                type="text"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </template>

        <!-- type select -->
        <template v-if="filter.type === 'select'">
            <select :value="value" @change="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <option value="">{{ filter.placeholder }}</option>
                <option v-for="option in filter.options" :value="option.value" :key="option.value">{{ option.label }}
                </option>
            </select>
        </template>

        <!-- type number -->

        <template v-if="filter.type === 'number'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                type="number"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </template>

        <!-- type date -->

        <template v-else-if="filter.type === 'date'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                type="date"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </template>

        <template v-if="filter.type === 'sorting'">
            <select :value="value" @change="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <option value="">{{ filter.placeholder }}</option>
                <option value="asc">Ascendent</option>
                <option value="desc">Descendent</option>
            </select>
        </template>

    </div>
</template>

<script>
export default {
    props: {
        filter: {
            type: Object,
            required: true
        },
        value: {
            type: [String, Number, Array],
            default: ''
        }
    },

    emits: ['update:value'],
    methods: {
        updateValue(newValue) {
            this.$emit('update:value', newValue);
        },
    }
}
</script>