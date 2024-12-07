<template>
    <div>
        <h3 class="text-base font-semibold text-gray-900">Create Discount</h3>
        <div>
            <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
            <div>{{ props.selectedDate }}</div>
        </div>


        <input v-model="formData.title" type="text" placeholder="Enter Discount Title" class="input mt-2" />
        <input v-model="formData.description" type="text" placeholder="Enter Discount Description" class="input mt-2" />
        <div>
            <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input v-model="formData.start" type="datetime-local" id="startTime" class="input"
                :min="`${props.selectedDate}T00:00`"  />
        </div>

        <div>
            <label for="endTime" class="block text-sm font-medium text-gray-700">End Time</label>
            <input v-model="formData.end" type="datetime-local" id="endTime" class="input" />
        </div>

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
            <input v-model="formData.discount" type="number" id="discount" placeholder="Enter Discount Percentage"
                class="input" min="0" max="100" />
        </div>

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
import { onMounted } from 'vue';

const props = defineProps({
    selectedDate: String,
    categories: Array
});

const formData = useForm({
    title: '',
    description: '',
    start: props.selectedDate,
    end: props.selectedDate,
    type: 'discount',
    details: '',
    category:'',
    discount:'',
    applyTo:'',

    calendarId: 'leisure'
});

const emits = defineEmits(['closeForm']);

function closeForm() {
    emits('closeForm');
}
function formatDate(dateTime) {
    if (!dateTime) return null;

    const date = new Date(dateTime);

    if (dateTime.includes("T")) {
        return date.toISOString().slice(0, 16).replace('T', ' ');
    } else {
        return date.toISOString().slice(0, 10); 
    }
}

function submitForm() {
    const details = {
        applyTo: formData.applyTo,
        category: formData.category,
        discount: formData.discount
    };

    formData.details = JSON.stringify(details);
    formData.start = formatDate(formData.start);
    formData.end = formatDate(formData.end);
    formData.post(route('admin.calendar.event.store'), {
        onSuccess: () => {
            closeForm();
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
