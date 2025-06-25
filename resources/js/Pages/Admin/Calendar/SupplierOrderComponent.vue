<template>
    <div
        v-if="!showModal"
        class="order"
        :class="{ 'ghost-event': calendarEvent.isGhost }"
    >
        <div v-if="calendarEvent.isGhost" class="ghost-banner">
            👻 Aceasta este o previzualizare a unui eveniment recurent viitor.
            Nu este încă listat.
        </div>
        <div v-if="parsedDetails?.fromFavorites" class="from-favorites">
            ⭐ Din Favorite!
        </div>

        <div class="order-header flex items-center gap-2 mb-2">
            <span class="text-xl">🏷️</span>
            <h3 class="text-xl font-semibold">{{ calendarEvent.title }}</h3>
        </div>

        <div class="flex items-start gap-2 mb-2">
            <span class="text-xl">📝</span>
            <p class="text-gray-700">{{ calendarEvent.description }}</p>
        </div>

        <p class="order-time">
            <span role="img" aria-label="clock">⏰</span>
            <strong>Data:</strong> {{ formattedStartTime }}
        </p>

        <div v-if="parsedDetails">
            <div class="order-details">
                <h4 class="text-lg font-semibold mb-4">📦 Order Details</h4>

                <div class="order-info mb-3">
                    <p>
                        <strong>Furnizor:</strong>
                        {{ parsedDetails.supplierName }}
                    </p>
                </div>

                <div class="order-products">
                    <p class="font-medium text-slate-700 mb-2">Produse:</p>
                    <ul class="product-list">
                        <li
                            v-for="(
                                item, index
                            ) in parsedDetails.productQuantities"
                            :key="index"
                        >
                            <span class="product-name">{{
                                item.productName
                            }}</span
                            >:
                            <span class="product-qty">{{ item.quantity }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div
            v-if="
                calendarEvent.status != 'CLOSED' &&
                !calendarEvent.isGhost &&
                calendarEvent.user_id === $page.props.user.id
            "
            class="order-actions"
        >
            <button
                v-if="!parsedDetails?.fromFavorites"
                @click="editOrder"
                class="edit-btn"
            >
                ✏️ Edit
            </button>
            <button @click="deleteOrder" class="delete-btn">❌ Șterge</button>
        </div>
        <div class="order-header-buttons">
            <button
                v-if="!parsedDetails?.fromFavorites && !calendarEvent.isGhost"
                @click="toggleFavorite"
                :class="{
                    'favorite-btn': !favourite,
                    'unfavorite-btn': favourite,
                }"
            >
                {{ favourite ? "⭐ Favorit" : "⭐ Adaugă la Favorite" }}
            </button>

            <button
                v-if="
                    calendarEvent.status === 'CLOSED' &&
                    parsedDetails?.s3_path &&
                    !calendarEvent.isGhost
                "
                class="invoice-btn"
                @click="openInvoice"
            >
                📄 Vezi Factura
            </button>
            <!-- Buton "Stop Recurrence" doar pentru ultimul eveniment recurent -->
            <div
                v-if="
                    calendarEvent?.is_last_recurring &&
                    calendarEvent.user_id === $page.props.user.id
                "
                class="recurrence-actions"
            >
                <button @click="stopRecurrence" class="stop-recurrence-btn">
                    ⏹ Oprește Recurența
                </button>
            </div>
        </div>
    </div>

    <EditEventModal
        :show-modal="showModal"
        :selected-type="selectedType"
        :calendar-event="calendarEvent"
        @close="closeModal"
        :suppliers="suppliers"
        :products="products"
    />
</template>

<script>
import EditEventModal from "./EditEventModal.vue";

