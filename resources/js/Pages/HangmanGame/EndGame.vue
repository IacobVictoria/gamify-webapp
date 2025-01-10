<template>
        <div class="end-game">
            <h2>Game Over</h2>
            <div v-if="scores">
                <p><strong>Creator:</strong> {{ scores.creator.name }} - Score: {{ scores.creator.score }}</p>
                <p><strong>Opponent:</strong> {{ scores.opponent.name }} - Score: {{ scores.opponent.score }}</p>
            </div>
        </div>   
</template>

<script>
export default {
    props: {
        scores: Object
    },  
};
</script>
<style scoped>
.end-game {
    text-align: center;
    margin-top: 20px;
}
</style>
