<template>
    <div v-if="this.notifications.length > 0" class="notifications-list">
        <div v-for="(notification, index) in this.notifications" :key="index" class="notification-item">
            <p>{{ notification.message }}</p>
            <div v-if="!notification.handled && notification.type === 'FriendRequest'">
                <button @click=" handleResponse(notification.id, 'accept')" class="btn-accept">Accept</button>
                <button @click="handleResponse(notification.id, 'decline')" class="btn-decline">Decline</button>
            </div>
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
    methods: {
        async handleResponse(id, content) {
            await axios.post(`/user/user_friends/${id}/respond`, {
                action: content
            });
            const notificationIndex = this.notifications.findIndex(
                notification => notification.id === id
            );
            if (notificationIndex !== -1) {
                this.notifications[notificationIndex].handled = true;
            }
        }
    }
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

.btn-accept {
    background-color: #4caf50;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    margin-right: 5px;
}

.btn-decline {
    background-color: #f44336;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
}
</style>