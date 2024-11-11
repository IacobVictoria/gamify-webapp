<template>
    <div class="flex flex-col">
        <div class="flex-1 overflow-y-auto p-4">
            <div v-for="message in messages" :key="message.id" class="mb-2">
                <div v-if="message.sender_id === currentUser.id" class="text-right">
                    <span class="bg-blue-500 text-white p-2 rounded-lg ">{{ message.content }}</span>
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
        };
    },
    mounted() {
           this.fetchMessages();
       
    },
    methods: {
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
        listenMessages(){
            window.Echo.private(`chat.${this.currentUser.id}`).listen('.ChatMessageSent',(event)=>{
                this.messages.push(event.message);
                    this.newMessage = "";
                });
          }
        
    },

};
</script>
