<template>
    <div class="bg-white p-8">
        <AuthenticatedLayout>
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 text-center mt-32">Supplier Invoices</h1>
            </header>
            <section class="mb-8">
                <!-- Formular de căutare -->
                <form @submit.prevent="searchReports" class="flex justify-center space-x-4">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                        <input
                            type="number"
                            id="year"
                            v-model="filters.year"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                        <select
                            id="month"
                            v-model="filters.month"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option v-for="(name, value) in months" :key="value" :value="value">{{ name }}</option>
                        </select>
                    </div>
                    <div class="self-end">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                            Search
                        </button>
                    </div>
                </form>
            </section>
            <main>
                <div v-if="Object.keys(groupedReports).length" class="space-y-8">
                    <!-- Gruparea pe săptămâni -->
                    <div v-for="(reports, week, index) in groupedReports" :key="index" class="p-4 bg-gray-50 rounded shadow">
                        <div
                            @click="toggleWeek(week)"
                            class="cursor-pointer flex justify-between items-center p-2 bg-gray-100 rounded mb-2"
                        >
                            <h4 class="font-semibold text-lg">Week {{ week }}</h4>
                            <span class="text-gray-500">
                                {{ expandedWeeks.includes(week) ? "▼" : "▶" }}
                            </span>
                        </div>
                        <ul v-if="expandedWeeks.includes(week)" class="pl-4">
                            <li
                                v-for="(report, reportIndex) in reports"
                                :key="reportIndex"
                                class="p-2 bg-white rounded mb-2 shadow"
                            >
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4>{{ report.title }}</h4>
                                    </div>
                                    <div>
                                        <a
                                            :href="report.s3_path"
                                            target="_blank"
                                            class="text-blue-500 hover:underline"
                                        >
                                            View Invoice (PDF)
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-else class="text-center">
                    <p class="text-gray-500">No invoices available for this period.</p>
                </div>
            </main>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    props: {
        groupedReports: Object, // Facturile grupate pe săptămâni
        year: String,
        month: String,
    },
    data() {
        return {
            filters: {
                year: this.year || new Date().getFullYear(),
                month: this.month || (new Date().getMonth() + 1).toString().padStart(2, '0'),
            },
            months: {
                '01': 'January',
                '02': 'February',
                '03': 'March',
                '04': 'April',
                '05': 'May',
                '06': 'June',
                '07': 'July',
                '08': 'August',
                '09': 'September',
                '10': 'October',
                '11': 'November',
                '12': 'December',
            },
            expandedWeeks: [], // Lista săptămânilor expandate
        };
    },
    methods: {
        searchReports() {
            // Redirecționare către backend cu query string
            window.location.href = `?year=${this.filters.year}&month=${this.filters.month}`;
        },
        toggleWeek(week) {
            // Expandăm sau restrângem săptămâna
            if (this.expandedWeeks.includes(week)) {
                this.expandedWeeks = this.expandedWeeks.filter(w => w !== week);
            } else {
                this.expandedWeeks.push(week);
            }
        },
    },
    components: {
        AuthenticatedLayout,
    },
};
</script>
