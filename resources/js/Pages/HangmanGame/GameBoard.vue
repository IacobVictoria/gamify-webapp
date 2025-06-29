<template>
    <div class="game-board">
        <h3>{{ isMyTurn ? "Rândul tău" : "Rândul oponentului" }}</h3>
        <p><strong>Sugestie:</strong> {{ hint }}</p>


        <div class="word-container">
            <span v-for="(char, index) in displayedWord" :key="index" class="word-char">
                {{ char }}
            </span>
        </div>
        <div class="keyboard">
            <button v-for="letter in alphabet" :key="letter" :disabled="!isMyTurn || usedLetters.includes(letter)"
                class="key" :class="{
                    correct: displayCorrectLetters.includes(letter),
                    wrong: displayedWrongLetters.includes(letter),
                }" @click="guessLetter(letter)">
                {{ letter }}
            </button>
        </div>

        <p>Erori: {{ errors }}/{{ Math.ceil(word.length / 2) }}</p>
    </div>
</template>

<script>
export default {
    props: {
        isMyTurn: Boolean, // dacă e rândul utilizatorului curent
        hint: String,
        word: String, 
        usedLetters: Array,
        correctLetters: Array, 
        wrongLetters: Array, 
        errors: Number, // Numărul de greșeli
    },
    computed: {
        displayedWord() {
            return this.word.split("").map((char) =>
                this.correctLetters.includes(char.toUpperCase()) ? char : "_"
            );
        },
        alphabet() {
            return "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
        },
        displayedWrongLetters() {
            return this.wrongLetters;
        },
        displayCorrectLetters() {
            return this.correctLetters;
        }

    },
    methods: {
        guessLetter(letter) {
            if (!this.usedLetters.includes(letter)) {
                this.usedLetters.push(letter);
                this.$emit("guess", letter); // Emite litera ghicită către componenta părinte
            }
        },
    },
};
</script>
<style scoped>
.word-container {
    display: flex;
    gap: 10px;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.word-char {
    border-bottom: 2px solid black;
    width: 20px;
    text-align: center;
    color: black;
}

.keyboard {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.key {
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    color: black;
    background: url('http://localhost:8000/images/cloud.png');
    background-size: cover;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.key:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

.key.correct {
    color: #7bc043;
    ;
}

.key.wrong {
    color: #ff6f61;
}

.key.disabled {
    cursor: not-allowed;
}
</style>