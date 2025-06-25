<template>
    <div
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white p-6 rounded-md shadow-md w-1/3">
            <h2 class="text-lg font-semibold mb-4">Adaugă</h2>

            <!-- Question Text -->
            <input
                v-model="question.text"
                type="text"
                placeholder="Text"
                class="w-full p-2 rounded border mb-4"
            />

            <!-- Question Type -->
            <select
                v-model="question.type"
                class="w-full p-2 rounded border mb-4"
                @change="handleTypeChange"
            >
                <option value="binary">Binară</option>
                <option value="scale">Scală</option>
                <option value="open">Deschisă</option>
                <option value="multiple_choice">Alegere multiplă</option>
            </select>

            <!-- Scale Dropdown -->
            <div v-if="question.type === 'scale'">
                <h3 class="text-sm font-medium mb-2">Scala optiuni</h3>
                <select v-model="scaleOption" class="w-full p-2 rounded border">
                    <option value="unlikely-likely">Unlikely → Likely</option>
                    <option value="very_bad-very_good">
                        Very Bad → Very Good
                    </option>
                    <option value="worse-excellent">Worse → Excellent</option>
                </select>
            </div>

            <!-- Binary Answers -->
            <div v-if="question.type === 'binary'">
                <h3 class="text-sm font-medium mb-2">Răspunsuri binare</h3>
                <h3 class="text-sm font-medium mb-2">Alege promoterul</h3>
                <ul>
                    <li
                        v-for="(answer, index) in answers"
                        :key="index"
                        class="flex items-center gap-2"
                    >
                        <input
                            type="radio"
                            :value="index"
                            v-model="promoterIndex"
                            class="cursor-pointer"
                        />
                        <span class="text-gray-700">{{ answer.text }}</span>
                    </li>
                </ul>
            </div>

            <!-- Multiple Choice Answers -->
            <div v-if="question.type === 'multiple_choice'">
                <h3 class="text-sm font-medium mb-2">Alegere multiplă</h3>
                <div
                    v-for="(answer, index) in answers"
                    :key="index"
                    class="flex items-center gap-2 mb-2"
                >
                    <input
                        v-model="answer.text"
                        type="text"
                        placeholder="Option text"
                        class="w-full p-2 rounded border"
                    />
                    <button @click="removeAnswer(index)" class="text-red-500">
                        Șterge
                    </button>
                </div>
                <button
                    @click="addAnswer"
                    class="bg-blue-500 text-white py-1 px-3 rounded text-sm"
                >
                    Adaugă opțiune
                </button>
            </div>

            <!-- Save & Cancel Buttons -->
            <div class="mt-4 flex justify-end gap-2">
                <button
                    @click="saveQuestion"
                    :disabled="isInvalid()"
                    :class="[
                        'py-2 px-4 rounded',
                        isInvalid()
                            ? 'bg-gray-400 text-white cursor-not-allowed'
                            : 'bg-blue-500 text-white hover:bg-blue-600',
                    ]"
                >
                    Salvează
                </button>

                <button
                    @click="$emit('close')"
                    class="bg-red-500 text-white py-2 px-4 rounded"
                >
                    Anulează
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            question: {
                text: "",
                type: "binary",
            },
            answers: [],
            promoterIndex: 0,
            scaleOption: "unlikely-likely",
        };
    },
    methods: {
        isInvalid() {
            if (!this.question.text.trim()) return true;

            if (this.question.type === "multiple_choice") {
                return (
                    this.answers.length < 2 ||
                    this.answers.some((a) => !a.text.trim())
                );
            }

            if (this.question.type === "binary") {
                return (
                    this.answers.length !== 2 ||
                    this.answers.some((a) => !a.text.trim()) ||
                    this.promoterIndex === null
                );
            }

            return false;
        },
        handleTypeChange() {
            if (this.question.type === "binary") {
                this.answers = [
                    { text: "Yes", is_promoter: true },
                    { text: "No", is_promoter: false },
                ];
                this.promoterIndex = 0;
            } else if (this.question.type === "multiple_choice") {
                this.answers = [
                    { text: "" },
                    { text: "" },
                    { text: "" },
                    { text: "" },
                ];
            } else if (this.question.type === "scale") {
                this.answers = [];
            } else {
                this.answers = [];
            }
        },
        addAnswer() {
            if (this.answers.length < 10) {
                this.answers.push({ text: "" });
            }
        },
        removeAnswer(index) {
            this.answers.splice(index, 1);
        },
        saveQuestion() {
            const questionPayload = {
                text: this.question.text,
                type: this.question.type,
                answers:
                    this.question.type === "scale"
                        ? [{ text: this.scaleOption }]
                        : this.answers.map((answer) => ({
                              text: answer.text,
                              is_promoter: answer.is_promoter || false,
                          })),
            };

            axios
                .post("/admin/nps/questions/store", questionPayload)
                .then((response) => {
                    this.$emit("questionAdded", response.data.question);
                    this.$emit("close");
                })
                .catch((error) => {
                    console.error("Error saving question:", error);
                });
        },
    },
    mounted() {
        this.handleTypeChange();
    },
};
</script>
