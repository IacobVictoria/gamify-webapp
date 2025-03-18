<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 393 80" style="fill: var(--Fresh-Mint, #fff2e5)">
        <path d="M393 80V53C347.667 55.6667 241.8 57.2 181 42C105 23 50 0 0 0V80H393Z" fill="#fff2e5" />
    </svg>
    <section class="bg-[#fff2e5] py-12 text-gray-900 -mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:flex lg:items-center lg:gap-12">

            <!-- Coloană 1: Scan to Discover -->
            <div class="flex flex-col items-center">
                <img class="w-72 h-72 object-cover" :src="imagePath('/landing/qr_code1.png')" alt="Scan to Discover" />

                <h2 class="text-2xl font-extrabold text-[#F45D3A] mt-6">
                    🔍 Scan to Discover!
                </h2>
                <p class="mt-3 text-lg text-gray-700 text-center px-6">
                    Scan this QR code to explore **nutritional values, reviews**, and **seasonal discounts** for your
                    favorite snacks!
                </p>

                <button @click="startQrScanner"
                    class="mt-4 px-6 py-3 text-lg font-semibold bg-[#F45D3A] hover:bg-[#e35c2b] text-white rounded-lg shadow-md transition-all duration-300">
                    View Product 📦
                </button>
                <div v-if="scanning" id="qr-reader" class="mt-6 w-full max-w-md mx-auto"></div>
            </div>

            <!-- Coloană 2: Scan to Win -->
            <div class="flex flex-col items-center">
                <img class="w-72 h-72 object-cover" :src="imagePath('/landing/points1.png')" alt="Scan to Win" />
                <h2 class="text-2xl font-extrabold text-[#6ACAB1] mt-6">
                    🎁 Scan to Win!
                </h2>
                <p class="mt-3 text-lg text-gray-700 text-center px-6">
                    Create an account and scan QR codes to **earn points, unlock badges**, and **climb the
                    leaderboard!**
                </p>
                <div>
                    <inertia-link v-if="isLoggedIn() && authUserHasRole('User')" :href="route('user.dashboard')"
                        class="mt-4 px-6 py-3 no-underline text-lg font-semibold bg-[#6ACAB1] hover:bg-[#56b29b] text-white rounded-lg shadow-md transition-all duration-300">
                        Enter your dashboard to win points!
                    </inertia-link>

                    <button v-else
                        class="mt-4 px-6 py-3 text-lg font-semibold bg-[#6ACAB1] hover:bg-[#56b29b] text-white rounded-lg shadow-md transition-all duration-300">
                        Join & Start Earning 🚀
                    </button>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
import { Html5QrcodeScanner } from "html5-qrcode";

export default {
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

            this.$inertia.post(route('scan.product.find'), {
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
};
</script>

<style scoped>
#qr-reader {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>