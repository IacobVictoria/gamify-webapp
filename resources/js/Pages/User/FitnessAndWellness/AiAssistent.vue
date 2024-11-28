<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-10">
        <div class="w-full m-40 ai-assistant-container shadow rounded-lg p-6">
            <div class="chat-section">

                <div class="robot-container">
                    <img src="/images/ai_assistent.png" alt="AI Assistant" class="robot-image" />
                </div>

                <div class="chat-box">
                    <h1 class="chat-title">Let’s chat! Find more about your diet</h1>

                    <div class="questions-list">
                        <p class="subtitle">Some common questions:</p>
                        <ul>
                            <li @click="sendQuestion('Dietă pentru scădere în greutate')">Dietă pentru scădere în
                                greutate</li>
                            <li @click="sendQuestion('Dietă pentru creștere musculară')">Dietă pentru creștere musculară
                            </li>
                            <li @click="sendQuestion('Menținere sănătate')">Menținere sănătate</li>
                            <li
                                @click="sendQuestion('Care sunt cele mai bune alimente pentru creșterea masei musculare?')">
                                Care sunt cele mai bune alimente pentru creșterea masei musculare?</li>
                            <li @click="sendQuestion('Ce exerciții mă pot ajuta să ard calorii rapid?')">Ce exerciții mă
                                pot ajuta să ard calorii rapid?</li>
                            <li @click="sendQuestion('Cum pot înlocui zahărul în dieta mea?')">Cum pot înlocui zahărul
                                în dieta mea?</li>
                        </ul>
                    </div>

                    <div class="messages overflow-y-auto max-h-96 mb-6">
                        <div v-for="message in messages" :key="message.id" class="message">
                            <p :class="message.is_user ? 'user' : 'ai'">{{ message.content }}</p>
                        </div>
                        <div v-if="products.length" class="products">
                            <h2>Suggested Products</h2>
                            <ul>
                                <li v-for="product in products" :key="product.id">
                                    <strong>{{ product.name }}</strong>: {{ product.description }} - <em>{{
                                        product.price }} RON</em>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <form @submit.prevent="sendMessage" class="chat-form">
                        <textarea v-model="newMessage" placeholder="Type your message..." rows="3"></textarea>
                        <button type="submit" class="send-button">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const messages = ref([]);
        const newMessage = ref('');
        const products = ref([]);

        const sendMessage = async () => {
            try {
                const response = await axios.post('/user/wellness/openai/respond-messages', {
                    message: newMessage.value,
                });


                messages.value.push({ content: newMessage.value, is_user: true });
                messages.value.push({ content: response.data.response, is_user: false });

                if (response.data.products) {
                    products.value = response.data.products;
                }

                newMessage.value = '';
            } catch (error) {
                console.error('Error sending message:', error);
            }
        };

        const sendQuestion = (question) => {
            newMessage.value = question;
            sendMessage();
        };

        return { messages, newMessage, sendMessage, sendQuestion, products };
    },
};
</script>
<style scoped>
.ai-assistant-container {
    display: flex;
    justify-content: space-between;
    background-color: #121212;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.chat-section {
    display: flex;
    flex: 1;
}

.robot-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.robot-image {
    width: 50%;
    height: auto;
    border-radius: 50%;
}

.chat-box {
    flex: 2;
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 10px;
    max-width: 700px;
    color: #fff;
}

.chat-title {
    color: #4CAF50;
    font-size: 1.8rem;
    margin-bottom: 20px;
}

.questions-list {
    margin-bottom: 20px;
}

.questions-list ul {
    list-style-type: none;
    padding: 0;
}

.questions-list li {
    background-color: #2c2c2c;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.questions-list li:hover {
    background-color: #4CAF50;
}

.messages {
    margin-bottom: 20px;
    overflow-y: auto;
    max-height: 24rem;
}

.message {
    padding: 8px;
    border-radius: 5px;
    margin: 10px 0;
}

.message.user {
    background-color: #4CAF50;
    color: white;
    text-align: right;
}

.message.ai {
    background-color: #333;
    color: white;
}

.chat-form {
    display: flex;
    flex-direction: column;
}

textarea {
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #333;
    background-color: #222;
    color: #fff;
}

.send-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.send-button:hover {
    background-color: #45a049;
}
</style>