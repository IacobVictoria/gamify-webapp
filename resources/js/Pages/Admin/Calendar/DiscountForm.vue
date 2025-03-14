<template>
    <div>
        <div class="flex justify-between mb-4">
            <button v-if="!showFavorites" class="btn-favorites" @click="toggleFavorites">⭐ Favorites</button>
            <button v-if="showFavorites" class="btn-back" @click="toggleFavorites">🔙 Back</button>
        </div>
        <div v-if="showFavorites">
            <FavoritesEvents :favorites="favoriteDiscounts" :selected-date="selectedDate" :type="'Discounts'">
            </FavoritesEvents>
        </div>
        <div v-else>
            <h3 class="text-base font-semibold text-gray-900">Create Discount</h3>
            <div>
                <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
                <div>{{ props.selectedDate }}</div>
            </div>

            <input v-model="formData.title" type="text" placeholder="Enter Discount Title" class="input mt-2" />
            <input v-model="formData.description" type="text" placeholder="Enter Discount Description"
                class="input mt-2" />
            <div>
                <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input v-model="formData.start" type="datetime-local" id="startTime" class="input"
                    :min="`${props.selectedDate}T00:00`" />
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
            <div>
                <label for="isPublished" class="block text-sm font-medium text-gray-700">Publish Event</label>
                <input type="checkbox" id="isPublished" v-model="formData.is_published" class="input-checkbox" />
            </div>
            <div class="mt-2">
                <label class="flex items-center">
                    <input type="checkbox" v-model="formData.is_recurring">
                    <span class="ml-2 text-sm font-medium text-gray-700">Make it Recurring</span>
                </label>
            </div>

            <div v-if="formData.is_recurring" class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Recurring Interval</label>

                <div class="flex gap-4 mt-2">
                    <label class="flex items-center">
                        <input type="radio" v-model="formData.recurring_interval" value="weekly" class="mr-2">
                        Weekly
                    </label>
                    <label class="flex items-center">
                        <input type="radio" v-model="formData.recurring_interval" value="monthly" class="mr-2">
                        Monthly
                    </label>
                </div>
                <div v-if="recurringError" class="text-red-500 text-sm mt-2">
        {{ recurringError }}
    </div>
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
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch } from 'vue';
import FavoritesEvents from './FavoritesEvents.vue';

const props = defineProps({
    selectedDate: String,
    categories: Array,
    favoriteDiscounts: Array
});

const showFavorites = ref(false);

const formData = useForm({
    title: '',
    description: '',
    start: props.selectedDate,
    end: props.selectedDate,
    type: 'discount',
    details: '',
    category: '',
    discount: '',
    applyTo: '',
    is_published: false,
    calendarId: 'leisure',
    is_recurring: false, // Inițial, evenimentul NU este recurent
    recurring_interval: null, 
});

const emits = defineEmits(['closeForm']);
const recurringError = ref('');

const eventDuration = computed(() => {
    if (!formData.start || !formData.end) return 0;
    const start = new Date(formData.start);
    const end = new Date(formData.end);
    return (end - start) / (1000 * 60 * 60 * 24); 
});

watch(() => formData.recurring_interval, async (newInterval) => {
    if (!newInterval) return;
    await validateRecurringInterval();
});

watch(() => [formData.start, formData.end], async () => {
    await validateRecurringInterval();
});

async function validateRecurringInterval() {
    recurringError.value = '';

    if (formData.recurring_interval === 'weekly' && eventDuration.value > 7) {
        recurringError.value = '❌ Weekly recurrence is not allowed for events longer than 7 days!';
        await nextTick(() => {
            formData.recurring_interval = null;
        });
    } else if (formData.recurring_interval === 'monthly' && eventDuration.value > 28) {
        recurringError.value = '❌ Monthly recurrence is not allowed for events longer than 28 days!';
        await nextTick(() => {
            formData.recurring_interval = null;
        });
    }
}
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

function toggleFavorites() {
    showFavorites.value = !showFavorites.value;
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
