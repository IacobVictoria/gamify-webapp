<template>
    <TransitionRoot as="template" :show="showModal">
        <Dialog class="relative z-10" @close="closeForm">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <div v-if="!isPastDate">
                                <DialogTitle
                                    class="text-xl font-semibold text-gray-800 mb-2"
                                >
                                    🗓️ Adaugă Întâlnire
                                </DialogTitle>

                                <form
                                    @submit.prevent="submitForm"
                                    class="space-y-4"
                                >
                                    <div>
                                        <label class="block text-gray-700"
                                            >Titlu:</label
                                        >
                                        <input
                                            type="text"
                                            v-model="form.title"
                                            class="w-full border rounded-md px-3 py-2"
                                        />
                                        <p
                                            v-if="validationErrors.title"
                                            class="text-red-600 text-sm mt-1"
                                        >
                                            {{ validationErrors.title }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-gray-700"
                                            >Descriere:</label
                                        >
                                        <textarea
                                            v-model="form.description"
                                            class="w-full border rounded-md px-3 py-2"
                                            rows="3"
                                        ></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-gray-700"
                                            >Ora de început:</label
                                        >
                                        <input
                                            type="datetime-local"
                                            v-model="form.start"
                                            :min="startTimeMin"
                                            class="w-full border rounded-md px-3 py-2"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-gray-700"
                                            >Perioadă:</label
                                        >
                                        <select
                                            v-model="form.period"
                                            class="w-full border rounded-md px-3 py-2"
                                            required
                                        >
                                            <option
                                                v-for="period in periods"
                                                :key="period"
                                                :value="period"
                                            >
                                                {{ period.replace("_", " ") }}
                                            </option>
                                        </select>
                                        <p
                                            v-if="validationErrors.period"
                                            class="text-red-600 text-sm mt-1"
                                        >
                                            {{ validationErrors.period }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-gray-700"
                                            >Categorii de Raport:</label
                                        >
                                        <select
                                            v-model="form.report_category_ids"
                                            multiple
                                            class="w-full border rounded-md px-3 py-2"
                                        >
                                            <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <p
                                            v-if="
                                                validationErrors.report_category_ids
                                            "
                                            class="text-red-600 text-sm mt-1"
                                        >
                                            {{
                                                validationErrors.report_category_ids
                                            }}
                                        </p>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500"
                                            @click="closeForm"
                                        >
                                            Anulează
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-md"
                                        >
                                            Salvează
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div v-else class="text-center">
                                <p class="text-red-600 font-semibold">
                                    ❌ Nu poți adăuga meeting-uri la o dată
                                    trecută!
                                </p>
                                <button
                                    type="button"
                                    class="mt-4 px-4 py-2 bg-gray-400 text-white rounded-md"
                                    @click="closeForm"
                                >
                                    Închide
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { defineProps, defineEmits, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

const props = defineProps({
    showModal: Boolean,
    selectedDate: String,
    categories: Array,
    periods: Array,
    isPastDate: Boolean,
    addRoute: String,
});
const now = new Date();
const startTimeMin = `${now.getFullYear()}-${String(
    now.getMonth() + 1
).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}T${String(
    now.getHours()
).padStart(2, "0")}:${String(now.getMinutes()).padStart(2, "0")}`;

const emit = defineEmits(["closeForm"]);
const validationErrors = ref({});

const form = useForm({
    title: "",
    description: "",
    start: props.selectedDate
        ? formatDate(props.selectedDate + ' 00:00:00')
        : formatDate(new Date()),
    end: "",
    period: "ultima_luna",
    status: "open",
    report_category_ids: [],
});

function closeForm() {
    emit("closeForm");
}

//validare formular
function validateForm() {
    validationErrors.value = {};

    if (!form.title.trim()) {
        validationErrors.value.title = "Titlul este obligatoriu.";
    }

    if (!form.period) {
        validationErrors.value.period = "Perioada este obligatorie.";
    }

    if (!form.report_category_ids || form.report_category_ids.length === 0) {
        validationErrors.value.report_category_ids =
            "Selectează cel puțin o categorie.";
    }

    return Object.keys(validationErrors.value).length === 0;
}

//trimitere formular
function submitForm() {
    if (!validateForm()) return;

    form.start = formatDate(form.start);
    form.end = formatDate(form.end);
    form.post(route(props.addRoute), {
        onSuccess: () => {
            window.location.reload();
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        },
    });
}

function formatDate(dateTime) {
    if (!dateTime) return null;

    if (dateTime.includes("T")) {
        return dateTime.replace("T", " ");
    }

    return dateTime;
}
</script>

<style scoped>
.modal {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.submit-btn {
    background-color: #2ecc71;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.cancel-btn {
    margin-top: 5px;
    background-color: #e74c3c;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>
