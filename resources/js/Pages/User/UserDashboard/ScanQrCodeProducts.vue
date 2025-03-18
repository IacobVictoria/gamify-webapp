<template>
    <div class="flex flex-col items-center text-center mt-12">
        <h2 class="text-2xl font-extrabold">Earn Rewards by Scanning!</h2>
        <p class="mt-2 text-lg opacity-80">
            Scan the QR code from your purchased products and collect points to unlock badges & exclusive perks! 🎉
        </p>

        <button @click="startQrScanner"
            class="mt-5 flex items-center gap-2 px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-full shadow-md transition duration-300">
            Earn Points Now! 🚀
        </button>
        <div v-if="scanning" id="qr-reader" class="mt-6 w-full max-w-md mx-auto"></div>
        <div v-if="$page.props.errorMessage" class="flex flex-row items-start mt-2">
            <div class="flex-shrink-0">
                <XCircleIcon class=" w-6 h-6 text-red-400" aria-hidden="true" />
            </div>
            <div class="ml-3  flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">{{ $page.props.errorMessage }}
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import { Html5QrcodeScanner } from "html5-qrcode";
import { XCircleIcon } from '@heroicons/vue/24/outline';

export default {
    components: {
        XCircleIcon
    },
    data() {
        return {
            scanning: false,
            decodedText: "",
            qrScanner: null,
        };
    },
    methods: {
        startQrScanner() {
            if (this.qrScanner) {
                this.qrScanner.clear();
            }

            this.scanning = true;

            this.$nextTick(() => {
                this.qrScanner = new Html5QrcodeScanner(
                    "qr-reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    false
                );

                this.qrScanner.render(this.onScanSuccess, this.onScanFailure);
            });
        },

        onScanSuccess(decodedText, decodedResult) {
            this.decodedText = decodedText;
            this.scanning = false;

            this.$inertia.post(route('scan.product.earn'), {
                qrCode: decodedText,
            }, {
                preserveState: true,
                preserveScroll: true
            });

            if (this.qrScanner) {
                this.qrScanner.clear();
            }
        },

        onScanFailure(error) {


        },
    },
};</script>