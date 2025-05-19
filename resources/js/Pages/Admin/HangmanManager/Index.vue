<template>
    <AuthenticatedLayout>
        <div class="px-24 py-6">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="px-6 py-12">
                    <h1
                        class="text-3xl font-bold text-center mb-10 text-gray-700"
                    >
                        🎮 Manager Cuvinte pentru Jocul Spânzurătoarea
                    </h1>
                    <div class="flex gap-6 p-6">
                        <!-- Left Panel - Word List -->
                        <div
                            class="w-1/2 bg-white p-4 rounded-lg shadow-lg max-h-[600px] overflow-y-auto"
                        >
                            <h2 class="text-xl font-semibold mb-4">
                                Lista de Cuvinte
                            </h2>
                            <ul>
                                <li
                                    v-for="wordObj in words"
                                    :key="wordObj.word"
                                    class="border-b py-2"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span class="font-bold">{{
                                            wordObj.word
                                        }}</span>
                                        <button
                                            @click="toggleHint(wordObj.word)"
                                            class="text-blue-500"
                                        >
                                            {{
                                                expandedWord === wordObj.word
                                                    ? "▲"
                                                    : "▼"
                                            }}
                                        </button>
                                    </div>
                                    <div
                                        v-if="expandedWord === wordObj.word"
                                        class="mt-2 text-gray-600"
                                    >
                                        <p>{{ wordObj.hint }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <button
                                                @click="startEdit(wordObj)"
                                                class="text-yellow-500"
                                            >
                                                Editeză
                                            </button>
                                            <button
                                                @click="
                                                    deleteWord(wordObj.word)
                                                "
                                                class="text-red-500"
                                            >
                                                Șterge
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- ADD / EDIT FORM -->
                        <div
                            class="w-1/2 bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl shadow-2xl flex items-center justify-center"
                        >
                            <div
                                class="max-w-md w-full bg-white p-6 rounded-xl shadow-lg"
                            >
                                <h2
                                    class="text-2xl font-bold text-gray-700 mb-6 text-center flex items-center justify-center gap-2"
                                >
                                    <span>✏️</span>
                                    {{
                                        editMode
                                            ? "Editează Cuvântul"
                                            : "Adaugă un Cuvânt Nou"
                                    }}
                                </h2>

                                <!-- Input pentru cuvânt -->
                                <div class="relative mb-4">
                                    <input
                                        v-model="wordInput"
                                        class="w-full p-3 pl-10 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition-all"
                                        placeholder="✍️ Cuvânt"
                                    />
                                </div>

                                <!-- Input pentru hint -->
                                <div class="relative mb-6">
                                    <input
                                        v-model="hintInput"
                                        class="w-full p-3 pl-10 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition-all"
                                        placeholder="💡 Hint"
                                    />
                                </div>

                                <!-- Butoane -->
                                <div class="flex justify-center gap-4">
                                    <button
                                        v-if="editMode"
                                        @click="saveEdit"
                                        class="flex items-center gap-2 bg-yellow-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-yellow-600 transition duration-300"
                                    >
                                        ✏️ Salvează
                                    </button>

                                    <button
                                        v-if="editMode"
                                        @click="cancelEdit"
                                        class="flex items-center gap-2 bg-gray-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-300"
                                    >
                                        ❌ Anulează
                                    </button>

                                    <button
                                        v-if="!editMode"
                                        @click="addWord"
                                        class="flex items-center gap-2 bg-green-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition duration-300"
                                    >
                                        ✅ Adaugă
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            words: this.$page.props.words || [],
            expandedWord: null,
            newWord: "",
            newHint: "",
            editMode: false,
            editWord: null,
        };
    },
    computed: {
        wordInput: {
            get() {
                return this.editMode && this.editWord
                    ? this.editWord.word
                    : this.newWord;
            },
            set(val) {
                if (this.editMode && this.editWord) {
                    this.editWord.word = val;
                } else {
                    this.newWord = val;
                }
            },
        },
        hintInput: {
            get() {
                return this.editMode && this.editWord
                    ? this.editWord.hint
                    : this.newHint;
            },
            set(val) {
                if (this.editMode && this.editWord) {
                    this.editWord.hint = val;
                } else {
                    this.newHint = val;
                }
            },
        },
    },
    methods: {
        toggleHint(word) {
            this.expandedWord = this.expandedWord === word ? null : word;
        },
        addWord() {
            if (!this.newWord || !this.newHint) return;
            this.$inertia.post(
                this.route("admin-gamification.hangman_manager.store"),
                { word: this.newWord, hint: this.newHint },
                {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.words = page.props.words;
                        this.newWord = "";
                        this.newHint = "";
                    },
                }
            );
        },
        deleteWord(word) {
            if (!confirm(`Ești sigur că vrei să ștergi ${word}?`)) return;
            this.$inertia.delete(
                this.route("admin-gamification.hangman_manager.destroy", word),
                {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.words = page.props.words;
                    },
                }
            );
        },
        saveEdit() {
            if (!this.editWord.word || !this.editWord.hint) return;

            this.$inertia.put(
                this.route(
                    "admin-gamification.hangman_manager.update",
                    this.editWord.oldWord
                ),
                {
                    new_word: this.editWord.word,
                    hint: this.editWord.hint,
                },
                {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        this.words = page.props.words;
                        this.editMode = false;
                        this.editWord = null;
                    },
                }
            );
        },
        startEdit(wordObj) {
            this.editMode = true;
            this.editWord = { ...wordObj, oldWord: wordObj.word };
        },
        cancelEdit() {
            this.editMode = false;
            this.editWord = null;
        },
    },
};
</script>
