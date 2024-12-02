<template>
    <div
        class="mt-20 relative mx-auto border-gray-800 dark:border-gray-800 bg-gray-800  border-[14px] rounded-[2.5rem] h-[700px] w-[500px]">
        <div class="h-[32px] w-[3px] bg-gray-800 dark:bg-gray-800 absolute -start-[17px] top-[72px] rounded-s-lg"></div>
        <div class="h-[46px] w-[3px] bg-gray-800 dark:bg-gray-800 absolute -start-[17px] top-[124px] rounded-s-lg">
        </div>
        <div class="h-[46px] w-[3px] bg-gray-800 dark:bg-gray-800 absolute -start-[17px] top-[178px] rounded-s-lg">
        </div>
        <div class="h-[64px] w-[3px] bg-gray-800 dark:bg-gray-800 absolute -end-[17px] top-[142px] rounded-e-lg"></div>

        <div class="rounded-[2rem] overflow-hidden h-[650px] w-[450px] bg-white dark:bg-gray-800 p-2">
            <div class="flex flex-col h-full">
                <div ref="scrollContainer" class="flex-1 overflow-y-auto p-4">
                    <div v-for="message in messages" :key="message.id" :id="'message-' + message.id" class="mb-2">
                        <MessageItem :key="message.id" :message="message"
                            :repliedMessage="findMessageById(message.reply_to_message_id)" :currentUser="currentUser"
                            @reply="setReplyMessage" @scrollMessage="findScrollMessage" />
                    </div>
                </div>

                <!-- Display reply preview -->
                <div v-if="replyMessage" class="p-2 bg-gray-100 border rounded mb-2">
                    <span class="text-sm text-gray-500">Replying to:</span>
                    <p class="text-gray-700">{{ replyMessage.content }}</p>
                    <button @click="clearReply" class="text-sm text-red-500">Cancel</button>
                </div>

                <!-- Input for sending messages -->
                <div class="p-4 border-t flex items-center gap-2" id="inputMessage">
                    <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Write a message..."
                        class="flex-1 p-2 border rounded-lg" />
                    <button @click="sendMessage" class="ml-2 bg-blue-500 text-white p-2 rounded-lg">Send</button>
                    <button @click="startRecording" class="bg-gray-300 p-2 rounded-full">
                        <MicrofoneSVG></MicrofoneSVG>
                    </button>
                    <button v-if="isRecording" @click="stopRecording" class="bg-red-500 text-white p-2 rounded-full">
                        Stop
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import MessageItem from "@/Components/MessageItem.vue";
import MessageSeenSVG from "@/Components/MessageSeenSVG.vue";
import MicrofoneSVG from "@/Components/MicrofoneSVG.vue";


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
            //media
            isRecording: false,
            mediaRecorder: null,
            audioChunks: [],
            isLoading: false,
            noMoreItems: false,
            offset: 0, 
            limit: 10, 
        };
    },
    beforeUnmount() {
        this.isActive = false;
        this.$refs.scrollContainer.removeEventListener("scroll", this.handleScroll);
    },
    mounted() {
        this.isActive = true;
        this.loadMessages().then(() => {
            this.scrollToLastMessage(); 
        }); 

        const container = this.$refs.scrollContainer;

        container.addEventListener("scroll", this.handleScroll);
    },
    components: {
        MessageSeenSVG,
        MessageItem,
        MicrofoneSVG
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
                this.$nextTick(() => {
                    this.scrollToLastMessage();
                });
                this.newMessage = "";
                this.clearReply(); // Clear reply after sending
            }
        },
        async startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(stream);
                this.mediaRecorder.start();
                this.audioChunks = [];
                this.isRecording = true;

                this.mediaRecorder.ondataavailable = (event) => {
                    this.audioChunks.push(event.data);
                };
            } catch (error) {
                console.error("Error starting recording:", error);
            }
        },
        stopRecording() {
            this.isRecording = false;
            this.mediaRecorder.stop();

            this.mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(this.audioChunks, { type: "audio/webm" });

                if (audioBlob.size > 0) {
                    await this.uploadAudio(audioBlob);
                } else {
                    console.error("Fișierul audio este gol!");
                }
            };
        }
        ,
        async uploadAudio(fileBlob) {
            const formData = new FormData();
            formData.append("file", fileBlob);  // "file" - cheia pentru a trimite fișierul
            formData.append("message_type", "file");

            try {
                const response = await axios.post(`/user/user_chat/messages/${this.friend.id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                this.messages.push(response.data);
                this.$nextTick(() => {
                    this.scrollToLastMessage();
                });
            } catch (error) {
                console.error("Error uploading file:", error);
            }
        }
        ,
        listenMessages() {
            window.Echo.private(`chat.${this.currentUser.id}`).listen('.ChatMessageSent', (event) => {
                this.messages.push(event.message);
                this.newMessage = "";
                // dacă suntem în conversația cu expeditorul, marcăm mesajul ca citit imediat
                if (this.isActive && event.message.sender_id === this.friend.id) {
                    this.markMessagesAsRead();
                }
                this.$nextTick(() => {
                    this.scrollToLastMessage();
                });
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
        scrollToLastMessage() {
            this.$nextTick(() => {
                const container = this.$refs.scrollContainer; //  containerul cu mesaje
                if (container) {
                    container.scrollTop = container.scrollHeight; // scroll la ultimul element
                }
            });
        },
        findScrollMessage(messageId) { // for replied message
            this.$nextTick(() => {
                const messageElement = document.getElementById(`message-${messageId}`); //id-ul custom pus in v-for
                if (messageElement) {
                    messageElement.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });
        },
        async loadMessages() {
            if (this.isLoading || this.noMoreItems) return;

            this.isLoading = true;
            const container = this.$refs.scrollContainer;

            try {
                // Salvează înălțimea curentă a containerului înainte de a adăuga noi mesaje
                const previousScrollHeight = container.scrollHeight;

                const response = await axios.get(`/user/user_chat/messages/${this.friend.id}`, {
                    params: {
                        offset: this.offset,
                        limit: this.limit,
                    },
                });

                if (response.data.length > 0) {
                    this.messages = [...response.data.reverse(), ...this.messages]; 
                    this.offset += this.limit; 
                } else {
                    this.noMoreItems = true; 
                }

                this.$nextTick(() => {
                    const newScrollHeight = container.scrollHeight;
                    container.scrollTop = newScrollHeight - previousScrollHeight;
                });
            } catch (error) {
                console.error("Error loading messages:", error);
            } finally {
                this.isLoading = false;
            }
        },
        handleScroll() {
            const container = this.$refs.scrollContainer;
            //  scroll-ul este aproape de partea de sus
            if (container.scrollTop <= 50 && !this.isLoading && !this.noMoreItems) {
                this.loadMessages(); 
            }
        },

    },



};
</script>
