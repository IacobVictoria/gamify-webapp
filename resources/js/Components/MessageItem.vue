<template>
    <div class="flex items-start gap-2.5 mb-2">
        <img v-if="message.sender_id !== currentUser.id" class="w-8 h-8 rounded-full"
            :src="message.sender_gender === 'Male' ? '/images/male.png' : '/images/female.png'" alt="User Avatar" />
        <div v-if="repliedMessage"
            class="bg-gray-200 dark:bg-gray-600 text-sm text-gray-700 dark:text-gray-300 p-2 rounded-lg mt-2"
            @click="$emit('scrollMessage', repliedMessage.id)">
            <span class="text-sm text-gray-500">Replied to:</span>
            <p>{{ repliedMessage.content }}</p>
        </div>
        <div class="flex flex-col w-full max-w-[320px] p-4 border border-gray-200 rounded-e-xl rounded-es-xl dark:bg-gray-700"
            :class="{
                'self-end bg-blue-300 ': message.sender_id === currentUser.id,
                'bg-gray-100 text-gray-900 ': message.sender_id !== currentUser.id,
            }">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span v-if="message.sender_id !== currentUser.id"
                    class="text-sm font-semibold text-gray-900 dark:text-gray-400">
                    {{ message.sender_name }}
                </span>
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    {{ message.sent_at_formatted }}
                </span>
            </div>
            <div class="flex">
                <p class="text-sm font-normal py-2.5">
                    {{ message.content }}
                </p>
                <MessageSeenSVG v-if="message.sender_id === currentUser.id" :isSeen="message.is_read === 1"
                    class="mt-2 ml-4" />
            </div>

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
    emits: ['reply', 'scrollMessage'],

};
</script>
