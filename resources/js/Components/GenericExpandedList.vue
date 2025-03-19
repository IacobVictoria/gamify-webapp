<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <p class="text-base font-semibold leading-6 text-gray-900">{{ description }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="filter in filters" :key="filter.model">
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
                                        {{ column.label }}
                                    </th>
                                    <th scope="col" class="text-center px-6 py-3 text-md font-semibold text-gray-500">
                                        Acțiuni
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 font-semibold">
                                <template v-for="(item, index) in items.data" :key="item.id">
                                    <tr>
                                        <td v-for="column in columns" :key="column.name"
                                            class="px-6 py-4 lg:whitespace-normal whitespace-nowrap text-sm text-gray-900"
                                            :class="column.valueAlign">
                                            <span>{{ item[column.name] }}</span>
                                        </td>
                                        <td class="flex flex-col items-center text-center px-6 py-4">
                                            <div>
                                                <template v-if="updateRoute && deleteRoute">
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-md font-medium">
                                            <inertia-link :href="route(updateRoute, item.id)"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</inertia-link>
                                            <button @click="openDialog(item)"
                                                class="ml-4 text-red-600 hover:text-red-400">Delete</button>
                                        </td>
                                </template>
                                <button @click="toggleDetails(index)"
                                    class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                                    {{ showDetails[index] ? 'Ascunde' : 'Afișează' }} detalii
                                </button>
                                <div v-if="authUserHasRole('User')" class="mt-2">
                                    <template v-if="invoice === 'client'">
                                        <button v-if="item.invoice_url" @click="viewInvoice(item.invoice_url)"
                                            class="mt-2 bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 transition">
                                            Vezi Factura
                                        </button>
                                    </template>
                                </div>
                    </div>

                    <div v-if="authUserHasRole('Admin')" class="mt-2">
                        <template v-if="invoice === 'clients'">
                            <button @click="showInvoiceClient(item.invoice_url)"
                                class="bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 transition duration-200">
                                Factura Client
                            </button>
                        </template>
                        <template v-else-if="invoice === 'suppliers'">
                            <button @click="showInvoiceSupplier(item.invoice_url)"
                                class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                                Factura Furnizor
                            </button>
                        </template>

                    </div>
                    </td>
                    </tr>
                    <!-- Detalii -->
                    <tr v-if="showDetails[index]" class="details-row">
                        <td colspan="100%" class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <strong class="text-lg font-semibold text-gray-800">{{
                                    descriptionDetails }}</strong>

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div v-for="information in extraLabel" :key="information.name"
                                        class="flex flex-col justify-between">
                                        <span class="text-gray-600 font-medium">{{ information.label
                                        }}:</span>
                                        <span class="text-gray-800 font-semibold">{{
                                            item.extra[information.name] }}</span>
                                    </div>
                                </div>

                                <ul class="mt-4 space-y-2">
                                    <li v-for="(detail, productIndex) in item.details" :key="productIndex"
                                        class="border-b border-gray-300 pb-2">
                                        <div class="flex flex-col space-y-1">
                                            <template v-for="label in detailsLabel" :key="label.name">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-600">{{ label.label
                                                    }}:</strong>
                                                    <span class="text-gray-800">{{ detail[label.name]
                                                    }}</span>
                                                </div>
                                            </template>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
</template>
</tbody>
</table>

<GenericDeleteNotification :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event" title="Delete Item"
    message="Are you sure you want to delete this badge from your the list?" :deleteRoute="'admin.badges.destroy'"
    :objectId="itemToDelete" />

</div>
</div>
</div>
</div>

<Pagination class="flex justify-center" :links="items.links" />
</div>
</template>

<script>
import Filter from './Filter.vue';
import GenericDeleteNotification from './GenericDeleteNotification.vue';
import Pagination from './Pagination.vue';
import debounce from "lodash/fp/debounce";
import { ref, watch } from 'vue';

export default {
    name: 'GenericList',

    components: {
        Pagination,
        Filter,
        GenericDeleteNotification
    },

    props: {
        items: Object,
        title: String,
        description: String,
        columns: Array,
        filters: Array,
        prevFilters: Object,
        entityName: String,
        getRoute: String,
        descriptionDetails: String,
        detailsLabel: Array,
        extraLabel: Array,
        invoice: String,
        updateRoute: String,
        deleteRoute: String,
    },
    data() {
        return {
            filterValues: Object.fromEntries(
                this.filters.map(filter => [filter.model, this.prevFilters[filter.model] || ''])
            ),
            showDetails: [],
            isDeleteDialogOpen: false,
            itemToDelete: null,
        };
    },

    watch: {
        filterValues: {
            handler: debounce(300, function () {
                this.$inertia.get(route(this.getRoute), {
                    filters: this.filterValues,
                }, {
                    preserveState: true,
                    replace: true,
                });
            }),
            deep: true,
        },
    },
    mounted() {
        this.showDetails = Array(this.items.data.length).fill(false);
    },

    methods: {
        updateFilter(model, newValue) {
            this.filterValues[model] = newValue;
        },

        toggleDetails(index) {
            this.showDetails[index] = !this.showDetails[index];
        },

        showInvoiceClient(url) {
            window.open(url, '_blank');
        },

        showInvoiceSupplier(url) {
            window.open(url, '_blank');
        },

        openDialog(item) {
            this.isDeleteDialogOpen = !this.isDeleteDialogOpen;
            this.itemToDelete = item.id;
        },
        viewInvoice(url) {
            window.open(url, '_blank');
        },

    }
};
</script>

<style scoped>
.details-row {
    background-color: #f9f9f9;
}

.details-row td {
    padding: 15px;
    white-space: pre-wrap;
}
</style>
