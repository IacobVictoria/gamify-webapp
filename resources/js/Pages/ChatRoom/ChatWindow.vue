<template>
    <div class="flex flex-col">
        <div class="flex-1 overflow-y-auto p-4">
            <div v-for="message in messages" :key="message.id" class="mb-2">
                <div v-if="message.sender_id === currentUser.id" class="text-right">
                    <span class="bg-blue-500 text-white p-2 rounded-lg ">{{ message.content }}</span>
                    <MessageSeenSVG :isSeen="message.is_read === 1" />
                </div>
                <div v-else class="text-left">
                    <span class="bg-gray-200 p-2 rounded-lg mb-2">{{ message.content }}</span>
                </div>
            </div>
        </div>

        <!-- input pentru trimiterea mesajelor -->
        <div class="p-4 border-t flex items-center">
            <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Scrie un mesaj..."
                class="flex-1 p-2 border rounded-lg" />
            <button @click="sendMessage" class="ml-2 bg-blue-500 text-white p-2 rounded-lg">
                Trimite
            </button>
        </div>
    </div>
</template>

<script>
import MessageSeenSVG from "@/Components/MessageSeenSVG.vue";

import axios from "axios";

export default {
    props: {
        friend: {
            type: Object,
            required: true,
        },
        currentUser: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            messages: [],
            newMessage: "",
            isActive: false,
        };
    },
    beforeUnmount() {
        this.isActive = false;
    },
    mounted() {
        this.isActive = true;
        this.fetchMessages();

    },
    components: {
        MessageSeenSVG
    },
    methods: {
        async markMessagesAsRead() {
            try {
                await axios.put(`/user/user_chat/mark-read/${this.friend.id}`);
                // actualizăm local starea mesajelor
                this.messages = this.messages.map(message => {
                    if (message.sender_id === this.friend.id) {
                        message.is_read = true;
                    }
                    return message;
                });
            } catch (error) {
                console.error('Error marking messages as read:', error);
            }
        },

        async fetchMessages() {
            const response = await axios.get(`/user/user_chat/messages/${this.friend.id}`);
            this.messages = response.data;
            this.listenMessages();
        },

        async sendMessage() {
            if (this.newMessage.trim()) {
                const response = await axios.post(`/user/user_chat/messages/${this.friend.id}`, {
                    message: this.newMessage,
                });
                this.messages.push(response.data);
                this.newMessage = "";
            }
        },
        listenMessages() {
            window.Echo.private(`chat.${this.currentUser.id}`).listen('.ChatMessageSent', (event) => {
                this.messages.push(event.message);
                this.newMessage = "";
                // dacă suntem în conversația cu expeditorul, marcăm mesajul ca citit imediat
                if (this.isActive && event.message.sender_id === this.friend.id) {
                    this.markMessagesAsRead();
                }
            });

            window.Echo.private(`chat_read.${this.currentUser.id}`).listen('.MessageRead', (event) => {
                this.messages = this.messages.map(message => {
                    if (message.sender_id === this.currentUser.id) {
                        message.is_read = 1;
                    }
                    return message;
                });
            });
        }
    },

};
</script>
