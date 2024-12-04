<template>
    <div class="space-y-4">
        <div>
            <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
            <div>{{ props.selectedDate }}</div>
        </div>

        <div>
            <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input v-model="formData.start" type="datetime-local" id="startTime" class="input"
                :min="`${props.selectedDate}T00:00`" :max="`${props.selectedDate}T23:59`" />
        </div>

        <div>
            <label for="endTime" class="block text-sm font-medium text-gray-700">End Time</label>
            <input v-model="formData.end" type="datetime-local" id="endTime" class="input" />
        </div>

        <h3 class="text-lg font-semibold text-gray-900">Create Event</h3>

        <input v-model="formData.title" type="text" placeholder="Enter event title" class="input" />
        <textarea v-model="formData.description" placeholder="Enter event description" class="input"></textarea>

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

function formatDate(dateTime) {
    if (!dateTime) return null;

    const date = new Date(dateTime);

    if (dateTime.includes("T")) {
        return date.toISOString().slice(0, 16).replace('T', ' ');
    } else {
        return date.toISOString().slice(0, 10); 
    }
}

const formData = useForm({
    title: '',
    type: 'event',
    description: '',
    start: '',
    end: '',
    calendarId:'work'
})

const emits = defineEmits(['closeForm'])
const props = defineProps({
    selectedDate: String  
})

function closeForm() {
    emits('closeForm')
}

function submitForm() {
    formData.start = formatDate(formData.start);
    formData.end = formatDate(formData.end);

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
