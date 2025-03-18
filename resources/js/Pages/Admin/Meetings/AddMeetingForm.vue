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
                            <div v-if="!isPastDate" class="space-y-4">
                                <h2>Adaugă Meeting</h2>
                                <form @submit.prevent="submitForm">
                                    <div class="form-group">
                                        <label for="name">Title Meeting:</label>
                                        <input type="text" id="title" v-model="form.title" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Descriere:</label>
                                        <textarea id="description" v-model="form.description"></textarea>
                                    </div>
                                    <div>
                                        <label for="startTime" class="block text-sm font-medium text-gray-700">Start
                                            Time</label>
                                        <input v-model="form.start" type="datetime-local" id="startTime" class="input"
                                            :min="`${props.selectedDate}T00:00`" />
                                    </div>

                                    <div class="form-group">
                                        <label for="period">Perioadă:</label>
                                        <select id="period" v-model="form.period" required>
                                            <option v-for="period in periods" :key="period" :value="period">
                                                {{ period.replace('_', ' ') }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="report_category_ids">Categorii de Raport:</label>
                                        <select id="report_category_ids" v-model="form.report_category_ids" multiple>
                                            <option v-for="category in categories" :key="category.id"
                                                :value="category.id">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="submit" class="submit-btn">Salvează</button>
                                        <button type="button" class="cancel-btn" @click="closeForm">Anulează</button>
                                    </div>
                                </form>
                            </div>
                            <div v-if="isPastDate" class="bg-red-100 text-red-700 p-3 rounded-md text-center mt-2">
                                ❌ You have selected a past date. You cannot add meetings to past dates!
                                <button type="button" class="cancel-btn" @click="closeForm">Anulează</button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    showModal: Boolean,
    selectedDate: String,
    categories: Array,
    periods: Array,
    isPastDate: Boolean
});

const emit = defineEmits(['closeForm']);

const form = useForm({
    title: '',
    description: '',
    start: props.selectedDate ? formatDate(props.selectedDate) : formatDate(new Date()),
    end: '',
    period: 'ultima_luna',
    status: 'open',
    report_category_ids: [],
});

function closeForm() {
    emit('closeForm');
}

function submitForm() {
    form.start = formatDate(form.start);
    form.end = formatDate(form.end);
    form.post(route('admin.meetings.store'), {
        onSuccess: () => {
            closeForm();
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        }
    });
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