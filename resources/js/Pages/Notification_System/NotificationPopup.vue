<template>
    <transition name="fade">
      <div v-if="visible" class="notification-popup">
        <div class="notification-content">
          <strong>{{ title }}</strong>
          <p>{{ message }}</p>
          <button @click="closeNotification">Close</button>
        </div>
      </div>
    </transition>
  </template>
  
  <script>
  export default {
    props: {
      title: {
        type: String,
        required: true,
      },
      message: {
        type: String,
        required: true,
      }
    },
    data() {
      return {
        visible: true,
      };
    },
    methods: {
      closeNotification() {
        this.visible = false;
      }
    }
  };
  </script>
  
  <style scoped>
  .notification-popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #333;
    color: white;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    width: 250px;
    z-index: 1000;
  }
  .notification-content button {
    margin-top: 10px;
    background: transparent;
    border: none;
    color: #bbb;
    cursor: pointer;
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
  </style>
  