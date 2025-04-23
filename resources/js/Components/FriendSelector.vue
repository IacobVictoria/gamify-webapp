<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center">
        <!-- Fundal blurat -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm z-[-1]"></div>

        <!-- Popup centrat -->
        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md z-10">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">
                Select a friend to share with 👥
            </h2>
            <ul class="space-y-2 max-h-64 overflow-y-auto">
                <li
                    v-for="friend in friends"
                    :key="friend.id"
                    class="flex justify-between items-center border-b pb-2"
                >
                    <span>{{ friend.name }}</span>
                    <button
                        @click="$emit('send', friend.id)"
                        class="text-sm text-white bg-blue-500 px-3 py-1 rounded hover:bg-blue-600 transition"
                    >
                        Send
                    </button>
                </li>
            </ul>
            <button
                class="mt-4 w-full bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300 transition"
                @click="$emit('close')"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        friends: {
            type: Array,
            required: true,
        },
    },
    emits: ["send", "close"],
};
</script>
