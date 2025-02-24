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
                    this.addNotification('Comment nou!', event.message);
                });

            window.Echo.private(`leaderboard.${user.id}`)
                .listen('.UserMadeLeaderboard', (event) => {
                    this.addNotification('Felicitări!', event.message);
                });
            if (user.roles[0].name === 'Admin') {
                window.Echo.private('admin-channel')
                    .listen('.UserRemarkedOnQuiz', (event) => {
                        this.addNotification('New Remark!', event.message);
                    });
            }

            window.Echo.private(`obtain_badge.${user.id}`).listen('.ObtainBadge', (event) => {
                this.addNotification('Badge nou!', event.message);
            });

            window.Echo.private(`review_liked.${user.id}`).listen('.ReviewLikedEvent', (event) => {
                this.addNotification('Like nou!', event.message);
            });

            window.Echo.private(`user.${user.id}`)
                .listen('.UserScoreUpdatedEvent', (event) => {
                    this.addNotification('Felicitări!', event.message);
                })
                .listen('.FriendRequestAccepted', (event) => {
                    this.addNotification('New friend!', event.message);
                });
            window.Echo.private(`user.${user.id}`)
                .listen('.UserMedalAwarded', (event) => {
                    this.addNotification('New medal!', event.message);
                });
            window.Echo.private(`user_newDiscount.${user.id}`).listen('.DiscountApplied', (event) => {
                this.addNotification('New discount', event.message)
            });
            window.Echo.private(`user_newEvent.${user.id}`)
                .listen('.NewEventNotification', (event) => {
                    this.addNotification('New Event', event.message);
                });
            window.Echo.private(`friend-requests.${user.id}`).listen('.FriendRequestSent', (event) => {
                this.addNotification('New friend request', event.message);
            });
            window.Echo.private(`admin-channel.${user.id}`).listen('.SupplierOrderError', (event) => {
                this.addNotification('Error', event.message);
            });
            window.Echo.private(`admin-channel.${user.id}`).listen('.SupplierOrderSuccess', (event) => {
                this.addNotification('Success', event.message);
            });
            window.Echo.private(`user_reminder.${user.id}`)
                .listen('.EventReminderNotification', (event) => {
                    this.addNotification('Reminder!', event.message);
                })

            window.Echo.private(`user_newProduct.${user.id}`)
                .listen('.NewProductNotification', (event) => {
                    this.addNotification('New Product', event.message);
                });
            window.Echo.private(`user_restockProduct.${user.id}`)
                .listen('.ProductRestockedNotification', (event) => {
                    this.addNotification('Restock Product', event.message);
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