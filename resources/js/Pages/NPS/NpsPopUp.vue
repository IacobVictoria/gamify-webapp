<template>
    <div
        v-if="isVisible"
        class="fixed bottom-4 right-4 z-50 p-4 bg-white shadow-lg rounded-lg flex items-center space-x-4"
    >
        <div>
            <p class="text-lg font-semibold">Oferă-ne feedback!</p>
            <p class="text-sm text-gray-500">
                Ne-ar plăcea să aflăm părerea ta.
            </p>
        </div>
        <div>
            <button
                @click="handleGiveFeedback"
                class="bg-blue-500 text-white px-3 py-2 rounded-lg shadow hover:bg-blue-600"
            >
                Trimite feedback
            </button>
        </div>
        <button
            @click="closePopup"
            class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
        >
            ✕
        </button>
    </div>
</template>

<script>
export default {
    name: "NpsPopup",
    data() {
        return {
            isVisible: true,
        };
    },
    methods: {
        handleGiveFeedback() {
            this.navigateTo("/nps/form"); 
            this.isVisible = false; 
        },
        navigateTo(route) {
            window.location.href = route;
        },
        closePopup() {
            this.isVisible = false;
        },
    },
};
</script>