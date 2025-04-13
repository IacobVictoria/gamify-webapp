<template>
    <div class="group relative flex items-start gap-3 mb-4">
        <!-- Avatar -->
        <img
            v-if="message.sender_id !== currentUser.id"
            class="w-9 h-9 rounded-full"
            :src="
                message.sender_gender === 'Male'
                    ? '/images/male.png'
                    : '/images/female.png'
            "
            alt="Avatar"
        />

        <!-- Bubble mesaj -->
        <div
            class="relative group max-w-[75%] px-4 py-3 rounded-2xl shadow-md transition-all"
            :class="{
                'ml-auto bg-blue-500 text-white rounded-br-none':
                    message.sender_id === currentUser.id,
                'bg-gray-100 text-gray-900 rounded-bl-none':
                    message.sender_id !== currentUser.id,
            }"
        >
            <!-- Replied message (preview) -->
            <div
                v-if="repliedMessage"
                class="text-xs bg-white/30 dark:bg-black/20 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-md mb-2 cursor-pointer border border-gray-200"
                @click="$emit('scrollMessage', repliedMessage.id)"
            >
                <p class="font-semibold text-gray-500">↩️ Replied to:</p>
                <p class="truncate">{{ repliedMessage.content }}</p>
            </div>

            <!-- Nume și timp -->
            <div class="flex justify-between items-center mb-1">
                <span
                    v-if="message.sender_id !== currentUser.id"
                    class="text-sm font-semibold"
                >
                    {{ message.sender_name }}
                </span>
                <span class="text-xs text-gray-400 dark:text-gray-400">
                    {{ message.sent_at_formatted }}
                </span>
                <button
                    class="text-xs px-2 py-1 rounded text-blue-600"
                    @click="$emit('reply', message)"
                    title="Reply"
                >
                    🔁
                </button>
            </div>

            <!-- Conținut mesaj -->
            <div>
                <AudioPlayer
                    v-if="
                        message.message_type === 'file' &&
                        message.attachment_url
                    "
                    :option="{
                        src: cleanAttachmentUrl(message.attachment_url),
                        progressBarColor: '#2563EB',
                        indicatorColor: '#2563EB',
                        coverRotate: true,
                    }"
                    :class="[
                        'max-w-full',
                        message.sender_id === currentUser.id
                            ? 'text-blue-500'
                            : 'text-gray-100',
                    ]"
                />
                <p
                    v-else-if="isUrl(message.content)"
                    class="text-sm whitespace-pre-wrap break-words"
                >
                    <a
                        :href="message.content"
                        target="_blank"
                        class="text-black underline"
                    >
                        {{ message.content }}
                    </a>
                </p>
                <p v-else class="text-sm whitespace-pre-wrap break-words">
                    {{ message.content }}
                </p>
            </div>

            <!-- Seen (pentru userul curent) -->
            <MessageSeenSVG
                v-if="message.sender_id === currentUser.id"
                :isSeen="message.is_read === 1"
                class="absolute bottom-1 right-2 w-4 h-4"
            />
        </div>
    </div>
</template>
<script>
import MessageSeenSVG from "@/Components/MessageSeenSVG.vue";
import AudioPlayer from "vue3-audio-player";
import "vue3-audio-player/dist/style.css";

export default {
    components: {
        MessageSeenSVG,
        AudioPlayer,
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
    emits: ["reply", "scrollMessage"],
    methods: {
        cleanAttachmentUrl(url) {
            return url.replace("http://127.0.0.1:8000/storage/", "");
        },
        isUrl(text) {
            return /^http?:\/\/\S+$/.test(text);
        },
    },
};
</script>
