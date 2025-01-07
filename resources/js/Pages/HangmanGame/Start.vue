<template>
    <AuthenticatedLayout>
        <div v-if="!gameEnd" class="game-session">
            <h2>Hangman Game Session</h2>
            <div v-if="bothConnected" class="game-info">
                <p><strong>Creator:</strong> {{ creatorName }}</p>
                <p><strong>Opponent:</strong> {{ opponentName }}</p>
                <p>
                    <strong>Current Turn:</strong>
                    <span v-if="$page.props.user.id === turnData">Your turn</span>
                    <span v-else>Opponent's turn</span>
                </p>
                <button v-if="$page.props.user.id === creatorId" @click="startGame" class="btn btn-primary mt-3">
                    Start Game
                </button>
            </div>
            <div v-else class="waiting-for-opponent">
                <h3>Waiting for Opponent...</h3>
                <p>Session ID: {{ sessionId }}</p>
            </div>
            <div v-if="gameStart">
                <GameBoard :isMyTurn="$page.props.user.id === turnData" :hint="currentHint" :word="currentWord"
                    :usedLetters="usedLetters" :correctLetters="correctLetters" :wrongLetters="wrongLetters"
                    :errors="errors" @guess="handleGuess" />
            </div>
            <div v-if="!bothConnected" class="friend-search mt-5">
                <h3>Invite a Friend</h3>
                <input type="text" v-model="search" @input="searchFriends" placeholder="Search friends by email..."
                    class="form-control" />
                <ul v-if="friends.length > 0" class="friend-list mt-3 list-group">
                    <li v-for="friend in friends" :key="friend.id"
                        class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ friend.name }} ({{ friend.email }})</span>
                        <button @click="sendGameInvite(friend.id)" class="btn btn-sm btn-success">
                            Invite
                        </button>
                    </li>
                </ul>
                <p v-else class="text-muted mt-3">No friends found.</p>
            </div>
        </div>
        <div v-if="gameEnd">
            <EndGame :scores="scores" />
        </div>

    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import debounce from "lodash/debounce";
import Swal from "sweetalert2";
import GameBoard from "./GameBoard.vue";
import EndGame from "./EndGame.vue";

