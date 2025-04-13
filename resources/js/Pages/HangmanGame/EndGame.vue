<template>
    <div
        class="bg-gradient-to-br from-green-400 to-green-300 shadow-xl rounded-xl p-8 mt-12 text-center animate-fade-in"
    >
        <h2 class="text-3xl font-bold text-white-700 mb-6">🎮 Game Over</h2>

        <div v-if="scores" class="space-y-4">
            <div
                class="bg-white p-4 rounded-lg shadow flex flex-col items-center"
            >
                <p class="text-lg font-semibold text-gray-700">
                    👑 <strong>Creator:</strong> {{ scores.creator.name }}
                </p>
                <p class="text-purple-600 font-bold text-xl">
                    Score: {{ scores.creator.score }}
                </p>
            </div>

            <div
                class="bg-white p-4 rounded-lg shadow flex flex-col items-center"
            >
                <p class="text-lg font-semibold text-gray-700">
                    🕹️ <strong>Opponent:</strong> {{ scores.opponent.name }}
                </p>
                <p class="text-blue-600 font-bold text-xl">
                    Score: {{ scores.opponent.score }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        scores: Object,
    },
};
</script>

