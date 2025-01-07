<template>
    <div class="game-board">
        <h3>{{ isMyTurn ? "Your Turn" : "Opponent's Turn" }}</h3>
        <p><strong>Hint:</strong> {{ hint }}</p>

        <div class="word-container">
            <span v-for="(char, index) in displayedWord" :key="index" class="word-char">
                {{ char }}
            </span>
        </div>
        <div class="keyboard">
            <button v-for="letter in alphabet" :key="letter" :disabled="!isMyTurn || usedLetters.includes(letter)"
                :class="{
                    correct: correctLetters.includes(letter),
                    wrong: displayedWrongLetters.includes(letter),
                }" @click="guessLetter(letter)">
                {{ letter }}
            </button>
        </div>

        <p>Errors: {{ errors }}/{{ Math.ceil(word.length / 2) }}</p>
    </div>
</template>

<script>
export default {
    props: {
        isMyTurn: Boolean, // Determină dacă e rândul utilizatorului curent
        hint: String, // Hint-ul pentru cuvântul de ghicit
        word: String, // Cuvântul complet
        usedLetters: Array, // Literele deja utilizate
        correctLetters: Array, // Literele corecte
        wrongLetters: Array, // Literele greșite
        errors: Number, // Numărul de greșeli
    },
    computed: {
        displayedWord() {
            return this.word.split("").map((char) => (this.correctLetters.includes(char) ? char : "_"));
        },
        alphabet() {
            return "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
        },
        displayedWrongLetters() {
            return this.wrongLetters;
        },

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
}

.word-char {
    border-bottom: 2px solid black;
    width: 20px;
    text-align: center;
}

.keyboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(30px, 1fr));
    gap: 10px;
}

.keyboard button {
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
}

.keyboard button.correct {
    background-color: green;
    color: white;
}

.keyboard button.wrong {
    background-color: red;
    color: white;
}
</style>