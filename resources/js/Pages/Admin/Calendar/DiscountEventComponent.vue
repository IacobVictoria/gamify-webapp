<template>
    <div
        v-if="!showModal"
        class="discount"
        :class="{ 'ghost-event': calendarEvent.isGhost }"
    >
        <div v-if="calendarEvent.isGhost" class="ghost-banner">
            👻 Aceasta este o previzualizare a unui eveniment recurent viitor.
            Nu este încă listat.
        </div>
        <div v-if="parsedDetails?.fromFavorites" class="from-favorites">
            ⭐ Din Favorite!
        </div>
        <div class="discount-header flex items-center gap-2 mb-2">
            <span class="text-xl">🏷️</span>
            <h3 class="text-xl font-semibold">{{ calendarEvent.title }}</h3>
        </div>

        <div class="flex items-start gap-2 mb-2">
            <span class="text-xl">📝</span>
            <p class="text-gray-700">{{ calendarEvent.description }}</p>
        </div>

        <p class="event-time">
            <span role="img" aria-label="clock">⏰</span>
            {{ calendarEvent.start }} |
            {{ calendarEvent.end }}
        </p>

        <div v-if="parsedDetails">
            <div class="discount-details">
                <h4 class="text-lg font-semibold">Detalii Reducere</h4>
                <p>
                    <strong>Aplicat la:</strong>
                    {{
                        parsedDetails.applyTo === "categories"
                            ? "o categorie"
                            : "toate produsele"
                    }}
                </p>
                <p v-if="parsedDetails.applyTo === 'categories'">
                    <strong>Categorie:</strong> {{ parsedDetails.category }}
                </p>
                <p><strong>Reducere:</strong> {{ parsedDetails.discount }}%</p>
            </div>
        </div>

        <div
            v-if="
                editMode &&
                !calendarEvent.isGhost &&
                calendarEvent.user_id === $page.props.user.id
            "
            class="discount-actions"
        >
            <!-- edit button apare cand nu este la favorite si cand  nu a inceput deja discount ul -->
            <button
                v-if="
                    !parsedDetails?.fromFavorites && !calendarEvent.is_started
                "
                @click="editEvent"
                class="edit-btn"
            >
                ✏️ Editează
            </button>
            <button @click="deleteEvent" class="delete-btn">❌ Șterge</button>
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
        </div>
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

    <EditEventModal
        :show-modal="showModal"
        :selected-type="selectedType"
        :calendar-event="calendarEvent"
        @close="closeModal"
        :categories="categories"
    >
    </EditEventModal>
</template>

<script>
import EditEventModal from "./EditEventModal.vue";

export default {
    props: {
        calendarEvent: Object,
        categories: Array,
        editMode: Boolean,
    },
    data() {
        return {
            showModal: false,
            selectedType: this.calendarEvent.type,
            parsedDetails: this.parseDetails(this.calendarEvent.details),
            favourite: this.calendarEvent.is_favorite,
            is_reccuring: this.calendarEvent?.is_last_recurring ?? false,
        };
    },
    components: {
        EditEventModal,
    },

    methods: {
        parseDetails(details) {
            try {
                return details ? JSON.parse(details) : null;
            } catch (e) {
                console.error("Error parsing details:", e);
                return null;
            }
        },

        editEvent() {
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selectedType = null;
        },

        deleteEvent() {
            this.$inertia.delete(
                route("admin.calendar.event.destroy", {
                    id: this.calendarEvent.id,
                }),
                {
                    onSuccess: () => {
                        window.location.reload();
                        this.closeModal();
                    },
                    onError: (error) => {},
                }
            );
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
                        this.calendarEvent.is_recurring = false;
                        is_reccuring = false;
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
.discount {
    color: #333;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: "Arial", sans-serif;
    margin-bottom: 20px;
}

.from-favorites {
    background-color: #fef3c7;
    color: #333;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 5px;
    text-align: center;
    margin-top: 10px;
    margin-bottom: 5px;
}

.ghost-event {
    background-color: #dcdde1;
    opacity: 0.8;
    border: 1px dashed #7f8c8d;
}

.ghost-banner {
    background-color: #f1c40f;
    color: #fff;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 5px;
    text-align: center;
    margin-bottom: 10px;
}

.discount-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.event-header h3 {
    margin-left: 10px;
    font-size: 1.5rem;
    color: #2c3e50;
}

.event-description {
    font-size: 1rem;
    color: #7f8c8d;
    margin-bottom: 10px;
}

.event-time {
    font-size: 1rem;
    color: #34495e;
    margin-bottom: 10px;
}

.event-status {
    font-size: 1rem;
    margin-bottom: 15px;
}

.discount-actions {
    display: flex;
    gap: 10px;
    margin-top: 1em;
}

.edit-btn,
.delete-btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.edit-btn {
    background-color: #3498db;
    color: white;
}

.delete-btn {
    background-color: #e74c3c;
    color: white;
}

.edit-btn:hover {
    background-color: #2980b9;
}

.delete-btn:hover {
    background-color: #c0392b;
}

.discount-details {
    margin-top: 20px;
    padding: 16px 20px;
    border-radius: 12px;
    transition: transform 0.2s ease-in-out;
}

.discount-details h4 {
    font-size: 1.35rem;
    margin-bottom: 12px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.discount-details h4::before {
    content: "💸";
    font-size: 1.4rem;
}

.discount-details p {
    font-size: 1rem;
    color: #2c3e50;
    margin: 6px 0;
    padding-left: 12px;
    position: relative;
}

.discount-details p::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #3498db;
    font-weight: bold;
}

.order-header-buttons {
    margin-top: 1em;
    display: flex;
    justify-content: flex-end;
    gap: 0.75em;
    align-items: center;
}

.favorite-btn {
    background-color: #f1c40f;
    color: white;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.favorite-btn:hover {
    background-color: #f39c12;
    transform: translateY(-1px);
}

.favorite-btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.unfavorite-btn {
    background-color: #2ecc71;
    color: white;
    padding: 8px 12px;
    border: none;
    margin-bottom: 1em;
    border-radius: 5px;
    cursor: pointer;
}

.unfavorite-btn:hover {
    background-color: #27ae60;
}

.stop-recurrence-btn {
    background-color: #e74c3c;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 1em;
}

.stop-recurrence-btn:hover {
    background-color: #c0392b;
}
</style>
