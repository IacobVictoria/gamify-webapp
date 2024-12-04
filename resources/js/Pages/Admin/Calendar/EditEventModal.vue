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

                            <div v-if="selectedType === 'event'">
                                <EditEventForm :calendarEvent="calendarEvent" @closeForm="closeForm" />
                            </div>
                            <!-- <div v-if="selectedType === 'order'">
                  <EditComandForm @closeForm="closeForm" />
                </div>
                <div v-if="selectedType === 'discount'">
                  <EditDiscountForm @closeForm="closeForm" />
                </div>
   -->
                            <div v-if="selectedType === null" class="mt-5 sm:mt-6">
                                <button type="button"
                                    class="inline-flex w-full justify-center mt-3 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200"
                                    @click="closeModal">
                                    Cancel
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
import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import EditEventForm from './EditEventForm.vue'

// Props și emiteri
const props = defineProps({
    showModal: Boolean,
    selectedType: String,
    calendarEvent: Object
});

const emit = defineEmits();

function closeModal() {
    emit('update:showModal', false);
    emit('update:selectedType', null); // Resetarea tipului când se închide modalul
}

function closeForm() {
    emit('update:selectedType', null); // Resetarea tipului după ce se închide formularul
}

</script>

<style scoped>
/* Stiluri pentru dialog și panou */
</style>