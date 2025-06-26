<template>
    <div>
        <div class="flex justify-between mb-4">
            <button
                v-if="!showFavorites"
                class="btn-favorites"
                @click="toggleFavorites"
            >
                ⭐ Favorite
            </button>
            <button
                v-if="showFavorites"
                class="btn-back"
                @click="toggleFavorites"
            >
                🔙 Înapoi
            </button>
        </div>
        <div v-if="showFavorites">
            <FavoritesEvents
                :favorites="favoriteDiscounts"
                :selected-date="selectedDate"
                :type="'Discount-uri'"
            >
            </FavoritesEvents>
        </div>
        <div v-else>
            <h3 class="text-base font-semibold text-gray-900">
                Creează Reducere
            </h3>

            <input
                v-model="formData.title"
                type="text"
                placeholder="Introdu titlul reducerii"
                class="input mt-2"
            />
            <input
                v-model="formData.description"
                type="text"
                placeholder="Introdu descrierea reducerii"
                class="input mt-2"
            />
            <div>
                <label
                    for="startTime"
                    class="block text-sm font-medium text-gray-700"
                    >Ora de început</label
                >
                <input
                    v-model="formData.start"
                    type="datetime-local"
                    id="startTime"
                    class="input"
                    :min="`${props.selectedDate}`"
                />
            </div>

            <div>
                <label
                    for="endTime"
                    class="block text-sm font-medium text-gray-700"
                    >Ora de final</label
                >
                <input
                    v-model="formData.end"
                    type="datetime-local"
                    id="endTime"
                    class="input"
                />
            </div>

            <div class="mt-2">
                <label
                    for="applyTo"
                    class="block text-sm font-medium text-gray-700"
                    >Aplică pentru</label
                >
                <select v-model="formData.applyTo" id="applyTo" class="input">
                    <option value="all">Toate produsele</option>
                    <option value="categories">Categorie specifică</option>
                </select>
            </div>

            <div v-if="formData.applyTo === 'categories'" class="mt-2">
                <label
                    for="category"
                    class="block text-sm font-medium text-gray-700"
                    >Selectează categoria</label
                >
                <select v-model="formData.category" id="category" class="input">
                    <option
                        v-for="(category, index) in props.categories"
                        :key="index"
                        :value="category"
                    >
                        {{ category }}
                    </option>
                </select>
            </div>

            <div class="mt-2">
                <label
                    for="discount"
                    class="block text-sm font-medium text-gray-700"
                    >Procent reducere</label
                >
                <input
                    v-model="formData.discount"
                    type="number"
                    id="discount"
                    placeholder="Introdu procentul de reducere"
                    class="input"
                    min="0"
                    max="100"
                />
            </div>
            <div class="flex gap-8">
                <label
                    for="isPublished"
                    class="block text-sm font-medium text-gray-700"
                    >Publică eveniment</label
                >
                <input
                    type="checkbox"
                    id="isPublished"
                    v-model="formData.is_published"
                    class="input-checkbox"
                />
            </div>
            <div class="mt-2 flex gap-4">
                <label class="text-sm font-medium text-gray-700"
                    >Recurent</label
                >
                <input type="checkbox" v-model="formData.is_recurring" />
            </div>

            <div v-if="formData.is_recurring" class="mt-2">
                <label class="block text-sm font-medium text-gray-700"
                    >Interval recurent</label
                >

                <div class="flex gap-4 mt-2">
                    <label class="flex items-center">
                        <input
                            type="radio"
                            v-model="formData.recurring_interval"
                            value="weekly"
                            class="mr-2"
                        />
                        Săptămânal
                    </label>
                    <label class="flex items-center">
                        <input
                            type="radio"
                            v-model="formData.recurring_interval"
                            value="monthly"
                            class="mr-2"
                        />
                        Lunar
                    </label>
                </div>
                <div v-if="recurringError" class="text-red-500 text-sm mt-2">
                    {{ recurringError }}
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button
                    type="button"
                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    @click="submitForm"
                >
                    Trimite
                </button>

                <button
                    type="button"
                    class="inline-flex w-full justify-center mt-3 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200"
                    @click="closeForm"
                >
                    Închide
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, nextTick, ref, watch } from "vue";
import FavoritesEvents from "./FavoritesEvents.vue";

const props = defineProps({
    selectedDate: String,
    categories: Array,
    favoriteDiscounts: Array,
});

const showFavorites = ref(false);

const formData = useForm({
    title: "",
    description: "",
    start: props.selectedDate,
    end: props.selectedDate,
    type: "discount",
    details: "",
    category: "",
    discount: "",
    applyTo: "",
    is_published: false,
    calendarId: "leisure",
    is_recurring: false, // Inițial, evenimentul NU este recurent
    recurring_interval: null,
});

const emits = defineEmits(["closeForm"]);
const recurringError = ref("");

const eventDuration = computed(() => {
    if (!formData.start || !formData.end) return 0;
    const start = new Date(formData.start);
    const end = new Date(formData.end);
    return (end - start) / (1000 * 60 * 60 * 24);
});

watch(
    () => formData.recurring_interval,
    async (newInterval) => {
        if (!newInterval) return;
        await validateRecurringInterval();
    }
);

watch(
    () => [formData.start, formData.end],
    async () => {
        await validateRecurringInterval();
    }
);

async function validateRecurringInterval() {
    recurringError.value = "";

    if (formData.recurring_interval === "weekly" && eventDuration.value > 7) {
        recurringError.value =
            "❌ Recurența săptămânală nu este permisă pentru evenimente mai lungi de 7 zile!";
        await nextTick(() => {
            formData.recurring_interval = null;
        });
    } else if (
        formData.recurring_interval === "monthly" &&
        eventDuration.value > 28
    ) {
        recurringError.value =
            "❌ Recurența lunară nu este permisă pentru evenimente mai lungi de 28 de zile!";
        await nextTick(() => {
            formData.recurring_interval = null;
        });
    }
}
function closeForm() {
    emits("closeForm");
}
function formatDate(dateTime) {
    if (!dateTime) return null;

    return dateTime.replace("T", " ");
}

function submitForm() {
    const details = {
        applyTo: formData.applyTo,
        category: formData.category,
        discount: formData.discount,
    };

    formData.details = JSON.stringify(details);
    formData.start = formatDate(formData.start);
    formData.end = formatDate(formData.end);
    formData.post(route("admin.calendar.event.store"), {
        onSuccess: () => {
            closeForm();
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        },
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