export default {
    components: {
        AuthenticatedLayout,
        GameBoard,
        EndGame
    },
    props: {
        sessionId: String,
        creatorId: Number,
        turn: Number,
    },
    data() {
        return {
            turnData: this.$props.turn,
            search: "",
            friends: [],
            gameStart: false,
            gameEnd: false,
            scores: null,
            gameUrl: window.location.href,
            creatorName: null,
            opponentName: null,
            bothConnected: false,
            creatorWord: "",
            creatorHint: "",
            opponentWord: "",
            opponentHint: "",
            currentWord: "",
            currentHint: "",
            usedLetters: [],
            correctLetters: [],
            wrongLetters: [],
            errors: 0,
        };
    },
    methods: {
        searchFriends: debounce(async function () {
            if (!this.search.trim()) {
                this.friends = [];
                return;
            }

            try {
                const response = await axios.get(`/user/hangmanGame/search_friends`, {
                    params: { email: this.search.trim() },
                });
                this.friends = response.data;
            } catch (error) {
                console.error("Error searching friends:", error);
                alert("Failed to search friends.");
            }
        }, 300),

        async sendGameInvite(friendId) {
            if (this.gameUrl.trim()) {
                const payload = {
                    message: this.gameUrl,
                };

                await axios.post(`/user/user_chat/messages/${friendId}`, payload);
            }
        },

        async joinSession() {
            try {
                const response = await axios.post(`/user/hangmanGame/${this.sessionId}/join`);
                this.creatorName = response.data.creator_name;
                this.opponentName = response.data.opponent_name;
                this.bothConnected = response.data.opponent_connected;
            } catch (error) {
                console.error("Error joining session:", error);
            }
        },
        async startGame() {
            try {
                await axios.post(`/user/hangmanGame/${this.sessionId}/start`);
                alert("Game has started!");
            } catch (error) {
                console.error("Error starting game:", error);
                alert("Failed to start the game.");
            }
        },
        showEnterWordPopup() {
            Swal.fire({
                title: "Enter your word and hint",
                html: `
                <input id="swal-input-word" class="swal2-input" placeholder="Enter a word">
                <input id="swal-input-hint" class="swal2-input" placeholder="Enter a hint">
            `,
                showCancelButton: false,
                confirmButtonText: "Submit",
                preConfirm: () => {
                    const word = document.getElementById("swal-input-word").value;
                    const hint = document.getElementById("swal-input-hint").value;

                    if (!word || !hint) {
                        Swal.showValidationMessage("Please enter both a word and a hint!");
                    }
                    return { word, hint };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submitWord(result.value.word, result.value.hint);
                }
            });
        },
        async submitWord(word, hint) {
            try {
                await axios.post(`/user/hangmanGame/${this.sessionId}/submitWord`, {
                    word,
                    hint,
                });
                alert("Word and hint submitted successfully!");
            } catch (error) {
                console.error("Error submitting word and hint:", error);
                alert("Failed to submit the word and hint.");
            }
        },
        async handleGuess(letter) {
            try {
                const response = await axios.post(`/user/hangmanGame/${this.sessionId}/guess`, { letter });

                const { correct, finished, nextTurn } = response.data;

                if (correct) {
                    this.correctLetters.push(letter); // Adaugă litera corectă
                } else {
                    this.wrongLetters.push(letter); // Adaugă litera greșită
                }

                this.errors = response.data.errors; // Actualizează numărul de greșeli
                this.usedLetters.push(letter); // Adaugă litera la utilizate

                if (finished) {
                    this.turnData = nextTurn;
                    this.errors = 0;
                    this.correctLetters = [];
                    this.wrongLetters = [];
                    this.usedLetters = [];
                }
            } catch (error) {
                console.error("Error handling guess:", error);
            }
        },
        updateCurrentWordAndHint() {
            if (this.turnData === this.creatorId) {
                this.currentWord = this.creatorWord;
                this.currentHint = this.creatorHint;
            } else {
                this.currentWord = this.opponentWord;
                this.currentHint = this.opponentHint;
            }
        },
    },
    mounted() {
        this.joinSession();
    },
    beforeMount() {
        window.Echo.private(`hangman-session.${this.sessionId}`)
            .listen(".OpponentJoined", (event) => {
                this.opponentName = event.opponentName;
                this.bothConnected = true;
            });
        window.Echo.private(`hangman-session.${this.sessionId}`)
            .listen(".GameStarted", () => {
                this.showEnterWordPopup();
            });
        window.Echo.private(`hangman-session.${this.sessionId}`)
            .listen(".GameReady", (event) => {
                this.creatorWord = event.wordForCreator;
                this.creatorHint = event.hintForCreator;
                this.opponentWord = event.wordForOpponent;
                this.opponentHint = event.hintForOpponent;

                this.correctLetters = [];
                this.wrongLetters = [];
                this.usedLetters = [];
                this.errors = 0;

                this.updateCurrentWordAndHint();

                this.gameStart = true;
            });

        window.Echo.private(`hangman-session.${this.sessionId}`).listen(".GameUpdated", (event) => {
            this.turnData = event.turn;
            this.correctLetters = event.correctLetters || [];
            this.wrongLetters = event.wrongLetters || [];
            this.usedLetters = event.usedLetters || [];
            this.errors = this.turnData === this.creatorId ? event.creatorErrors : event.opponentErrors;


            if (event.correctLetters.length === 0 && event.wrongLetters.length === 0) {
                this.correctLetters = [];
                this.wrongLetters = [];
                this.usedLetters = [];
                this.errors = 0;
            }
            this.updateCurrentWordAndHint();
        });
        window.Echo.private(`hangman-session.${this.sessionId}`)
            .listen(".GameEnded", (event) => {
                alert(event.message);
                this.gameStart = false;
                this.gameEnd = true;
                this.scores = event.scores;
            });
    }
};
</script>
