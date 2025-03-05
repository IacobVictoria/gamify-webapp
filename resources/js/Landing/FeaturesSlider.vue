<template>
    <!-- Features Slider -->
    <article class="flex flex-col lg:flex-row lg:items-center lg:justify-center md:gap-12 my-12">

        <!-- Static Image Section -->
        <div class="flex flex-col items-center max-w-[300px] md:max-w-[400px]">
            <img loading="lazy" :src="imagePath('/landing/fuel_1.png')" alt="Why Crunchy Fuel?" />
            <span class="text-[#F45D3A] text-2xl font-bold font-cocon leading-tight mt-4 text-center">
                Why Choose Crunchy Fuel?
            </span>
        </div>

        <!-- Transition Content -->
        <transition name="slide-fade" mode="out-in">
            <div :key="currentIndex" class="relative flex flex-col
             lg:w-[500px]
             min-h-[400px]  
             rounded-3xl
             shadow-lg
             px-8
             py-10
             mx-10
             overflow-hidden
             border-l-8" :class="[features[currentIndex].borderColor, features[currentIndex].bgColor]">

                <!-- Feature Content -->
                <div class="flex flex-col w-full h-full text-center">
                    <!-- Feature Title -->
                    <span class="text-3xl font-bold text-gray-900">
                        {{ features[currentIndex].emoji }} {{ features[currentIndex].title }}
                    </span>

                    <!-- Feature Description -->
                    <p class="text-lg leading-8 text-gray-800 mt-5">
                        {{ features[currentIndex].description }}
                    </p>
                </div>

                <!-- FIXED Navigation -->
                <nav class="absolute bottom-6 left-0 right-0 flex justify-center">
                    <div class="flex space-x-6">
                        <button @click="prevFeature"
                            class="p-3 border border-gray-300 rounded-full transition duration-300 hover:bg-gray-900 hover:text-white shadow-md">
                            <!-- Left arrow icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M18.5 12H6M6 12L12 6L6 12ZM6 12L12 18L6 12Z" fill="#F45D3A" />
                                <path d="M6 12L12 18M18.5 12H6M6 12L12 6L6 12Z" stroke="#F45D3A" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <button @click="nextFeature"
                            class="p-3 border border-gray-300 rounded-full transition duration-300 hover:bg-gray-900 hover:text-white shadow-md">
                            <!-- Right arrow icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M6 12H18.5M18.5 12L12.5 6L18.5 12ZM18.5 12L12.5 18L18.5 12Z" fill="#F45D3A" />
                                <path d="M18.5 12L12.5 18M6 12H18.5M18.5 12L12.5 6L18.5 12Z" stroke="#F45D3A"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </nav>
            </div>
        </transition>
    </article>
</template>

<script setup>
import { ref } from 'vue';

// Listă de features statice cu fundal pastelat și border colorat
const features = ref([
    {
        title: "🎯 Quizzes & Challenges",
        description: "Test your knowledge with interactive nutrition & lifestyle quizzes that make learning fun and engaging.",
        emoji: "🧠",
        borderColor: "border-blue-500",
        bgColor: "bg-blue-100"

    },
    {
        title: "💡 Hangman Game",
        description: "Enjoy a fun Hangman game where you discover new facts about healthy snacks while playing!",
        emoji: "🎮",
        borderColor: "border-green-500",
        bgColor: "bg-green-100"
    },
    {
        title: "💬 Make Friends & Chat",
        description: "Join the Crunchy Fuel community and connect with people who share your interest in nutrition and wellness.",
        emoji: "🤝",
        borderColor: "border-yellow-500",
        bgColor: "bg-yellow-100"
    },
    {
        title: "🤖 Chatbot for Lifestyle Tips",
        description: "Talk to our AI chatbot to receive personalized nutrition and lifestyle tips tailored to your needs.",
        emoji: "📱",
        borderColor: "border-purple-500",
        bgColor: "bg-purple-100"
    },
    {
        title: "🎤 Join Webinars",
        description: "Participate in expert-led webinars on nutrition, fitness, and wellness to stay informed and motivated.",
        emoji: "🎙️",
        borderColor: "border-red-500",
        bgColor: "bg-red-100"
    },
]);

// Starea pentru indexul activ
const currentIndex = ref(0);

// Navigare între feature-uri
const prevFeature = () => {
    currentIndex.value = (currentIndex.value - 1 + features.value.length) % features.value.length;
};

const nextFeature = () => {
    currentIndex.value = (currentIndex.value + 1) % features.value.length;
};
</script>

<style scoped>
/* Slide Transition */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.5s ease;
}

.slide-fade-enter {
    opacity: 0;
    transform: translateX(20px);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>