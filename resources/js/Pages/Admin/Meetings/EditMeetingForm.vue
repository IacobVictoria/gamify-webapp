<template>
    <TransitionRoot as="template" :show="showModal">
        <Dialog class="relative z-10" @close="closeModal">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                            <h3 class="text-xl font-semibold mb-4">Edit Meeting</h3>
                            <form @submit.prevent="submitForm">
                                <div class="mb-4">
                                    <label class="block text-gray-700">Title:</label>
                                    <input v-model="form.title" class="w-full border px-3 py-2 rounded" required />
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700">Description:</label>
                                    <textarea v-model="form.description"
                                        class="w-full border px-3 py-2 rounded"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700">Meeting Date:</label>
                                    <input v-model="form.start" type="datetime-local" id="startTime" class="input"
                                        :min="startTimeMin" />
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700">Period:</label>
                                    <select v-model="form.period" class="w-full border px-3 py-2 rounded">
                                        <option v-for="period in periods" :key="period" :value="period">
                                            {{ period.replace('_', ' ') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700">Categories:</label>
                                    <select v-model="form.report_category_ids" multiple
                                        class="w-full border px-3 py-2 rounded">
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="$emit('close')"
                                        class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                                    <button v-if="form.isDirty" type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded">
                                        Save Changes
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
import { defineProps, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    showModal: Boolean,
    calendarEvent: Object,
    categories: Array,
    periods: Array,
    selectedDate: Date
});

const emit = defineEmits(['close']);
const startTimeMin = new Date().toISOString().split("T")[0] + 'T00:00';
const form = useForm({
    id: '',
    title: '',
    description: '',
    start: '',
    period: '',
    report_category_ids: [],
});

watch(() => props.calendarEvent, (event) => {
    form.defaults({
        id: event.id,
        title: event.title,
        description: event.description,
        start: event.start,
        period: event.period,
        report_category_ids: event.report_category_ids || [],
    });

    form.reset();
}, { immediate: true });

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
    form.start = formatDate(form.start);
    form.put(route('admin.meetings.update', form.id), {
        onSuccess: () => emit('close'),
        onError: (error) => console.error(error),
    });
};

</script>
