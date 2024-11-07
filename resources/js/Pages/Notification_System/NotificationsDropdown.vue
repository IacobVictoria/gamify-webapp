<template>
    <div v-if="this.notifications.length > 0" class="notifications-list">
        <div v-for="(notification, index) in this.notifications" :key="index" class="notification-item">
            <p>{{ notification.message }}</p>
        </div>
    </div>
    <div v-else
        class=" notifications-list bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6">
        Nu exista notificari momentan!</div>
</template>
<script>
export default {
    props: {
        notifications: Array
    },

}
</script>
<style>
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
</style>