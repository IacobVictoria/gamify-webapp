<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                🎭 Your Hangman History ✨
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-gradient-to-r from-green-300 to-teal-300 text-black shadow-2xl sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-3 text-center">🔍 Review Your Past Games</h3>
                    <div class="flex flex-row items-center justify-end learn-message mb-12">
                        <div class="text-lg font-bold text-black pr-4">
                            Learn new words - Click on a result!
                        </div>
                        <img :src="imagePath('/user_dashboard/search.png')" class="w-32 search-icon" />
                    </div>

                    <div v-if="userResults.length === 0" class="text-center text-gray-100 text-lg">
                        No hangman history found. 🏆 Try a new game!
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div v-for="result in userResults" :key="result.id" 
                            class="hangman-card" 
                            @click="openModal(result)">
                            <h4 class="hangman-title">🆚 {{ result.creator_name }} vs {{ result.opponent_name }}</h4>

                            <p class="hangman-word">
                                Your Word: <span class="font-semibold">{{ result.is_creator ? result.word_for_creator : result.word_for_opponent }}</span>
                            </p>

                            <p class="hangman-word">
                                Opponent’s Word: <span class="font-semibold">{{ result.is_creator ? result.word_for_opponent : result.word_for_creator }}</span>
                            </p>

                            <p class="hangman-score">
                                Your Mistakes: <span class="text-red-400">{{ result.is_creator ? result.mistakes_creator : result.mistakes_opponent }}</span>
                            </p>

                            <p class="hangman-score">
                                Opponent’s Mistakes: <span class="text-red-400">{{ result.is_creator ? result.mistakes_opponent : result.mistakes_creator }}</span>
                            </p>

                            <p class="hangman-score">
                                Your Score: <span :class="getScoreColor(result.is_creator ? result.scores.creator.score : result.scores.opponent.score)">
                                    {{ result.is_creator ? result.scores.creator.score : result.scores.opponent.score }} 🎯
                                </span>
                            </p>

                            <p class="hangman-score">
                                Opponent's Score: <span :class="getScoreColor(result.is_creator ? result.scores.opponent.score : result.scores.creator.score)">
                                    {{ result.is_creator ? result.scores.opponent.score : result.scores.creator.score }} 🎯
                                </span>
                            </p>

                            <p class="hangman-date">📅 {{ formatDate(result.updated_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ MODAL PENTRU DETALII -->
        <div v-if="selectedGame" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <button class="close-button" @click="closeModal">✖</button>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">📖 Word & Hints</h3>

                <div class="game-details">
                    <p class="text-lg font-semibold">Your Word: <span class="text-green-700">{{ selectedGame.is_creator ? selectedGame.word_for_creator : selectedGame.word_for_opponent }}</span></p>
                    <p class="text-lg font-semibold">Opponent's Word: <span class="text-red-600">{{ selectedGame.is_creator ? selectedGame.word_for_opponent : selectedGame.word_for_creator }}</span></p>

                    <p class="text-sm text-gray-600 mt-4">💡 Your Hint: {{ selectedGame.is_creator ? selectedGame.hint_for_creator : selectedGame.hint_for_opponent }}</p>
                    <p class="text-sm text-gray-600">💡 Opponent's Hint: {{ selectedGame.is_creator ? selectedGame.hint_for_opponent : selectedGame.hint_for_creator }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        userResults: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            selectedGame: null
        };
    },
    methods: {
        openModal(game) {
            this.selectedGame = game;
        },
        closeModal() {
            this.selectedGame = null;
        },
        getScoreColor(score) {
            if (score >= 75) return 'text-green-500 font-bold';
            if (score >= 50) return 'text-yellow-300 font-bold';
            return 'text-red-300 font-bold';
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        }
    }
};
</script>

<style scoped>
/* 🎨 Card Design */
.hangman-card {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    text-decoration: none;
    color: black;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    cursor: pointer;
}

/* Hover Effect */
.hangman-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(12px);
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
    z-index: 1000;
}

.modal-content {
    background: black;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s ease-in-out;
}

.close-button {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
}

.game-details {
    margin-top: 10px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
</style>
