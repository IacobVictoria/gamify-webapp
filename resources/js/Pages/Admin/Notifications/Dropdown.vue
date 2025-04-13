<template>
    <div v-if="notifications.length > 0" class="notifications-list">
      <div v-for="(notification, index) in notifications" :key="index" class="notification-item">
        <p>{{ notification.message }}</p>
        <div v-if="!notification.handled && handle">
          <button @click="markAsHandled(notification.id)" class="btn-handle">Mark as Handled</button>
        </div>
      </div>
    </div>
    <div v-else class="no-notifications">
      Nu sunt notificări pentru admini!
    </div>
  </template>
  
  <script>
  export default {
    props: {
      notifications: Array,
      handle: Boolean
    },
    methods: {
      markAsHandled(notificationId) {
        this.$emit('markHandled', notificationId);
      }
    }
  };
  </script>
  
  <style scoped>
  .notifications-list {
    padding: 10px;
  }
  
  .notification-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
  }
  
  .notification-item:last-child {
    border-bottom: none;
  }
  
  .btn-handle {
    background-color: #4caf50;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
  }
  
  .no-notifications {
    padding: 10px;
    text-align: center;
    background-color: #f9f9f9;
    border-radius: 4px;
  }
  </style>
  