<template>
    <div class="flex flex-col">
        <div class="flex-1 overflow-y-auto p-4">
            <div v-for="message in messages" :key="message.id" :id="'message-' + message.id" class="mb-2">
                <MessageItem :key="message.id" :message="message"
                    :repliedMessage="findMessageById(message.reply_to_message_id)" :currentUser="currentUser"
                    @reply="setReplyMessage" @scrollMessage="findScrollMessage" />
            </div>
        </div>

        <!-- display reply preview -->
        <div v-if="replyMessage" class="p-2 bg-gray-100 border rounded mb-2">
            <span class="text-sm text-gray-500">Replying to:</span>
            <p class="text-gray-700">{{ replyMessage.content }}</p>
            <button @click="clearReply" class="text-sm text-red-500">Cancel</button>
        </div>

        <!-- Input for sending messages -->
        <div class="p-4 border-t flex items-center" id="inputMessage">
            <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Write a message..."
                class="flex-1 p-2 border rounded-lg" />
            <button @click="sendMessage" class="ml-2 bg-blue-500 text-white p-2 rounded-lg">Send</button>
        </div>
    </div>
</template>

<script>
import MessageItem from "@/Components/MessageItem.vue";
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
            replyMessage: null,
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
        MessageSeenSVG,
        MessageItem
    },
    methods: {
        setReplyMessage(message) {
            this.replyMessage = message;
            this.$nextTick(() => {
                const inputMessage = document.getElementById('inputMessage');
                if (inputMessage) {
                    inputMessage.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });
        },
        clearReply() {
            this.replyMessage = null;
        },
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
        findMessageById(id) {
            return this.messages.find((message) => message.id === id);
        },
        async sendMessage() {
            if (this.newMessage.trim()) {
                const payload = {
                    message: this.newMessage,
                };

                if (this.replyMessage) {
                    payload.reply_to_message_id = this.replyMessage.id; // include reply ID if replying
                }

                const response = await axios.post(`/user/user_chat/messages/${this.friend.id}`, payload);
                this.messages.push(response.data);

                this.newMessage = "";
                this.clearReply(); // Clear reply after sending
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
        },

        findScrollMessage(messageId) {
            this.$nextTick(() => {
                const messageElement = document.getElementById(`message-${messageId}`); //id-ul custom pus in v-for
                if (messageElement) {
                    messageElement.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });
        },
    },



};
</script>
