<!-- <template>
    <div class="w-full relative">
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <Menu as="div" class="relative inline-block text-left w-full">
            <div>
                <MenuButton
                    class="inline-flex w-full justify-between items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <span>{{ selectedLabel }}</span>
                    <ChevronDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </MenuButton>
            </div>

            <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <MenuItems
                    class="absolute h-28 z-10 mt-2 w-full origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-y-scroll">
                    <div class="py-1">
                        <MenuItem v-for="option in options" :key="option.value" v-slot="{ active }">
                        <button @click="selectOption(option)"
                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']">
                            {{ option.label }}
                        </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
        <span v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</span>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    modelValue: [String, Number, Boolean, Object],
    options: {
        type: Array,
        required: true,
        default: () => [],
    },
    label: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
})

const emit = defineEmits(['update:modelValue'])

const selectedLabel = computed(() => {
    const selectedOption = props.options.find(option => option.value === props.modelValue)
    return selectedOption ? selectedOption.label : 'Alege o opțiune'
})

const selectOption = (option) => {
    emit('update:modelValue', option.value)
}

</script>

<style scoped>
/* Add any custom styles here if necessary */
</style> -->

<template>
    <div class="w-full relative">
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <div class="relative">
            <select 
                v-model="selectedValue" 
                @change="selectOption" 
                class="block w-full rounded-md border border-gray-300 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled>Alege o opțiune</option>
                <option v-for="option in options" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </div>
        <span v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</span>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    modelValue: [String, Number, Boolean, Object],
    options: {
        type: Array,
        required: true,
        default: () => [],
    },
    label: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);
const selectedValue = ref(props.modelValue);

// Emitere a valorii selectate la schimbare
const selectOption = () => {
    emit('update:modelValue', selectedValue.value);
};
watch(() => props.modelValue, (newVal) => {
    selectedValue.value = newVal;
});
</script>


