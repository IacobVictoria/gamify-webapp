<template>
    <div>
        <h3 class="text-base font-semibold text-gray-900">Create Discount</h3>
        <div>
            <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
            <div>{{ props.selectedDate }}</div>
        </div>

        <input v-model="formData.title" type="text" placeholder="Enter Discount Title" class="input mt-2" />
        <input v-model="formData.description" type="text" placeholder="Enter Discount Description" class="input mt-2" />
        <div class="mt-5 sm:mt-6">
            <button type="button"
                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                @click="submitForm">
                Submit
            </button>

            <button type="button"
                class="inline-flex w-full justify-center mt-3 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200"
                @click="closeForm">
                Close
            </button>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    selectedDate: String
});

const formData = useForm({
    title: '',
    start: props.selectedDate,
    end: props.selectedDate,
    type: 'discount',
    description: '',
    calendarId:'leisure'
});



const emits = defineEmits(['closeForm'])

function closeForm() {
    emits('closeForm')
}

function submitForm() {
    formData.post(route('admin.calendar.event.store'), {
        onSuccess: () => {
            closeForm()
        },
        onError: (errors) => {
            console.error("Errors:", errors)
        }
    });
}
</script>

<style scoped>
.input {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>