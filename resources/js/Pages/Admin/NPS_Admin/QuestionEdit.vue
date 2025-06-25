<template>
    <div
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white p-6 rounded-md shadow-md w-1/3">
            <h2 class="text-lg font-semibold mb-4">Editează</h2>

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
                <option value="multiple_choice">Alegeri multiple</option>
            </select>

            <!-- Scale Dropdown -->
            <div v-if="question.type === 'scale'">
                <h3 class="text-sm font-medium mb-2">Scală</h3>
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
                <h3 class="text-sm font-medium mb-2">
                  Alege promoterul
                </h3>
                <ul>
                    <li
                        v-for="(answer, index) in choices"
                        :key="index"
                        class="flex items-center gap-2"
                    >
                        <input
                            type="radio"
                            :value="index"
                            v-model="promoterIndex"
                            class="cursor-pointer"
                        />
                        <input
                            v-model="answer.text"
                            type="text"
                            placeholder="Option text"
                            class="w-full p-2 rounded border"
                        />
                    </li>
                </ul>
            </div>

            <!-- Multiple Choice Answers -->
            <div v-if="question.type === 'multiple_choice'">
                <h3 class="text-sm font-medium mb-2">
                    Răspunsuri multiple
                </h3>
                <div
                    v-for="(choice, index) in choices"
                    :key="index"
                    class="flex items-center gap-2 mb-2"
                >
                    <input
                        v-model="choice.text"
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
                    @click="updateQuestion"
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
    props: {
        questionData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            question: this.questionData,
            choices: this.questionData.choices,
            promoterIndex: Array.isArray(this.questionData.choices)
                ? this.questionData.choices.findIndex(
                      (choice) => choice.is_promoter
                  )
                : 0,
            scaleOption:
                this.questionData.choices &&
                this.questionData.type === "scale" &&
                this.questionData.choices.length > 0
                    ? this.questionData.choices[0]?.text
                    : "unlikely-likely",
        };
    },

    methods: {
        isInvalid() {
            if (!this.question.text.trim()) return true;

            if (this.question.type === "multiple_choice") {
                return (
                    this.choices.length < 2 ||
                    this.choices.some((choice) => !choice.text.trim())
                );
            }

            if (this.question.type === "binary") {
                return (
                    this.choices.length !== 2 ||
                    this.choices.some((choice) => !choice.text.trim()) ||
                    this.promoterIndex === null
                );
            }

            return false;
        },
        handleTypeChange() {
            if (this.question.type === "binary") {
                this.choices = [
                    { text: "Yes", is_promoter: true },
                    { text: "No", is_promoter: false },
                ];
                this.promoterIndex = 0;
            } else if (this.question.type === "multiple_choice") {
                this.choices =
                    this.choices.length > 0 ? this.choices : [{ text: "" }];
            } else if (this.question.type === "scale") {
                this.choices = [{ text: this.scaleOption }];
            } else {
                this.choices = [];
            }
        },
        addAnswer() {
            if (this.choices.length < 10) {
                this.choices.push({ text: "" });
            }
        },
        removeAnswer(index) {
            this.choices.splice(index, 1);
        },
        updateQuestion() {
            if (!this.question.text) {
                alert("Question text is required!");
                return;
            }

            const updatedQuestion = {
                text: this.question.text,
                type: this.question.type,
                choices:
                    this.question.type === "scale"
                        ? [{ text: this.scaleOption }]
                        : this.choices.map((choice, index) => ({
                              text: choice.text,
                              is_promoter: index === this.promoterIndex,
                          })),
            };

            axios
                .put(
                    `/admin/nps/questions/update/${this.question.id}`,
                    updatedQuestion
                )
                .then((response) => {
                    this.$emit("questionUpdated", response.data.question);
                    this.$emit("close");
                })
                .catch((error) => {
                    console.error("Error updating question:", error);
                });
        },
    },
    mounted() {
        this.handleTypeChange();
    },
};
</script>
