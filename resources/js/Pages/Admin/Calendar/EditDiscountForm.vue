<template>
    <div class="modal-overlay">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-gray-900">Edit Discount</h3>

            <form @submit.prevent="submitForm">
                <!-- Titlu Discount -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Discount Title</label>
                    <input v-model="formData.title" type="text" id="title" placeholder="Enter discount title"
                        class="input" />
                </div>

                <!-- Descriere Discount -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="formData.description" id="description" placeholder="Enter discount description"
                        class="input"></textarea>
                </div>

                <!-- Data Start -->
                <div>
                    <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input v-model="formData.start" type="datetime-local" id="startTime" class="input"
                        :min="startTimeMin" />
                </div>

                <!-- Data End -->
                <div>
                    <label for="endTime" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input v-model="formData.end" type="datetime-local" id="endTime" class="input"
                        :min="formData.start" />
                </div>

                <!-- Aplică la -->
                <div class="mt-2">
                    <label for="applyTo" class="block text-sm font-medium text-gray-700">Apply To</label>
                    <select v-model="formData.applyTo" id="applyTo" class="input">
                        <option value="all">All Products</option>
                        <option value="categories">Specific Category</option>
                    </select>
                </div>

                <div v-if="formData.applyTo === 'categories'" class="mt-2">
                    <label for="category" class="block text-sm font-medium text-gray-700">Select Category</label>
                    <select v-model="formData.category" id="category" class="input">
                        <option v-for="(category, index) in props.categories" :key="index" :value="category">
                            {{ category }}
                        </option>
                    </select>
                </div>

                <div class="mt-2">
                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount Percentage</label>
                    <input v-model="formData.discount" type="number" id="discount" placeholder="Enter discount percentage"
                        class="input" min="0" max="100" />
                </div>

                <div class="mt-5 sm:mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    calendarEvent: Object,
    categories: Array
});

const formData = useForm({
    title: props.calendarEvent.title || '',
    description: props.calendarEvent.description || '',
    start: formatDate(props.calendarEvent.start) || '',
    end: formatDate(props.calendarEvent.end) || '',
    applyTo: props.calendarEvent.details ? JSON.parse(props.calendarEvent.details).applyTo : 'all',
    category: props.calendarEvent.details ? JSON.parse(props.calendarEvent.details).category : '',
    discount: props.calendarEvent.details ? JSON.parse(props.calendarEvent.details).discount : 0
});

function formatDate(dateTime) {
    if (!dateTime) return '';
    return dateTime.replace(' ', 'T'); 
}

const startTimeMin = new Date().toISOString().split("T")[0] + 'T00:00';

const emit = defineEmits(['formSubmitted', 'closeForm']);

function closeForm() {
    emit('closeForm');
}


function submitForm() {
    if (new Date(formData.end) <= new Date(formData.start)) {
        alert("End time must be greater than start time.");
        return;
    }
    
    const details = {
        applyTo: formData.applyTo,
        category: formData.category,
        discount: formData.discount
    };

    formData.details = JSON.stringify(details);

    formData.post(route('admin.calendar.event.update', { id: props.calendarEvent.id }), {
        onSuccess: () => {
            emit('formSubmitted');
        },
        onError: (errors) => {
            console.error("Errors:", errors);
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
