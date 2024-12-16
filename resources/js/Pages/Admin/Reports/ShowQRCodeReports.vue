<template>
    <div class="bg-white p-8">
        <AuthenticatedLayout>
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 text-center mt-32">QR Codes Reports</h1>
            </header>
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