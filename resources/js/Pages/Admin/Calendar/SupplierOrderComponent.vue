<template>
  <div v-if="!showModal && !orderDeleted" class="order">
    <div v-if="parsedDetails?.fromFavorites" class="from-favorites">
      ⭐ From Favorites!
    </div>

    <div class="order-header">
      <img src="/images/event_title.png" alt="Order Title" />
      <h3>{{ calendarEvent.title }}</h3>
    </div>

    <div class="flex gap-2">
      <img src="/images/event_description.png" alt="Order Description" />
      <p>{{ calendarEvent.description }}</p>
    </div>

    <p class="order-time">
      <span role="img" aria-label="clock">⏰</span>
      <strong>Start:</strong> {{ formattedStartTime }} |
      <strong>End:</strong> {{ formattedEndTime }}
    </p>

    <div v-if="parsedDetails">
      <div class="order-details">
        <h4 class="text-lg font-semibold">Order Details</h4>
        <p><strong>Supplier:</strong> {{ parsedDetails.supplierName }}</p>
        <p><strong>Products:</strong></p>
        <ul>
          <li v-for="(item, index) in parsedDetails.productQuantities" :key="index">
            {{ item.productName }}: {{ item.quantity }}
          </li>
        </ul>
      </div>
    </div>

    <div v-if="calendarEvent.status != 'CLOSED'" class="order-actions">
      <button @click="editOrder" class="edit-btn">✏️ Edit</button>
      <button @click="deleteOrder" class="delete-btn">❌ Delete</button>
    </div>
    <div class="order-header-buttons">
      <button v-if="!parsedDetails?.fromFavorites" @click="toggleFavorite"
        :class="{ 'favorite-btn': !favourite, 'unfavorite-btn': favourite }">
        {{ favourite ? '⭐ Favorited' : '⭐ Add to Favorites' }}
      </button>

      <button v-if="calendarEvent.status === 'CLOSED' && parsedDetails?.s3_path" class="invoice-btn"
        @click="openInvoice">
        📄 See Invoice
      </button>
    </div>

  </div>

  <EditEventModal :show-modal="showModal" :selected-type="selectedType" :calendar-event="calendarEvent"
    @close="closeModal" :suppliers="suppliers" :products="products" />
</template>

<script>
import EditEventModal from './EditEventModal.vue';

export default {
  props: {
    calendarEvent: Object,
    suppliers: Array,
    products: Array,
    editMode: Boolean
  },
  data() {
    return {
      showModal: false,
      selectedType: this.calendarEvent.type,
      parsedDetails: this.parseDetails(this.calendarEvent.details),
      orderDeleted: false,
      favourite: this.calendarEvent.is_favorite
    };
  },
  components: {
    EditEventModal,
  },
  computed: {
    // Format start and end time
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
        console.error('Error parsing details:', e);
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
      this.$inertia.delete(route('admin.calendar.event.destroy', { id: this.calendarEvent.id }), {
        onSuccess: () => {
          this.orderDeleted = true;
          this.closeModal();
        },
        onError: (error) => {
          console.error('Error deleting order:', error);
        },
      });
    },

    // Format datetime string to a readable format
    formatDateTime(dateTime) {
      const date = new Date(dateTime);
      return date.toLocaleString(); // Adjust to your preferred format
    },
    
    toggleFavorite() {
      const newStatus = !this.favourite;

      this.favourite = newStatus;

      this.$inertia.put(route('admin.calendar.event.updateFavorites', { id: this.calendarEvent.id }), {
        is_favorite: newStatus,
      }, {
        preserveScroll: true,
        onSuccess: () => {
          this.calendarEvent.is_favorite = newStatus;
        },
        onError: (error) => {
          console.error('Error updating favorite status:', error);
          this.favourite = !newStatus;
        }
      });
    },

    openInvoice() {
      if (this.parsedDetails?.s3_path) {
        window.open(this.parsedDetails.s3_path, '_blank');
      } else {
        console.error("Invoice path not found!");
      }
    }

  },
};
</script>

<style scoped>
.order {
  background-color: #f4f4f9;
  color: #333;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  font-family: 'Arial', sans-serif;
  margin-bottom: 20px;
  transition: transform 0.3s ease-in-out;
}

.from-favorites {
  color: #333;
  font-weight: bold;
  padding: 8px 12px;
  border-radius: 5px;
  text-align: center;
  margin-top: 10px;
  margin-bottom: 5px;
}

.order:hover {
  transform: scale(1.02);
}

.order-header {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.order-header h3 {
  margin-left: 10px;
  font-size: 1.5rem;
  color: #2c3e50;
}

.order-description {
  font-size: 1rem;
  color: #7f8c8d;
  margin-bottom: 10px;
}

.order-time {
  font-size: 1rem;
  color: #34495e;
  margin-bottom: 10px;
}

.order-details {
  margin-top: 20px;
  background-color: #ecf0f1;
  padding: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.order-details h4 {
  font-size: 1.25rem;
  margin-bottom: 10px;
  color: #2c3e50;
}

.order-details p {
  font-size: 1rem;
  color: #34495e;
  margin: 5px 0;
}

.order-actions {
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

.order-header-buttons {
  margin-top: 1em;
  display: flex;
  gap: 10px;
  margin-left: auto;
}

.favorite-btn {
  background-color: #f1c40f;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.favorite-btn:hover {
  background-color: #f39c12;
}

.unfavorite-btn {
  background-color: #2ecc71;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.unfavorite-btn:hover {
  background-color: #27ae60;
}

.invoice-btn {
  background-color: #3498db;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.invoice-btn:hover {
  background-color: #2980b9;
}
</style>