export default {
    props: {
        calendarEvent: Object,
        suppliers: Array,
        products: Array,
        editMode: Boolean,
    },
    data() {
        return {
            showModal: false,
            selectedType: this.calendarEvent.type,
            parsedDetails: this.parseDetails(this.calendarEvent.details),
            favourite: this.calendarEvent.is_favorite,
        };
    },
    components: {
        EditEventModal,
    },
    computed: {
        formattedStartTime() {
            return this.formatDateTime(this.calendarEvent.start);
        },
        formattedEndTime() {
            return this.formatDateTime(this.calendarEvent.end);
        },
    },
    methods: {
        // Parse details from stringified JSON
        parseDetails(details) {
            try {
                return details ? JSON.parse(details) : null;
            } catch (e) {
                console.error("Error parsing details:", e);
                return null;
            }
        },

        editOrder() {
            this.showModal = true;
        },

        // Close the modal for editing
        closeModal() {
            this.showModal = false;
            this.selectedType = null;
        },

        // Handle deleting the order
        deleteOrder() {
            this.$inertia.delete(
                route("admin.calendar.event.destroy", {
                    id: this.calendarEvent.id,
                }),
                {
                    onSuccess: () => {
                        window.location.reload();
                        this.closeModal();
                    },
                    onError: (error) => {
                        console.error("Error deleting order:", error);
                    },
                }
            );
        },

        // Format datetime string to a readable format
        formatDateTime(dateTime) {
            const date = new Date(dateTime);
            return date.toLocaleString(); // Adjust to your preferred format
        },

        toggleFavorite() {
            const newStatus = !this.favourite;

            this.favourite = newStatus;

            this.$inertia.put(
                route("admin.calendar.event.updateFavorites", {
                    id: this.calendarEvent.id,
                }),
                {
                    is_favorite: newStatus,
                },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.calendarEvent.is_favorite = newStatus;
                    },
                    onError: (error) => {
                        console.error("Error updating favorite status:", error);
                        this.favourite = !newStatus;
                    },
                }
            );
        },

        openInvoice() {
            if (this.parsedDetails?.s3_path) {
                window.open(this.parsedDetails.s3_path, "_blank");
            } else {
                console.error("Invoice path not found!");
            }
        },
        stopRecurrence() {
            if (!confirm("Are you sure you want to stop this recurrence?"))
                return;

            this.$inertia.put(
                route("admin.calendar.event.stopRecurrence", {
                    id: this.calendarEvent.id,
                }),
                {},
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.calendarEvent.is_recurring = false; // Dezactivăm recurența local
                        this.calendarEvent.is_last_recurring = false; // Ascundem butonul
                    },
                    onError: (error) => {
                        console.error("Error stopping recurrence:", error);
                    },
                }
            );
        },
    },
};
</script>
<style scoped>
.order {
    background-color: #ffffff;
    color: #2c3e50;
    padding: 20px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
    font-family: "Segoe UI", sans-serif;
    margin-bottom: 24px;
}

.ghost-event {
    background-color: #f0f0f5;
    opacity: 0.8;
    border: 1px dashed #a0aec0;
}

.ghost-banner {
    background-color: #fbbf24;
    color: #fff;
    font-weight: bold;
    padding: 10px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 12px;
}

.from-favorites {
    background-color: #e0f7fa;
    color: #006064;
    font-weight: bold;
    padding: 10px;
    border-radius: 12px;
    text-align: center;
    margin: 12px 0;
}

.order-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.order-header h3 {
    font-size: 1.6rem;
    color: #1e293b;
}

.order-description {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 12px;
}

.order-time {
    font-size: 0.95rem;
    color: #475569;
    margin-bottom: 16px;
}

.order-details {
    background-color: #f9fafb;
    padding: 20px 24px;
    margin-top: 24px;
    color: #1e293b;
    font-family: "Segoe UI", sans-serif;
}

.order-details h4 {
    font-size: 1.3rem;
    color: #0f172a;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.order-info p {
    font-size: 1rem;
    color: #334155;
    margin: 4px 0 12px 0;
}

.order-products p {
    font-size: 0.95rem;
    color: #475569;
    margin-bottom: 8px;
}

.product-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
}

.product-list li {
    padding: 6px 0;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.95rem;
    display: flex;
    justify-content: space-between;
}

.product-name {
    font-weight: 500;
    color: #1e293b;
}

.product-qty {
    font-weight: 600;
    color: #0f172a;
}

.order-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}

.edit-btn,
.delete-btn,
.favorite-btn,
.unfavorite-btn,
.invoice-btn,
.stop-recurrence-btn {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.edit-btn {
    background-color: #3b82f6;
    color: white;
}

.edit-btn:hover {
    background-color: #2563eb;
    transform: scale(1.02);
}

.delete-btn {
    background-color: #ef4444;
    color: white;
}

.delete-btn:hover {
    background-color: #dc2626;
    transform: scale(1.02);
}

.favorite-btn {
    background-color: #facc15;
    color: #1f2937;
}

.favorite-btn:hover {
    background-color: #eab308;
}

.unfavorite-btn {
    background-color: #10b981;
    color: white;
}

.unfavorite-btn:hover {
    background-color: #059669;
}

.invoice-btn {
    background-color: #3b82f6;
    color: white;
}

.invoice-btn:hover {
    background-color: #2563eb;
}

.stop-recurrence-btn {
    background-color: #e11d48;
    color: white;
    margin-top: 12px;
}

.stop-recurrence-btn:hover {
    background-color: #be123c;
}

.order-header-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}
</style>
