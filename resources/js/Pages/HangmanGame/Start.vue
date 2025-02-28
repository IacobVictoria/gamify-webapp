<template>
    <AuthenticatedLayout>
        <div class="game-session-container">
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
                    <button v-if="$page.props.user.id === creatorId && !gameStart" @click="startGame"
                        class="btn btn-primary mt-3">
                        Start Game
                    </button>
                </div>
                <div v-else class="waiting-for-opponent">
                    <h3>Waiting for Opponent...</h3>
                </div>
                <!-- Alegerea cuvântului -->
                <div v-if="!wordSelected && gameStart" class="word-selection">
                    <h3>Select a Word for Your Opponent</h3>
                    <div class="word-options">
                        <button v-for="(option, index) in wordOptions" :key="index" @click="submitWord(option)"
                            class="word-button">
                            {{ option.word }}
                        </button>
                    </div>
                </div>
                <div v-if="gameStart && wordSelected">
                    <HangmanDrawing :mistakes="errors" :maxMistakes="Math.ceil(currentWord.length / 2)" />
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
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import debounce from "lodash/debounce";
import Swal from "sweetalert2";
import GameBoard from "./GameBoard.vue";
import EndGame from "./EndGame.vue";
import HangmanDrawing from "./HangmanDrawing.vue";

export default {
    components: {
        AuthenticatedLayout,
        GameBoard,
        EndGame,
        HangmanDrawing
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
            wordOptions: [], // Cele 3 opțiuni de cuvinte
            wordSelected: false
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

                await axios.post(`/user/user_chat/messages/${friendId}`, payload)
                    .then(() => {
                        this.friends = [];
                        this.search = "";
                        alert('Invitatie trimisa cu succes!');
                    })
                    .catch((error) => {
                        console.error('Error sending invitation:', error);
                        alert('A apărut o eroare la trimiterea invitației.');
                    });


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
        async loadWordOptions() {
            try {
                const response = await axios.get(`/user/hangmanGame/word-options`);

                // Verifică dacă userul curent este creatorul
                if (this.$page.props.user.id === this.creatorId) {
                    this.wordOptions = response.data.creatorWords;
                } else {
                    this.wordOptions = response.data.opponentWords;
                }
            } catch (error) {
                console.error("Error loading words:", error);
            }
        }
        ,

        async submitWord(word) {
            try {
                await axios.post(`/user/hangmanGame/${this.sessionId}/submitWord`, {
                    word,
                });
                this.wordSelected = true;
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
        showEnterWordPopup() {
            Swal.fire({
                title: "Choose a Word",
                html: `
                <p>Select a word for your opponent:</p>
                <div id="swal-word-options"></div>
            `,
                showCancelButton: false,
                confirmButtonText: "OK",
                confirmButtonColor: "#2e7d32",
                didOpen: () => {
                    const wordOptionsContainer = document.getElementById("swal-word-options");
                    this.wordOptions.forEach((word, index) => {
                        const button = document.createElement("button");
                        button.innerText = word.word;
                        button.className = "swal2-confirm swal2-styled";
                        button.style.margin = "5px";
                        button.onclick = () => {
                            this.submitWord(word.word);
                            Swal.close();
                        };
                        wordOptionsContainer.appendChild(button);
                    });
                },
            });
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
            .listen(".GameStarted", async () => {
                await this.loadWordOptions();
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
                this.gameStart = false;
                this.gameEnd = true;
                this.scores = event.scores;
            });
    }
};
</script>
<style scoped>
.game-session-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f5fff5;
    padding: 20px;
    color: #2c3e50;
    font-family: 'Arial', sans-serif;
}

.game-session {
    width: 80%;
    max-width: 900px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    padding: 40px;
    text-align: center;
    border: 1px solid #dfe6ee;
}

.waiting-for-opponent h3 {
    color: #4caf50;
    font-size: 24px;
    margin-bottom: 10px;
}

.waiting-for-opponent p {
    font-size: 16px;
    color: #6b8e23;
}

.friend-search {
    margin-top: 40px;
}

.friend-search h3 {
    font-size: 20px;
    color: #2e7d32;
    margin-bottom: 10px;
}

.friend-search input {
    max-width: 400px;
    margin: 0 auto;
    border-radius: 8px;
    border: 1px solid #a5d6a7;
    padding: 12px;
    font-size: 16px;
    color: #2c3e50;
}

.friend-list {
    max-width: 400px;
    margin: 0 auto;
}

.friend-list .list-group-item {
    font-size: 14px;
    padding: 10px;
    background: #e8f5e9;
    color: #2c3e50;
    border: 1px solid #c8e6c9;
    border-radius: 8px;
}

.friend-list .list-group-item:hover {
    background: #d0f0d0;
}

.btn-primary {
    background-color: #66bb6a;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 8px;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #43a047;
}

.btn-success {
    background-color: #81c784;
    color: #ffffff;
    border: none;
    padding: 8px 20px;
    font-size: 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background-color: #388e3c;
}

.game-session h2 {
    color: #2e7d32;
    margin-bottom: 20px;
    font-size: 28px;
}
</style>
