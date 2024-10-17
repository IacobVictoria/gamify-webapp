<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="handleClose">
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
                            <div>
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                    <CheckIcon class="h-6 w-6 text-green-600" aria-hidden="true" />
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                                        {{ title }}
                                    </DialogTitle>
                                    <p class="mt-2 text-sm text-gray-500">{{ message }}</p>
                                </div>
                            </div>
                            <div class="flex flex-row gap-5 mt-5 sm:mt-6">
                                <button
                                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                    @click="confirmDelete">Yes</button>
                                <button
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                                    @click="handleClose">No</button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { CheckIcon } from '@heroicons/vue/24/outline';

export default {
    components: {
        Dialog,
        TransitionRoot,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        CheckIcon

    },
    props: {
        open: {
            type: Boolean,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
        deleteRoute: {
            type: String,
            required: true,
        },
        objectId: {
            type: [String, Number],
            
        },
        items: {
            type: [Array, null],
          
        },

        extraId: {
            type: [String, Number],
         
        },
    },
    methods: {
        async confirmDelete() {
            if (Array.isArray(this.items) && this.items.length > 0) {

                for (const item of this.items) {
                    try {
                        // await each delete request
                        await this.$inertia.delete(route(this.deleteRoute, item.product.id));
                    } catch (error) {
                        console.error(`Error deleting item with ID ${item.product.id}:`, error);
                    }
                }
            } else if (this.objectId && this.extraId == null) {
                await this.$inertia.delete(route(this.deleteRoute, this.objectId));
            }
            else if (this.objectId && this.extraId) {
                try {
                    await this.$inertia.delete(route(this.deleteRoute, { productId: this.extraId, reviewId: this.objectId }));
                } catch (error) {
                    console.error(`Error deleting review with ID ${this.objectId}:  ${this.extraId} ... ${this.deleteRoute}`, error);
                }

            }

            // Close the dialog
            this.handleClose();
        },
        handleClose() {
            this.$emit('update:open', false);
        },
    },

};
</script>