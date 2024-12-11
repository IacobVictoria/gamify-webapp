<template>
  <div v-if="!showModal && !eventDeleted" class="discount"> 
      <div class="discount-header">
          <img src="/images/event_title.png" alt="">
          <h3>{{ calendarEvent.title }}</h3>
      </div>
      <div class="flex gap-2">
          <img src="/images/event_description.png" alt="">
          <p>{{ calendarEvent.description }}</p>
      </div>
      <p class="event-time">
          <span role="img" aria-label="clock">⏰</span>
          <strong>Start:</strong> {{ calendarEvent.start }} |
          <strong>End:</strong> {{ calendarEvent.end }}
      </p>

      <div v-if="parsedDetails">
          <div class="discount-details">
              <h4 class="text-lg font-semibold">Discount Details</h4>
              <p><strong>Apply To:</strong> {{ parsedDetails.applyTo }}</p>
              <p v-if="parsedDetails.applyTo === 'categories'"><strong>Category:</strong> {{ parsedDetails.category }}</p>
              <p><strong>Discount:</strong> {{ parsedDetails.discount }}%</p>
          </div>
      </div>

      <div v-if="editMode" class="discount-actions">
          <button @click="editEvent" class="edit-btn">✏️ Edit</button>
          <button @click="deleteEvent" class="delete-btn">❌ Delete</button>
      </div>
  </div>
  <EditEventModal :show-modal="showModal" :selected-type="selectedType" :calendar-event="calendarEvent" @close="closeModal" :categories="categories">
  </EditEventModal>
</template>

<script>
import EditEventModal from './EditEventModal.vue';

export default {
  props: {
      calendarEvent: Object,
      categories: Array,
      editMode: Boolean
  },
  data() {
      return {
          showModal: false,
          selectedType: this.calendarEvent.type,
          parsedDetails: this.parseDetails(this.calendarEvent.details),
          eventDeleted: false // Flag pentru a urmări dacă evenimentul a fost șters
      }
  },
  components: {
      EditEventModal
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
          this.$inertia.delete(route('admin.calendar.event.destroy', { id: this.calendarEvent.id }), {
              onSuccess: () => {
                  this.eventDeleted = true;
                  this.closeModal(); 
              },
              onError: (error) => {
              }
          });
      }
  }
}
</script>

<style scoped>
.discount {
  background-color: #f4f4f9;
  color: #333;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  font-family: 'Arial', sans-serif;
  margin-bottom: 20px;
  transition: transform 0.3s ease-in-out;
}

.discount:hover {
  transform: scale(1.02);
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
  background-color: #ecf0f1;
  padding: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.discount-details h4 {
  font-size: 1.25rem;
  margin-bottom: 10px;
  color: #2c3e50;
}

.discount-details p {
  font-size: 1rem;
  color: #34495e;
  margin: 5px 0;
}
</style>
