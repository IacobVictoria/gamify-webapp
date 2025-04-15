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
                            <StartPage v-if="selectedType === null  &&!isPastDate" @setSelectedType="setSelectedType"
                                @close="closeModal" />
                            <div v-if="isPastDate" class="bg-red-100 text-red-700 p-3 rounded-md text-center mt-2">
                                ❌ You have selected a past date. You cannot add events to past dates!
                            </div>

                            <div v-if="selectedType === 'event'">
                                <EventForm :selectedDate="selectedDate" @closeForm="closeForm" />
                            </div>
                            <div v-if="selectedType === 'order' ">
                                <SupplierOrderForm :selectedDate="selectedDate" @closeForm="closeForm"
                                    :suppliers="props.suppliers" :products="props.products"
                                    :favorite-commands="favoritesCommands" />
                            </div>
                            <div v-if="selectedType === 'discount'">
                                <DiscountForm :selectedDate="selectedDate" @closeForm="closeForm"
                                    :categories="props.categories" :favorite-discounts="favoritesDiscounts" />
                            </div>

                            <div v-if="selectedType === null" class="mt-2 sm:mt-4">

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
import { computed, ref } from 'vue'
import StartPage from './StartPage.vue'
import EventForm from './EventForm.vue';
import DiscountForm from './DiscountForm.vue';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import SupplierOrderForm from './SupplierOrderForm.vue';

const props = defineProps({
    showModal: Boolean,
    selectedDate: String,
    categories: Array,
    products: Array,
    suppliers: Array,
    favoritesCommands: Array,
    favoritesDiscounts: Array
});
const emit = defineEmits()
const selectedType = ref(null)

function setSelectedType(type) {
    selectedType.value = type
}

function closeModal() {
    emit('update:showModal', false)
    selectedType.value = null
}
function closeForm() {
    selectedType.value = null
}
const today = computed(() => {
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    return now;
});

//Verifică dacă selectedDate este în trecut
const isPastDate = computed(() => {
    if (!props.selectedDate) return false;

    const selected = new Date(props.selectedDate);
    selected.setHours(0, 0, 0, 0); // Ignorăm orele, verificăm doar ziua

    return selected < today.value; // Comparăm fără ore
});

</script>