<template>
  <div class="work-event">
      <h3>{{ calendarEvent.title }}</h3>
      <p>{{ calendarEvent.description }}</p>
  </div>
</template>

<script setup>
defineProps({
  calendarEvent: Object,
});
</script>

<style scoped>
.work-event {
  background-color: #ffcc00;
  color: #333;
  padding: 10px;
  border-radius: 5px;
}
</style>
