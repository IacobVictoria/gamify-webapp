<template>
    <AuthenticatedLayout>
        <div class="game-setup">
            <div class="image-container">
                <img src="/images/hangman_startPic.png" alt="Hangman Game Cover" class="game-image" />
            </div>
            <h2 class="game-title">Welcome to Hangman</h2>
            <button @click="startNewGame" class="start-button">Start New Game</button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    methods: {
        startNewGame() {
            try {
                this.$inertia.post(route('user.generateGameSession'));
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

.image-container {
    margin-bottom: 20px;
    width: 60em;
    height: 30em;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.game-image {
    object-fit: cover; 
    box-shadow: 0px 4px 20px;
}

.game-title {
    font-family: 'Comic Sans MS', cursive, sans-serif;
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
    font-family: 'Comic Sans MS', cursive, sans-serif;
}

.start-button:hover {
    background-color: #5a9d31;
}
</style>
