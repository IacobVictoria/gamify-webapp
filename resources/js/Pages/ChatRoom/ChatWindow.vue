<template>
    <div
        class="w-full max-w-4xl mx-auto mt-10 h-[85vh] bg-white shadow-lg rounded-lg flex flex-col border border-gray-200"
    >
        <!-- Header Chat -->
        <div
            class="px-6 py-4 border-b bg-gray-100 flex items-center justify-between"
        >
            <h2 class="text-lg font-semibold text-gray-800">
                💬 Discuție cu {{ friend.name }}
            </h2>
            <span class="text-sm text-gray-500">{{ friend.email }}</span>
        </div>

        <!-- Zona Mesaje -->
        <div
            ref="scrollContainer"
            class="flex-1 overflow-y-auto px-6 py-4 space-y-2 bg-gray-50"
        >
            <div
                v-for="message in messages"
                :key="message.id"
                :id="'message-' + message.id"
                class="mb-2"
            >
                <MessageItem
                    :message="message"
                    :repliedMessage="
                        findMessageById(message.reply_to_message_id)
                    "
                    :currentUser="currentUser"
                    @reply="setReplyMessage"
                    @scrollMessage="findScrollMessage"
                />
            </div>
        </div>

        <!-- Răspuns preview -->
        <div v-if="replyMessage" class="bg-gray-100 border-t px-4 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-xs text-gray-500">Răspunde:</span>
                    <p class="text-sm font-medium text-gray-700">
                        {{ replyMessage.content }}
                    </p>
                </div>
                <button
                    @click="clearReply"
                    class="text-sm text-red-500 hover:underline"
                >
                    Anulează
                </button>
            </div>
        </div>

        <!-- Input Mesaj -->
        <div
            class="border-t px-4 py-3 bg-white flex items-center gap-2"
            id="inputMessage"
        >
            <input
                v-model="newMessage"
                @keyup.enter="sendMessage"
                placeholder="Scrie un mesaj..."
                class="flex-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
                @click="sendMessage"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Trimite
            </button>
            <button
                @click="startRecording"
                class="bg-gray-200 p-2 rounded-full"
            >
                <MicrofoneSVG />
            </button>
            <button
                v-if="isRecording"
                @click="stopRecording"
                class="bg-red-500 text-white p-2 rounded-full"
            >
                Stop
            </button>
            <button @click="shareWishlist" class="p-2 rounded-full">
                <img
                    :src="imagePath('orders/wishlist.png')"
                    class="h-10 w-10"
                />
            </button>
            <button @click="shareTopProducts">
                <img :src="imagePath('orders/store1.png')" class="h-10 w-10" />
            </button>
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

    components: {
        MessageSeenSVG,
        MessageItem,
        MicrofoneSVG,
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
        this.$refs.scrollContainer.removeEventListener(
            "scroll",
            this.handleScroll
        );
    },

    mounted() {
        this.isActive = true;
        this.loadMessages().then(() => {
            this.scrollToLastMessage();
        });

        const container = this.$refs.scrollContainer;

        container.addEventListener("scroll", this.handleScroll);
        this.listenMessages();
    },

    methods: {
        setReplyMessage(message) {
            this.replyMessage = message;
            this.$nextTick(() => {
                const inputMessage = document.getElementById("inputMessage");
                if (inputMessage) {
                    inputMessage.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
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
                this.messages = this.messages.map((message) => {
                    if (message.sender_id === this.friend.id) {
                        message.is_read = true;
                    }
                    return message;
                });
            } catch (error) {
                console.error("Error marking messages as read:", error);
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

                const response = await axios.post(
                    `/user/user_chat/messages/${this.friend.id}`,
                    payload
                );

                this.messages.push(response.data);
                this.$nextTick(() => {
                    this.scrollToLastMessage();
                });
                this.newMessage = "";
                this.clearReply(); // Clear reply after sending
            }
        },

        //AUDIO METHODS
        async startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                });
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
                const audioBlob = new Blob(this.audioChunks, {
                    type: "audio/webm",
                });

                if (audioBlob.size > 0) {
                    await this.uploadAudio(audioBlob);
                } else {
                    console.error("Fișierul audio este gol!");
                }
            };
        },
        async uploadAudio(fileBlob) {
            const formData = new FormData();
            formData.append("file", fileBlob); // "file" - cheia pentru a trimite fișierul
            formData.append("message_type", "file");

            try {
                const response = await axios.post(
                    `/user/user_chat/messages/${this.friend.id}`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                console.log(response.data);
                this.messages.push(response.data);
                this.$nextTick(() => {
                    this.scrollToLastMessage();
                });
            } catch (error) {
                console.error("Error uploading file:", error);
            }
        },

        // Real time chat
        listenMessages() {
            window.Echo.private(`chat.${this.currentUser.id}`).listen(
                ".ChatMessageSent",
                (event) => {
                    console.log(event.message);
                    this.messages.push(event.message);
                    this.newMessage = "";
                    // dacă suntem în conversația cu expeditorul, marcăm mesajul ca citit imediat
                    if (
                        this.isActive &&
                        event.message.sender_id === this.friend.id
                    ) {
                        this.markMessagesAsRead();
                    }
                    this.$nextTick(() => {
                        this.scrollToLastMessage();
                    });
                }
            );

            window.Echo.private(`chat_read.${this.currentUser.id}`).listen(
                ".MessageRead",
                (event) => {
                    this.messages = this.messages.map((message) => {
                        if (
                            message.sender_id === this.currentUser.id &&
                            !message.is_read
                        ) {
                            message.is_read = 1;
                        }
                        return message;
                    });
                }
            );
        },

        scrollToLastMessage() {
            this.$nextTick(() => {
                const container = this.$refs.scrollContainer; //  containerul cu mesaje
                if (container) {
                    container.scrollTop = container.scrollHeight; // scroll la ultimul element
                }
            });
        },

        findScrollMessage(messageId) {
            // for replied message
            this.$nextTick(() => {
                const messageElement = document.getElementById(
                    `message-${messageId}`
                ); //id-ul custom pus in v-for
                if (messageElement) {
                    messageElement.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
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

                const response = await axios.get(
                    `/user/user_chat/messages/${this.friend.id}`,
                    {
                        params: {
                            offset: this.offset,
                            limit: this.limit,
                        },
                    }
                );

                if (response.data.length > 0) {
                    this.messages = [
                        ...response.data.reverse(),
                        ...this.messages,
                    ];
                    this.offset += this.limit;
                } else {
                    this.noMoreItems = true;
                }

                this.$nextTick(() => {
                    const newScrollHeight = container.scrollHeight;
                    container.scrollTop =
                        newScrollHeight - previousScrollHeight;
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
            if (
                container.scrollTop <= 50 &&
                !this.isLoading &&
                !this.noMoreItems
            ) {
                this.loadMessages();
            }
        },

        //Buttons chat
        async shareWishlist() {
            const link = `${window.location.origin}/user/wishlist/public_store/${this.$page.props.user.public_token}`;
            const messageInput = `Uite lista mea de favorite! 🧡 ${link}`;

            const response = await axios.post(
                `/user/user_chat/messages/${this.friend.id}`,
                { message: messageInput }
            );
            this.messages.push(response.data);
            this.$nextTick(() => {
                this.scrollToLastMessage();
            });
        },

        async shareTopProducts() {
            try {
                const response = await axios.get("/user/top_products");
                const products = response.data;

                if (products.length === 0) return;

                const messageLines = products.map((product) => {
                    const url = `${window.location.origin}/products/${product.slug}`;
                    return `🔸 ${product.name} – ${url}`;
                });

                const finalMessage =
                    `📊 Acestea sunt cele mai comandate produse:\n` +
                    messageLines.join("\n");

                const responseFinal = await axios.post(
                    `/user/user_chat/messages/${this.friend.id}`,
                    {
                        message: finalMessage,
                    }
                );
                this.messages.push(responseFinal.data);
                this.scrollToLastMessage();
            } catch (error) {
                console.error("Failed to share top products:", error);
            }
        },
    },
};
</script>
