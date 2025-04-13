<template>
    <AuthenticatedLayout>
        <div class="m-12 pl-32 pr-32 grid grid-cols-3 gap-6">
            <!-- Titlu sus -->
            <div class="col-span-3 text-center mb-4">
                <h1
                    class="text-2xl font-bold text-blue-700 flex items-center justify-center"
                >
                    ✍️ Let's Create an Awesome Quiz!
                </h1>
            </div>
            <!-- Tips & Tricks (Coloana dreaptă) -->
            <div
                class="bg-yellow-50 p-6 rounded-md shadow-md border border-yellow-200 flex flex-col justify-center"
            >
                <h2
                    class="font-bold text-yellow-700 flex items-center text-lg mb-4"
                >
                    💡 Quick Tips for a Great Quiz!
                </h2>
                <ul
                    class="list-disc list-inside text-base text-gray-800 space-y-3"
                >
                    <li>
                        🧐
                        <span class="font-semibold"
                            >Make sure your questions are clear and
                            concise.</span
                        >
                    </li>
                    <li>
                        🔢
                        <span class="font-semibold"
                            >Provide at least four answer choices.</span
                        >
                    </li>
                    <li>
                        🎯
                        <span class="font-semibold"
                            >Balance between easy and difficult questions.</span
                        >
                    </li>
                    <li>
                        💡
                        <span class="font-semibold"
                            >Add some fun & creative questions.</span
                        >
                    </li>
                    <li>
                        🏆
                        <span class="font-semibold"
                            >Assign logical points for each question.</span
                        >
                    </li>
                </ul>
            </div>

            <!-- Formularul de creare a quiz-ului (Coloana stângă) -->
            <div
                class="col-span-2 p-12 bg-white shadow-md rounded-lg border border-gray-200"
            >
                <h2 class="text-lg font-semibold mb-4">📋 Quiz Details</h2>

                <div class="mb-3">
                    <label class="block text-gray-600 font-medium text-sm mb-1"
                        >Quiz Title</label
                    >
                    <input
                        v-model="quizTitle"
                        placeholder="Give your quiz an engaging title"
                        class="w-full p-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                    />
                    <p v-if="errors.title" class="text-red-500 text-xs mt-1">
                        ⚠️ {{ errors.title }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="block text-gray-600 font-medium text-sm mb-1"
                        >Quiz Description</label
                    >
                    <textarea
                        v-model="quizDescription"
                        placeholder="Describe what this quiz is about..."
                        class="w-full p-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                    ></textarea>
                    <p
                        v-if="errors.description"
                        class="text-red-500 text-xs mt-1"
                    >
                        ⚠️ {{ errors.description }}
                    </p>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-600 font-medium text-sm mb-1"
                        >Difficulty</label
                    >
                    <select
                        v-model="quizDifficulty"
                        class="w-full p-2 text-sm border rounded focus:ring-2 focus:ring-blue-400"
                    >
                        <option
                            v-for="difficulty in difficulties"
                            :key="difficulty"
                            :value="difficulty"
                        >
                            {{
                                difficulty.charAt(0).toUpperCase() +
                                difficulty.slice(1)
                            }}
                        </option>
                    </select>
                    <p
                        v-if="errors.difficulty"
                        class="text-red-500 text-xs mt-1"
                    >
                        ⚠️ {{ errors.difficulty }}
                    </p>
                </div>
                <div class="mb-3">
                    <label
                        class="flex items-center gap-3 text-gray-600 font-medium text-sm mb-1"
                    >
                        <input
                            type="checkbox"
                            v-model="isPublished"
                            class="form-checkbox h-5 w-5 text-green-500"
                        />
                        <span>✅ Mark quiz as published</span>
                    </label>
                </div>

                <button
                    @click="openQuestionModal"
                    class="w-[16em] bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 text-sm rounded flex items-center justify-center transition"
                >
                    ➕ Add New Question
                </button>

                <!-- Lista întrebărilor -->
                <div class="mt-6 max-h-[19vh] overflow-y-auto pr-2">
                    <h5 class="text-md font-semibold mb-2">
                        📝 Your Questions
                    </h5>
                    <div
                        v-for="(question, index) in questions"
                        :key="index"
                        class="p-3 border border-gray-200 bg-gray-50 rounded-md mb-2 text-sm"
                    >
                        <h5 class="font-semibold text-gray-800">
                            {{ question.text }}
                            <span class="text-gray-500"
                                >(🎯 Score: {{ question.score }})</span
                            >
                        </h5>
                        <ul class="ml-4 text-gray-600">
                            <li
                                v-for="(answer, ansIndex) in question.answers"
                                :key="ansIndex"
                            >
                                ✅ {{ ansIndex + 1 }}. {{ answer.text }}
                                <span
                                    v-if="answer.isCorrect"
                                    class="text-green-500 font-bold"
                                    >(Correct)</span
                                >
                            </li>
                        </ul>
                    </div>
                    <p
                        v-if="errors.questions"
                        class="text-red-500 text-xs mt-1"
                    >
                        ⚠️ {{ errors.questions }}
                    </p>
                </div>

                <!-- Final Save Button -->
                <div class="mt-6 flex justify-center">
                    <button
                        @click="postQuiz"
                        class="w-[16em] bg-green-500 hover:bg-green-600 text-white px-3 py-2 text-sm rounded transition"
                    >
                        🚀 Post Quiz
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal pentru întrebări -->
        <div
            v-if="isQuestionModalOpen"
            class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50"
        >
            <div
                class="bg-white p-5 rounded-lg shadow-lg w-full max-w-lg mx-auto border border-gray-200"
            >
                <h2 class="text-xl font-bold text-center mb-3 text-blue-500">
                    ✏️ Add a New Question
                </h2>

                <div class="mb-3">
                    <label class="block text-gray-700 font-medium text-sm mb-1"
                        >Question</label
                    >
                    <input
                        v-model="newQuestionText"
                        placeholder="Enter your question here..."
                        class="w-full p-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                    />
                </div>

                <div class="mb-3">
                    <label class="block text-gray-700 font-medium text-sm mb-1"
                        >Score</label
                    >
                    <input
                        v-model="newQuestionScore"
                        type="number"
                        placeholder="Set a score"
                        class="w-full p-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                    />
                </div>

                <h5 class="text-gray-700 font-semibold mb-2">
                    📝 Possible Answers
                </h5>
                <div
                    class="grid grid-cols-3 mb-2 text-sm text-gray-600 font-semibold"
                >
                    <div class="col-span-2 text-center">Answer</div>
                    <div class="text-center">Correct?</div>
                </div>

                <div
                    v-for="(answer, index) in newAnswers"
                    :key="index"
                    class="grid grid-cols-3 items-center mb-2"
                >
                    <div class="flex items-center col-span-2">
                        <span class="mr-2">{{ index + 1 }}.</span>
                        <input
                            v-model="answer.text"
                            placeholder="Answer text"
                            class="flex-1 p-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                        />
                        <button
                            @click="removeAnswer(index)"
                            class="text-red-500 ml-2 hover:text-red-700 text-sm"
                        >
                            ❌
                        </button>
                    </div>
                    <div class="flex items-center justify-center">
                        <input
                            type="radio"
                            :value="index"
                            v-model="correctAnswerIndex"
                        />
                    </div>
                </div>

                <button
                    @click="addAnswer"
                    class="text-blue-500 mt-2 flex items-center hover:text-blue-600 transition text-sm"
                >
                    ➕ Add another answer
                </button>

                <div class="flex justify-end mt-4 space-x-2">
                    <button
                        @click="saveQuestion"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 text-sm rounded transition"
                    >
                        Save
                    </button>
                    <button
                        @click="closeQuestionModal"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 text-sm rounded transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        AuthenticatedLayout,
    },
    props: {
        difficulties: Array,
    },
    data() {
        return {
            quizTitle: "",
            quizDescription: "",
            isQuestionModalOpen: false,
            newQuestionText: "",
            newQuestionScore: "",
            newAnswers: [{ text: "", isCorrect: false }],
            correctAnswerIndex: null,
            questions: [],
            errors: {},
            quizDifficulty: "easy",
            isPublished: false,
        };
    },
    methods: {
        openQuestionModal() {
            this.isQuestionModalOpen = true;
        },

        addAnswer() {
            this.newAnswers.push({ text: "", isCorrect: false });
        },

        removeAnswer(index) {
            this.newAnswers.splice(index, 1);
        },

        saveQuestion() {
            if (
                !this.newQuestionText ||
                this.newAnswers.length === 0 ||
                this.correctAnswerIndex === null
            ) {
                alert(
                    "Please fill in all fields for the question and answers."
                );
                return;
            }

            this.questions.push({
                text: this.newQuestionText,
                score: this.newQuestionScore,
                answers: this.newAnswers.map((answer, index) => ({
                    text: answer.text,
                    isCorrect: index === this.correctAnswerIndex,
                })),
            });

            this.closeQuestionModal();
        },

        closeQuestionModal() {
            this.isQuestionModalOpen = false;
            this.resetQuestionModal();
        },

        resetQuestionModal() {
            this.newQuestionText = "";
            this.newQuestionScore = "";
            this.newAnswers = [{ text: "", isCorrect: false }];
            this.correctAnswerIndex = null;
        },

        postQuiz() {
            this.errors = {};

            // Validare locală înainte de trimiterea request-ului
            if (!this.quizTitle.trim()) {
                this.errors.title = "Title is required.";
            }

            if (!this.quizDescription.trim()) {
                this.errors.description = "Description is required.";
            }

            if (this.questions.length === 0) {
                this.errors.questions = "At least one question is required.";
            }

            if (!this.quizDifficulty) {
                this.errors.difficulty = "Difficulty is required.";
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }
            const payload = {
                title: this.quizTitle,
                description: this.quizDescription,
                difficulty: this.quizDifficulty,
                is_published: this.isPublished,
                questions: this.questions.map((question) => ({
                    text: question.text,
                    score: question.score,
                    answers: question.answers.map((answer) => ({
                        text: answer.text,
                        isCorrect: answer.isCorrect,
                    })),
                })),
            };

            // Send the data to the backend to store
            this.$inertia.post(
                route("admin-gamification.user_quiz.store"),
                payload
            );
        },
    },
};
</script>
