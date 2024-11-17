<template>
    <div class="w-1/4 p-4 border-r">
        <h2 class="text-lg font-semibold mb-4">Conversațiile tale</h2>
        <ul>
            <li v-for="conversation in conversations" :key="conversation.friend.id"
                @click="selectConversation(conversation.friend)"
                class="p-2 mb-2 cursor-pointer bg-gray-200 rounded-lg hover:bg-gray-300">
                <p class="font-semibold">{{ conversation.friend.name }}</p>
                <p class="text-sm font-semibold">Expeditor: {{ conversation.lastMessage.sender.name }}</p>
                <p class="text-gray-500 text-sm">Ultimul mesaj: {{ conversation.lastMessage.content }}</p>
                <p class="text-xs text-gray-400">{{ conversation.sent_at }}</p>
            </li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps({
    conversations: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['selectConversation']);

function selectConversation(friend) {
    emit('selectConversation', friend);
}
</script>
