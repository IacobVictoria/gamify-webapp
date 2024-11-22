<template>
    <div class="w-1/4 p-4 border-r">
        <h2 class="text-lg font-semibold mb-4">Conversațiile tale</h2>
        <ul>
            <li v-for="conversation in conversations" :key="conversation.friend.id"
                @click="selectConversation(conversation.friend)"
                class="p-2 mb-2 cursor-pointer bg-gray-200 rounded-lg hover:bg-gray-300 relative">
                <!-- <p>{{ conversation.status }}</p> -->
                <p :class="conversation.status === 'Online' ? 'text-green-500' : 'text-gray-500'">{{ conversation.status
                    }}</p>

                <p class="font-semibold">{{ conversation.friend.name }}</p>
                <p class="text-sm font-semibold">Expeditor: {{ conversation.lastMessage.sender.name }}</p>
                <p class="text-gray-500 text-sm">Ultimul mesaj: {{ conversation.lastMessage.content }}</p>
                <p class="text-xs text-gray-400">{{ conversation.sent_at }}</p>
                <div v-if="conversation.unreadCount > 0" class="notification-badge">
                    {{ conversation.unreadCount }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        conversations: {
            type: Array,
            required: true,
        },
        currentUser: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            selectedConversation: null,

        };
    },
    methods: {
        selectConversation(friend) {
            this.selectedConversation = friend.id;

            // mark the conversation as opened
            const conversation = this.conversations.find(convo => convo.friend.id === friend.id);
            if (conversation) {
                if (!conversation.hasBeenOpened) {
                    // it's the first time opening, reset unread count and mark as opened
                    conversation.unreadCount = 0;
                    conversation.hasBeenOpened = true;
                }
            }

            this.$emit('selectConversation', friend);
        },

        checkUserStatus(userId) {
            axios.get(`/user/user_chat/check-status/${userId}`).then(response => {
                const status = response.data.status;
                const conversation = this.conversations.find(convo => convo.friend.id === userId);
                if (conversation) {
                    conversation.status = status;
                }
            });
        }
    },
    mounted() {
        this.conversations.forEach(conversation => {
            this.checkUserStatus(conversation.friend.id);
        });
        window.Echo.private(`user_message.${this.currentUser.id}`)
            .listen('.MessageUnreadUpdated', (event) => {
                const conversation = this.conversations.find(convo => convo.friend.id === event.friendId);

                if (conversation) {
                    if (this.selectedConversation !== event.friendId && !conversation.hasBeenOpened) {
                        // if conversation is not opened and not yet marked, update unread count
                        conversation.unreadCount = event.unreadCount;
                    }
                }
            });

        window.Echo.channel('chat_status')
            .listen('.UserStatusChanged', (event) => {
                const conversation = this.conversations.find(convo => convo.friend.id === event.userId);
                if (conversation) {
                    conversation.status = event.status;
                }
            });

    },
    watch: {
        selectedConversation(newVal) {
            console.log('Selected conversation changed to:', newVal);
        },


    },

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
    margin: 10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    font-size: 12px;
    line-height: 20px;
}
</style>
