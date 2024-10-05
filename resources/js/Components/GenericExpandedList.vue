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
                                            <span v-if="column.isTemplate">{{ column.template(item) }}</span>
                                            <span v-else>{{ item[column.name] }}</span>
                                        </td>
                                        <td class="text-center px-6 py-4">
                                            <button @click="toggleDetails(index)">
                                                {{ showDetails[index] ? 'Ascunde' : 'Afișează' }} detalii
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Detalii -->
                                    <tr v-if="showDetails[index]" class="details-row">
                                        <td colspan="100%" class="px-6 py-4 bg-gray-50">
                                            <div>
                                                <strong>{{ descriptionDetails }}</strong>
                                                <ul>
                                                    <li v-for="(detail, productIndex) in item.details"
                                                        :key="productIndex">
                                                        <template v-for="label in detailsLabel" :key="label.name">
                                                            <strong>{{ label.label }}:</strong> {{ detail[label.name]
                                                            }}<br />
                                                        </template>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Pagination class="flex justify-center" :links="items.links" />
    </div>
</template>

<script>
import Filter from './Filter.vue';
import Pagination from './Pagination.vue';
import debounce from "lodash/fp/debounce";
import { ref, watch } from 'vue';

export default {
    name: 'GenericList',

    components: {
        Pagination,
        Filter,
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
    },
    data() {
        return {
            filterValues: Object.fromEntries(
                this.filters.map(filter => [filter.model, this.prevFilters[filter.model] || ''])
            ),
            showDetails: [],  
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
