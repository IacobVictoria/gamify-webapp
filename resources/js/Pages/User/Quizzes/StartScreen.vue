<template>
    <div
        class="start-screen p-6 rounded-3xl shadow-xl bg-gradient-to-br from-indigo-50 via-white to-purple-50 border border-gray-200 w-[50%] h-[30%]"
    >
        <h2
            class="text-2xl font-bold text-indigo-700 mb-4 flex items-center gap-2"
        >
            📘 Instrucțiuni pentru Quiz
        </h2>

        <p class="mb-4 text-gray-700">
            Acest quiz conține
            <strong class="text-indigo-600">{{ quiz.questions.length }}</strong>
            întrebări. Fiecare întrebare are un timp limitat de
            <strong>30 de secunde</strong>.
        </p>

        <p class="mb-3 text-gray-700">La final vei vedea:</p>
        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
            <li>✅ Procentajul de răspunsuri corecte.</li>
            <li>🎯 Punctele acumulate.</li>
        </ul>

        <p class="mb-4 text-gray-700">
            Punctele sunt adăugate în cont doar dacă
            <strong class="text-indigo-600">închizi quiz-ul</strong> și nu îl
            retry-uiești.
        </p>

        <div
            class="mb-6 p-4 bg-indigo-100 rounded-xl text-indigo-700 text-sm flex items-center justify-between"
        >
            <span
                >⏳ Ai la dispoziție <strong>3 încercări</strong> pentru acest
                quiz.</span
            >
            <span
                class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-semibold"
            >
                {{ remaining_attempts }} încercări rămase
            </span>
        </div>

        <button
            @click="$emit('start-quiz')"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-xl font-semibold text-lg transition duration-300 shadow"
        >
            🚀 Începe Quiz-ul
        </button>
    </div>
</template>

<script>
export default {
    props: {
        quiz: Object,
        nr_attempts: Number,
    },
    data() {
        return {
            remaining_attempts: 0,
        };
    },
    methods: {},
    mounted() {
        this.remaining_attempts = 3 - this.$page.props.nr_attempts;
        console.log(this.remaining_attempts);
    },
};
</script>

<style scoped></style>
