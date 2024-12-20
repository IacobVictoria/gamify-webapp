<template>
    <div class="bg-white p-8">
        <AuthenticatedLayout>
            <!-- Admin Greeting -->
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 text-center mt-32">Hello Admin, here are your files</h1>
            </header>

            <main class="space-y-24 space-x-10 flex justify-center">
                <!-- Folder QR Codes -->
                <div v-if="reports.qr_codes.length" class="text-center cursor-pointer">
                    <inertia-link :href="route('admin.reports.showQRCodeReports')" class="no-underline">
                        <img src="/images/folder_reports.png" alt="QR Codes Folder"
                            class="w-32 h-32 mx-auto hover:opacity-80">
                        <h3 class="mt-4 text-xl font-semibold">QR Codes</h3>
                    </inertia-link>
                </div>

                <!-- Folder Participants List -->
                <div v-if="reports.list_participants.length" class="text-center cursor-pointer">
                    <inertia-link :href="route('admin.reports.showParticipantsList')" class="no-underline">
                        <img src="/images/folder_reports.png" alt="Participants Folder"
                            class="w-32 h-32 mx-auto hover:opacity-80">
                        <h3 class="mt-4 text-xl font-semibold">Participants List</h3>
                    </inertia-link>

                </div>
                <!-- Folder Supplier Invoices List -->
                <div v-if="reports.supplier_invoices.length" class="text-center cursor-pointer">
                    <inertia-link :href="route('admin.reports.showSupplierInvoicesList')" class="no-underline">
                        <img src="/images/folder_reports.png" alt="Participants Folder"
                            class="w-32 h-32 mx-auto hover:opacity-80">
                        <h3 class="mt-4 text-xl font-semibold">Supplier Invoices</h3>
                    </inertia-link>
                </div>
            </main>
        </AuthenticatedLayout>
    </div>
</template>

<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    props: {
        reports: Object, // Datele trimise de backend pentru QR Codes și Participants
    },
    components: {
        AuthenticatedLayout,
    },
    methods: {
        navigateTo(route) {
            // Navighează la ruta specificată
            window.location.href = route;
        },
    },
};
</script>

<style scoped>
h1 {
    color: #333;
}

img {
    transition: opacity 0.3s ease;
}

img:hover {
    opacity: 0.8;
}
</style>
