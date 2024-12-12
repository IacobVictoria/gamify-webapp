<template>
    <div v-if="!showModal && !eventDeleted" class="event"> 
        <div class="event-header">
            <img src="/images/event_title.png" alt="">
            <h3>{{ calendarEvent.title }}</h3>
        </div>
        <div class="flex gap-2">
            <img src="/images/event_description.png" alt="">
            <p>{{ calendarEvent.description }}</p>
        </div>
        <p>Status: {{ calendarEvent.status }}</p>
        <p class="event-time">
            <span role="img" aria-label="clock">⏰</span>
            <strong>Start:</strong> {{ calendarEvent.start }} |
            <strong>End:</strong> {{ calendarEvent.end }}
        </p>

        <!-- Afișează butoanele de edit și delete doar dacă evenimentul nu este closed -->
        <div v-if="calendarEvent.status !== 'closed'" class="event-actions">
            <button @click="editEvent" class="edit-btn">✏️ Edit</button>
            <button @click="deleteEvent" class="delete-btn">❌ Delete</button>
        </div>

        <!-- Butonul de download participanți, doar dacă evenimentul este closed -->
        <div v-if="calendarEvent.status === 'closed'" class="event-actions">
            <button @click="downloadParticipants" class="download-btn">📥 Download Participants</button>
        </div>

    </div>
    <EditEventModal :show-modal="showModal" :selected-type="selectedType" :calendar-event="calendarEvent"
        @close="closeModal">
    </EditEventModal>
</template>

<script>
import EditEventModal from './EditEventModal.vue';

export default {
    props: {
        calendarEvent: Object,
        editMode: Boolean
    },
    data() {
        return {
            showModal: false,
            selectedType: this.calendarEvent.type,
            eventDeleted: false 
        };
    },
    components: {
        EditEventModal
    },
    methods: {
        editEvent() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false; 
            this.selectedType = null; 
        },
        deleteEvent() {
            this.$inertia.delete(route('admin.calendar.event.destroy', { id: this.calendarEvent.id }), {
                onSuccess: () => {
                    this.eventDeleted = true;
                    this.closeModal(); 
                },
                onError: (error) => {
                    console.error("Error deleting event:", error);
                }
            });
        },
        downloadParticipants() {
            // Trimitere cerere pentru a descărca lista participanților
            this.$inertia.get(route('admin.event.participants.download', { eventId: this.calendarEvent.id }));
        }
    }
}
</script>

<style scoped>
.event {
    background-color: #f4f4f9;
    color: #333;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
    margin-bottom: 20px;
    transition: transform 0.3s ease-in-out;
}

.event:hover {
    transform: scale(1.02);
}

.event-header {
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

.event-actions {
    display: flex;
    gap: 10px;
}

.edit-btn,
.delete-btn,
.download-btn {
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

.download-btn {
    background-color: #27ae60;
    color: white;
}

.edit-btn:hover {
    background-color: #2980b9;
}

.delete-btn:hover {
    background-color: #c0392b;
}

.download-btn:hover {
    background-color: #2ecc71;
}
</style>
