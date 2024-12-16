<template>
    <div class="bg-white p-8">
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
                                <div v-if="isEventLocked">
                                    <p class="mt-4 text-red-500">Event has already started or you cannot register anymore.</p>
                                </div>
                                <div v-else>
                                    <button v-if="!isParticipant" @click="downloadQRCode"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">
                                        Participate & Download QR Code
                                    </button>
                                    <button v-else class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">
                                        You are already a participant! Scan to enter the webinar
                                    </button>
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

export default {
    props: {
        event: Object,
        qrCode: String,
        isParticipant: Boolean,
        isEventLocked: Boolean // Adăugăm această proprietate pentru a verifica dacă evenimentul este blocat
    },
    components: {
        Layout
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
        }
    }
};
</script>
