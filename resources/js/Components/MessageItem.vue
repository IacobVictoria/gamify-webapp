<template>
    <div class="mb-2">
        <!-- replied-to message -->
        <div v-if="repliedMessage" class="bg-gray-100 p-2 rounded-lg mb-1"
        @click="$emit('scrollMessage',repliedMessage.id)">
            <span class="text-sm text-gray-500">Replied to:</span>
            <p class="text-gray-700">{{ repliedMessage.content }}</p>
        </div>

        <div @click="$emit('reply', message)" :class="{
            'text-right': message.sender_id === currentUser.id,
            'text-left': message.sender_id !== currentUser.id,
        }">
            <span :class="{
                'bg-blue-500 text-white': message.sender_id === currentUser.id,
                'bg-gray-200': message.sender_id !== currentUser.id,
            }" class="p-2 rounded-lg">
                {{ message.content }}
            </span>
            <MessageSeenSVG v-if="message.sender_id === currentUser.id" :isSeen="message.is_read === 1" />
        </div>
    </div>
</template>

<script>
import MessageSeenSVG from "@/Components/MessageSeenSVG.vue";

export default {
    components: {
        MessageSeenSVG,
    },
    props: {
        message: {
            type: Object,
            required: true,
        },
        repliedMessage: {
            type: Object,
            required: false,
        },
        currentUser: {
            type: Object,
            required: true,
        },
    },
    emits: ['reply','scrollMessage']
};
</script>
