<template>
    <div class="bg-white p-8">
        <AuthenticatedLayout>
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 text-center mt-32">QR Codes Events</h1>
            </header>

            <!-- Formular de căutare -->
            <section class="mb-8">
                <div class="flex justify-center">
                    <div class="w-1/3">
                        <label for="eventTitle" class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input v-model="searchQuery" id="search" name="search"
                            class="block w-full rounded-md border border-gray-300 pl-10 pr-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Search for events..." type="search" @input="searchReports" />
                    </div>
                </div>
            </section>

            <!-- Rapoartele QR Code -->
            <main class="space-y-24 space-x-10 flex justify-center">
                <div v-if="reports.length" class="folder">
                    <ul class="space-y-4">
                        <li v-for="(report, index) in reports" :key="index" class="p-4 bg-gray-50 rounded shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold">{{ report.title }}</h4>
                                    <p class="text-sm text-gray-600">Click below to view the QR code image.</p>
                                </div>
                                <div>
                                    <a :href="report.qr_code_url" target="_blank" class="text-blue-500 hover:underline">
                                        View QrCode (PDF)
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </main>
        </AuthenticatedLayout>
    </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    props: {
        reports: Array,
        filters: Object, 
    },
    data() {
        return {
            searchQuery: this.filters.title || '',  
        };
    },
    methods: {
        searchReports() {
            this.$inertia.get(route('admin.reports.showQRCodeReports', { title: this.searchQuery }), {
                preserveState: true, 
                replace: true,
            });
        },
    },
    components: {
        AuthenticatedLayout,
    },
};
</script>

<style scoped>
h1 {
    color: #333;
}
</style>
