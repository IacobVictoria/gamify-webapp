<template>
  <div class="notification-icon" @click="toggleDropdown()">
    <!-- Badge pentru notificări necitite -->
    <div v-if="unreadNotifications > 0" class="notification-badge">
      {{ unreadNotifications }}
    </div>

    <!-- Icon pentru notificări -->
    <svg width="36" height="36" fill="none" viewBox="0 0 24 24">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
        d="M17.25 12V10C17.25 7.1005 14.8995 4.75 12 4.75C9.10051 4.75 6.75 7.10051 6.75 10V12L4.75 16.25H19.25L17.25 12Z">
      </path>
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
        d="M9 16.75C9 16.75 9 19.25 12 19.25C15 19.25 15 16.75 15 16.75"></path>
    </svg>

    <!-- Dropdown pentru notificări -->
    <div v-if="isDropdownVisible" class="dropdown">
      <Dropdown :notifications="notifications" @markHandled="markHandled" :handle="true" />
    </div>
  </div>
</template>

<script>
import Dropdown from './Dropdown.vue';


export default {
  components: {
    Dropdown
  },
  data() {
    return {
      isDropdownVisible: false,
      notifications: [],
      unreadNotifications: 0
    };
  },
  mounted() {
    this.fetchUnreadNotifications();
    window.Echo.private(`notifications.${this.$page.props.user.id}`)
            .listen('.NotificationUpdatedEvent', (event) => {
                this.unreadNotifications = event.unreadNotifications;
            });
  },
  methods: {
    async toggleDropdown() {
      this.isDropdownVisible = !this.isDropdownVisible;
      if (this.isDropdownVisible && this.unreadNotifications > 0) {
        await axios.post(route('admin.notifications.markAsRead'));
        this.unreadNotifications = 0;
      }
    },
    async fetchUnreadNotifications() {
      try {
        const response = await axios.get(route('admin.notifications.getNotifications'));
        this.notifications = response.data.notifications;
        this.unreadNotifications = response.data.unreadNotifications;
      } catch (error) {
        console.error('Error fetching notifications:', error);
      }
    },
    async markHandled(notificationId) {
      try {
        await axios.post(`/admin/notifications/${notificationId}/handle`);
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification) {
          notification.handled = true;
        }
      } catch (error) {
        console.error('Error marking notification as handled:', error);
      }
    }
  }
};
</script>

<style scoped>
.notification-icon {
  position: relative;
  cursor: pointer;
}

.notification-badge {
  position: absolute;
  top: 0;
  right: 0;
  background-color: red;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  text-align: center;
  font-size: 12px;
  line-height: 20px;
}

.dropdown {
  position: absolute;
  top: 60px;
  right: 0;
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 300px;
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
}
</style>
