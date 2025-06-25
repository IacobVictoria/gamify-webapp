<template>
    <TransitionRoot as="template" :show="showModal">
        <Dialog class="relative z-10" @close="closeModal">
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
                            <DialogTitle class="text-xl font-semibold mb-4"
                                >✏️ Editează Meeting</DialogTitle
                            >

                            <form
                                @submit.prevent="submitForm"
                                class="space-y-4"
                            >
                                <div>
                                    <label class="block text-gray-700"
                                        >Titlu:</label
                                    >
                                    <input
                                        v-model="form.title"
                                        class="w-full border rounded-md px-3 py-2"
                                        required
                                    />
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
                                        >Data întâlnirii:</label
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
                                        >Perioada:</label
                                    >
                                    <select
                                        v-model="form.period"
                                        class="w-full border rounded-md px-3 py-2"
                                    >
                                        <option
                                            v-for="period in periods"
                                            :key="period"
                                            :value="period"
                                        >
                                            {{ period.replace("_", " ") }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-gray-700"
                                        >Categorii:</label
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
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500"
                                        @click="$emit('close')"
                                    >
                                        Anulează
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="!form.isDirty"
                                        :class="
                                            form.isDirty
                                                ? 'bg-blue-500 hover:bg-blue-600'
                                                : 'bg-gray-300 cursor-not-allowed'
                                        "
                                        class="px-4 py-2 text-white rounded-md"
                                    >
                                        Salvează
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script setup>
import { defineProps, watch } from "vue";
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
    calendarEvent: Object,
    categories: Array,
    periods: Array,
    selectedDate: Date,
    editRoute: String,
});

const emit = defineEmits(["close"]);
const now = new Date();
const startTimeMin = `${now.getFullYear()}-${String(
    now.getMonth() + 1
).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}T${String(
    now.getHours()
).padStart(2, "0")}:${String(now.getMinutes()).padStart(2, "0")}`;

const form = useForm({
    id: "",
    title: "",
    description: "",
    start: "",
    period: "",
    report_category_ids: [],
});

watch(
    () => props.calendarEvent,
    (event) => {
        form.defaults({
            id: event.id,
            title: event.title,
            description: event.description,
            start: event.start,
            period: event.period,
            report_category_ids: event.report_category_ids || [],
        });

        form.reset();
    },
    { immediate: true }
);

function formatDate(dateTime) {
    if (!dateTime) return null;

    const date = new Date(dateTime);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");

    return `${year}-${month}-${day} ${hours}:${minutes}`;
}

function submitForm() {
    form.start = formatDate(form.start);
    form.put(route(props.editRoute, form.id), {
        onSuccess: () =>  {
             window.location.reload(); 
        },
        onError: (error) => console.error(error),
    });
}
</script>
