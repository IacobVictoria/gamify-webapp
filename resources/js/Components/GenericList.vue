<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <p class="text-base font-semibold leading-6 text-gray-900">{{ description }}</p>
            </div>

            <div v-if="createRoute" class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <inertia-link :href="route(createRoute)"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add new {{ entityName }}
                </inertia-link>
            </div>
        </div>
        <!-- Filter section -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="filter in filters" :key="filter.id">
                <Filter :filter="filter" :value="filterValues[filter.model]"
                    @update:value="updateFilter(filter.model, $event)" />
            </div>
        </div>

        <!-- Items table -->
        <div class="mt-8 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th v-for="column in columns" :key="column.name" scope="col"
                                        class="px-6 py-1 text-left text-sm text-gray-400" :class="column.columnAlign">
                                        <div class="flex flex-row items-center gap-2">
                                                {{ column.label }}
                                            <div class="cursor-pointer hover:rounded-full hover:bg-gray-300"
                                                v-if="column.sorting" @click="toggleSorting(column)">
                                                <ArrowOrderSVG :direction="currentSortDirection[column.name]"
                                                    v-if="column.sorting === true" />
                                            </div>
                                        </div>
                                    </th>
                                    <template v-if="editRoute && deleteRoute">
                                        <th scope="col"
                                            class="text-center px-6 py-3 text-left text-md font-semibold text-gray-500">
                                            Acțiuni
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 font-semibold">
                                <tr v-for="item in items.data" :key="item.id">
                                    <td v-for="column in columns" :key="column.name"
                                        class="px-6 py-4 lg:whitespace-normal whitespace-nowrap text-sm text-gray-900"
                                        :class="column.valueAlign">
                                        <span>{{ item[column.name] }}</span>
                                    </td>
                                    <template v-if="editRoute && deleteRoute">
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-md font-medium">
                                            <inertia-link :href="route(editRoute, item.id)"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</inertia-link>
                                            <button @click="openDialog(item)"
                                                class="ml-4 text-red-600 hover:text-red-400">Delete</button>
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Pagination class="flex justify-center" :links="items.links" />

        <TransitionRoot as="template" :show="open">
            <Dialog as="div" class="relative z-10" @close="open = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                            Are you sure you want to delete this account?
                                        </DialogTitle>
                                    </div>
                                </div>
                                <div class="flex flex-row gap-5 mt-5 sm:mt-6">
                                    <button
                                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                        @click="deleteItem">Yes</button>
                                    <button
                                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                                        @click="cancel">No</button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>

</template>

<script>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import Filter from './Filter.vue';
import Pagination from './Pagination.vue';
import debounce from "lodash/fp/debounce";
import { ref } from 'vue';
import { CheckIcon } from '@heroicons/vue/24/outline';
import ArrowOrderSVG from './ArrowOrderSVG.vue';


export default {
    name: 'GenericList',

    components: {
        Pagination,
        Filter, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot,
        CheckIcon, ArrowOrderSVG

    },
    setup(props) {
        const open = ref(false);

        return {
            open,
        }
    },


    props: {
        items: Object,
        title: String,
        description: String,
        columns: Array,
        filters: Array,
        prevFilters: Object,
        getRoute: String,
        extraId: String,
        createRoute: {
            type: String,
            required: false,
            default: null,
        },
        entityName: String,
        editRoute: {
            type: String,
            required: false,
            default: null,
        },
        deleteRoute: {
            type: String,
            required: false,
            default: null,
        },
    },
    watch: {
        filterValues: {
            handler: debounce(300, function () {
                if (this.extraId) {
                    this.$inertia.get(route(this.getRoute, this.extraId), {
                        filters: this.filterValues,
                    }, {
                        preserveState: true,
                        replace: true,
                    });
                }
                else {
                    this.$inertia.get(route(this.getRoute), {
                        filters: this.filterValues,
                    }, {
                        preserveState: true,
                        replace: true,
                    });
                }
            }),
            deep: true,
        },
    },

    data() {
        return {
            filterValues: Object.fromEntries(
                this.filters.map(filter => [filter.model, this.prevFilters[filter.model] || ''])
            ),
            item: '',
            currentSortColumn: null,
            currentSortDirection: {},
        }
    },
    methods: {
        updateFilter(model, newValue) {
            this.filterValues[model] = newValue;
        },
        openDialog(item) {
            this.open = true;
            this.item = item;
        },
        cancel() {
            this.open = false;
            this.item = '';
        },
        deleteItem() {
            return this.$inertia.delete(route(this.deleteRoute, this.item.id), {
                preserveState: true,
                replace: true,

                onError: () => {
                    this.open = false;
                    this.item = '';
                },

                onSuccess: () => {
                    this.open = false;
                    this.item = '';
                }
            });
        },
        toggleSorting(column) {
            if (this.currentSortColumn === column.name) {
                this.currentSortDirection[column.name] = this.currentSortDirection[column.name] === "asc" ? "desc" : "asc";
            } else {
                this.currentSortColumn = column.name;
                this.currentSortDirection[column.name] = "asc";
            }
            this.fetchSortedData();
        },

        fetchSortedData() {
            this.$inertia.get(route(this.getRoute, {
                orderBy: this.currentSortColumn,
                orderDirection: this.currentSortDirection[this.currentSortColumn],
            }), {}, {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            });
        },

    }
}
</script>