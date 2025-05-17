<template>
    <section class="max-w-3xl mx-auto my-12 px-4">
        <h2
            class="text-center text-2xl font-extrabold bg-gradient-to-r from-[#16a34a] to-[#166534] bg-clip-text text-transparent"
        >
            ❓ Întrebări frecvente
        </h2>

        <div class="mt-6 space-y-3">
            <div
                v-for="(faq, index) in faqs"
                :key="index"
                class="bg-white border-l-8 p-4 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
                :class="faq.color"
            >
                <button
                    @click="toggleFAQ(index)"
                    class="flex justify-between items-center w-full text-left text-lg font-semibold"
                >
                    <span>{{ faq.emoji }} {{ faq.question }}</span>
                    <span
                        class="ml-4 transform transition-transform"
                        :class="{ 'rotate-180': activeFAQ === index }"
                    >
                        <svg
                            v-if="activeFAQ !== index"
                            class="w-5 h-5 text-gray-600"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 00-.707.293l-4 4a1 1 0 101.414 1.414L10 7.414l3.293 3.293a1 1 0 101.414-1.414l-4-4A1 1 0 0010 5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-5 h-5 text-gray-600"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5 10a1 1 0 011.707-.707L10 12.586l3.293-3.293A1 1 0 1115.707 10l-4 4a1 1 0 01-1.414 0l-4-4A1 1 0 015 10z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                </button>

                <div
                    v-show="activeFAQ === index"
                    class="mt-2 text-gray-700 text-md leading-6 transition-all duration-300"
                >
                    {{ faq.answer }}
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";

const faqs = ref([
    {
        question: "Ce este Crunchy Fuel?",
        answer: "Crunchy Fuel este ghidul tău pentru gustări sănătoase și educație nutrițională.",
        emoji: "🍏",
        color: "border-green-400",
    },
    {
        question: "Cum pot plasa o comandă?",
        answer: "Pur și simplu navighează prin produse, adaugă-le în coș și continuă către finalizarea comenzii.",
        emoji: "🛒",
        color: "border-blue-400",
    },
    {
        question: "Oferiți abonamente?",
        answer: "Da, oferim planuri flexibile de abonament pentru livrări recurente de gustări.",
        emoji: "📦",
        color: "border-yellow-400",
    },
    {
        question: "Ce metode de plată acceptați?",
        answer: "Acceptăm carduri de credit, PayPal și alte metode de plată sigure.",
        emoji: "💳",
        color: "border-red-400",
    },
]);

const activeFAQ = ref(null);

const toggleFAQ = (index) => {
    activeFAQ.value = activeFAQ.value === index ? null : index;
};
</script>

<style scoped>
button {
    transition: color 0.3s ease-in-out;
}

button:hover {
    color: #16a34a;
}
</style>
