<template>
    <div class="notification-center">
        <NotificationPopup v-for="(notification, index) in notifications" :key="index" :title="notification.title"
            :message="notification.message" @close="removeNotification(index)" />
    </div>
</template>

<script>
import NotificationPopup from './NotificationPopup.vue';
import { usePage } from '@inertiajs/vue3';

export default {
    components: { NotificationPopup },
    data() {
        return {
            notifications: [], 
        };
    },
    methods: {
        addNotification(title, message) {
            this.notifications.push({ title, message });
        },

        removeNotification(index) {
            this.notifications.splice(index, 1);
        },
    },
    mounted() {
        const { user } = usePage().props;

        if (user && user.id) {
            window.Echo.private(`comments.${user.id}`)
                .listen('.CommentEvent', (event) => {
                    this.addNotification('Notificare nouă', event.message);
                });
            window.Echo.private(`obtain_badge.${user.id}`).listen('.ObtainBadge', (event) => {
                console.log('heellp',event.message);
                this.addNotification('Badge nou', event.message);
            });
        }
    },
};
</script>

<style scoped>
.notification-center {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}
</style>