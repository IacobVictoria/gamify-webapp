<template>
    <AuthenticatedLayout>
        <div class="game-setup">
            <div class="image-container w-full max-w-3xl mx-auto mb-6 px-4">
                <img
                    src="/images/hangman_startPic.png"
                    alt="Hangman Game Cover"
                    class="w-full rounded-xl shadow-lg object-cover"
                />
            </div>

            <h2 class="game-title">Bine ai venit la Spânzurătoarea</h2>
            <button @click="startNewGame" class="start-button">
                Începe jocul
            </button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        AuthenticatedLayout,
    },
    methods: {
        startNewGame() {
            try {
                this.$inertia.post(route("user.generateGameSession"));
            } catch (error) {
                console.error("Error starting new game:", error);
                alert("Failed to start the game.");
            }
        },
    },
};
</script>

<style scoped>
.game-setup {
    text-align: center;
    margin-top: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.game-title {
    font-family: "Comic Sans MS", cursive, sans-serif;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

.start-button {
    background-color: #7bc043;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: "Comic Sans MS", cursive, sans-serif;
}

.start-button:hover {
    background-color: #5a9d31;
}
</style>
