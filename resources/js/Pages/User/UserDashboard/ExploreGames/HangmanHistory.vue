<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-indigo-800 mb-6">
                🕹️ Istoricul jocurilor Spânzurătoarea
            </h2>
        </template>

        <div class="py-10 md:px-4 lg:px-32">
            <div
                class="bg-white shadow-lg rounded-xl p-6 border border-gray-200"
            >
                <h3
                    class="text-xl font-semibold text-center text-gray-700 mb-6"
                >
                    📚 Bătălii trecute și progres
                </h3>

                <div
                    v-if="userResults.length === 0"
                    class="text-center text-gray-500"
                >
                    Nu ai jucat încă Spânzurătoarea. Începe primul tău joc chiar
                    azi! 🧠
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="result in userResults"
                        :key="result.id"
                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-5 shadow transition cursor-pointer"
                        @click="openModal(result)"
                    >
                        <h4
                            class="text-lg font-bold text-indigo-700 text-center mb-2"
                        >
                            {{ result.creator_name }} vs
                            {{ result.opponent_name }} 🆚
                        </h4>

                        <p class="text-sm text-gray-600 text-center mb-1">
                            Cuvântul tău:
                            <span class="font-medium text-gray-900">{{
                                result.is_creator
                                    ? result.word_for_creator
                                    : result.word_for_opponent
                            }}</span>
                        </p>
                        <p class="text-sm text-gray-600 text-center mb-1">
                            Cuvântul adversarului:
                            <span class="font-medium text-gray-900">{{
                                result.is_creator
                                    ? result.word_for_opponent
                                    : result.word_for_creator
                            }}</span>
                        </p>

                        <p class="text-sm text-center">
                            🎯 Scorul tău:
                            <span
                                :class="
                                    getScoreColor(
                                        result.is_creator
                                            ? result.scores.creator.score
                                            : result.scores.opponent.score
                                    )
                                "
                            >
                                {{
                                    result.is_creator
                                        ? result.scores.creator.score
                                        : result.scores.opponent.score
                                }}
                            </span>
                        </p>
                        <p class="text-sm text-center">
                            🧠 Scorul adversarului:
                            <span
                                :class="
                                    getScoreColor(
                                        result.is_creator
                                            ? result.scores.opponent.score
                                            : result.scores.creator.score
                                    )
                                "
                            >
                                {{
                                    result.is_creator
                                        ? result.scores.opponent.score
                                        : result.scores.creator.score
                                }}
                            </span>
                        </p>

                        <p class="text-xs text-gray-500 text-center mt-2">
                            📅 {{ formatDate(result.updated_at) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL PENTRU DETALII -->
        <div
            v-if="selectedGame"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white w-[90%] sm:w-[500px] p-6 rounded-lg relative shadow-lg"
            >
                <button
                    class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl"
                    @click="closeModal"
                >
                    ×
                </button>
                <h3
                    class="text-xl font-semibold text-center text-indigo-800 mb-4"
                >
                    📝 Cuvinte și indicii
                </h3>

                <p class="text-sm text-gray-700 mb-2">
                    🧩 Cuvântul tău:
                    <span class="font-bold">{{
                        selectedGame.is_creator
                            ? selectedGame.word_for_creator
                            : selectedGame.word_for_opponent
                    }}</span>
                </p>
                <p class="text-sm text-gray-700 mb-2">
                    🧩 Cuvântul adversarului:
                    <span class="font-bold">{{
                        selectedGame.is_creator
                            ? selectedGame.word_for_opponent
                            : selectedGame.word_for_creator
                    }}</span>
                </p>

                <p class="text-sm text-gray-500 mt-4">
                    💡 Indiciul tău:
                    {{
                        selectedGame.is_creator
                            ? selectedGame.hint_for_creator
                            : selectedGame.hint_for_opponent
                    }}
                </p>
                <p class="text-sm text-gray-500">
                    💡 Indiciul adversarului:
                    {{
                        selectedGame.is_creator
                            ? selectedGame.hint_for_opponent
                            : selectedGame.hint_for_creator
                    }}
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        userResults: Array,
    },
    data() {
        return {
            selectedGame: null,
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
            if (score >= 75) return "text-green-600 font-bold";
            if (score >= 50) return "text-yellow-500 font-bold";
            return "text-red-500 font-bold";
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
    },
};
</script>
