<template>
    <AuthenticatedLayout>
        <div class="game-setup">
            <h2>Welcome to Hangman</h2>
            <button @click="startNewGame" class="btn btn-primary">Start New Game</button>
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
