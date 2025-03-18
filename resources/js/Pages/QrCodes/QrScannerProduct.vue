<template>
    <div>
        <h2>Scan Product QR Code</h2>

        <button @click="startScanner" class="bg-indigo-600 text-white px-4 py-2 rounded-md">
            Start QR Scanner
        </button>

        <div v-if="showScanner" id="qr-reader" style="width: 500px; height: 500px;"></div>

        <div v-if="errorMessage" class="text-red-500 mt-4">
            {{ errorMessage }}
        </div>
        <div v-if="successMessage" class="text-green-500 mt-4">
            {{ successMessage }}
        </div>
    </div>
</template>

<script>
import { Html5QrcodeScanner, Html5QrcodeScanType } from 'html5-qrcode';
import { nextTick } from 'vue';

export default {
    props: {
        type: {
            type: String,
            required: true
        },
        error: {
            type: String,
            default: null
        },
        success: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            showScanner: false,
            scanner: null,
            scannedData: null, // keep track of scanned data
            successMessage: this.success ,
            errorMessage: this.error
        };
    },
    methods: {
        async startScanner() {
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
        
            if (this.scannedData === decodedText) {
                return; // Avoid reprocessing the same scan
            }

            this.scannedData = decodedText;
            this.errorMessage = null;

            // Stop scanning
            if (this.scanner) {
                this.scanner.clear();
            }
            try {
                let response;
                if (this.type === 'post') {
                    response = await this.$inertia.post('/qr-scanner/scan', { qrCode: decodedText });
                } else if (this.type === 'patch') {
                    response = await this.$inertia.patch('/qr-scanner', { qrCode: decodedText });
                }

                // Handle invalid QR code response (assuming server sends 400 or custom error for invalid QR)
                if (response.status !== 200 || response.data.invalid) {
                    this.errorMessage = 'Invalid QR code';
                    this.showScanner = false; // Close the scanner
                }
            } catch (error) {
                console.error('Error during scan request:', error);
                this.errorMessage = 'Invalid QR code'; // In case of error, show the invalid QR message
                this.showScanner = false; // Close the scanner
            }

        }
    }
};
</script>

<!-- permisiuni pt camera + upload file -->