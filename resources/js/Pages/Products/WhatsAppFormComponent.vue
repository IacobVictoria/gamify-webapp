<template>
    <div class="mt-8 p-6 border border-gray-200 rounded bg-gray-50">
        <h3 class="text-xl font-bold mb-4">Pasul 1</h3>
        <p class="text-gray-700 mb-2">Scan the QR code to interact with Twilio</p>
        <img src="/images/qr_code_wapp.png" alt="QR Code" class="w-80 h-80 mx-auto mb-6">

        <h3 class="text-xl font-bold mb-4">Pasul 2</h3>
        <p class="text-gray-700 mb-2">Introduceți numărul de telefon:</p>
        <input v-model="phoneNumber" type="text" placeholder="Număr de telefon"
            class="border border-gray-300 rounded w-full p-2 mb-4 focus:ring-2 focus:ring-green-500 focus:outline-none" />

        <button @click="submitForm"
            class="bg-green-500 text-white font-bold py-2 px-4 rounded shadow-lg hover:bg-green-600 transition">
            Submit
        </button>
    </div>
</template>

<script>
export default {
    props: {
        link: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            phoneNumber: "",
        };
    },
    methods: {
        submitForm() {
            if (this.phoneNumber.trim()) {
                this.$inertia.post(route('web.send-whatsapp'), {
                    message: this.link,
                    phoneNumber: this.phoneNumber
                });
            } else {
                alert("Introduceți un număr valid!");
            }
        },
    },
};
</script>