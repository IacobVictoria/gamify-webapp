<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                            <h2 class="text-2xl font-bold mb-4">{{ event.title }}</h2>
                            <p class="text-lg mb-4">{{ event.description }}</p>
                            <p><strong>Start:</strong> {{ event.start }} - <strong>End:</strong> {{ event.end }}</p>

                            <!-- Butonul de participare -->
                            <div v-if="!isLoggedIn()">
                                <p class="mt-4">Please <inertia-link :href="route('login')"
                                        class="text-blue-500">login</inertia-link> to participate.</p>
                            </div>
                            <div v-else>
                                <!-- Verificăm dacă evenimentul este blocat -->
                                <div v-if="isEventLocked && !isParticipant">
                                    <p class="mt-4 text-red-500">Event has already started or you cannot register
                                        anymore.</p>
                                </div>
                                <div v-else>
                                    <button v-if="!isParticipant" @click="downloadQRCode"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">
                                        Participate & Download QR Code
                                    </button>
                                    <button v-if="isParticipantConfirmed"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">
                                        See webinar
                                    </button>
                                    <button v-else-if="isParticipant && !isParticipantConfirmed" @click="toggleScanner"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">
                                        You are already a participant! Scan to enter the webinar
                                    </button>

                                    <div v-if="showScanner" id="qr-reader" style="width: 500px; height: 500px;"></div>

                                    <div v-if="errorMessage" class="text-red-500 mt-4">
                                        {{ errorMessage }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import Swal from 'sweetalert2';

import { Html5QrcodeScanner, Html5QrcodeScanType } from 'html5-qrcode';
import { nextTick } from 'vue';
export default {
    props: {
        event: Object,
        qrCode: String,
        isParticipant: Boolean,
        isEventLocked: Boolean, // Adăugăm această proprietate pentru a verifica dacă evenimentul este blocat
        isParticipantConfirmed: Boolean

    },

    components: {
        Layout
    },
    data() {
        return {
            showScanner: false,
            scanner: null,
            scannedData: null, // keep track of scanned data
            successMessage: this.success,
            errorMessage: this.error
        }
    },
    methods: {
        downloadQRCode() {
            // Deschidem tab-ul cu QR code-ul evenimentului
            this.makeParticipant();
            if (this.qrCode) {
                const link = document.createElement('a');
                link.href = this.qrCode;
                link.download = `${this.event.title}_QRCode.png`;
                link.setAttribute('target', '_blank');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('No QR Code found for this event.');
            }
        },
        makeParticipant() {
            this.$inertia.post(route('event.participant.store', { eventId: this.event.id }));
        },
        async toggleScanner() {
            this.showScanner = true;
            this.errorMessage = null;//restare

            // Wait for the DOM to update and `qr-reader` to be in the document
            await nextTick();

            const qrReaderElement = document.getElementById('qr-reader');
            if (qrReaderElement) {
                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                };

                try {
                    this.scanner = new Html5QrcodeScanner('qr-reader', config, false);
                    this.scanner.render(this.onScanSuccess.bind(this));
                } catch (error) {
                    console.error('Error initializing the QR code scanner:', error);
                }
            } else {
                console.error('QR Reader element not found');
            }
        },
        async onScanSuccess(decodedText) {

            this.scannedData = decodedText;
            this.errorMessage = null;

            // Oprește scannerul pentru a evita multiple scanări
            if (this.scanner) {
                this.scanner.clear();
            }
            await this.$inertia.post('/user/scanEvents', {
                qrCode: decodedText,
                eventId: this.event.id,
            });


        }
    }



};
</script>
