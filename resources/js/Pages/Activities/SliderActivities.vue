<template>
    <!-- Activities Slider -->
    <article
        class="flex flex-col lg:flex-row lg:items-center lg:justify-center md:gap-24 mb-10 md:bg-white"
        role="article"
        aria-label="Activities Slider"
    >
        <!-- Static Image Section -->
        <div
            class="flex flex-col self-center pt-2.5 max-w-full text-center w-[284px] md:w-[400px] relative"
        >
            <div class="flex flex-col min-h-[251px]">
                <div class="flex flex-col w-full">
                    <img
                        loading="lazy"
                        :src="imagePath('/custom/dance_1.png')"
                        alt="Event Illustration"
                    />
                    <span
                        class="text-[#F45D3A] text-[27px] font-bold font-cocon leading-[34.16px] mt-6 md:ml-16"
                    >
                        Activities</span
                    >
                </div>
            </div>
        </div>

        <!-- Transition Content -->
        <transition name="slide-fade" mode="out-in">
            <div
                :key="currentSlide"
                class="relative flex flex-col mt-5 lg:w-[492px] h-[560px] bg-[#b3e9f0] rounded-3xl md:shadow-lg px-8 py-10 md:p-[44px] mx-10 md:mx-0 overflow-hidden"
            >
                <!-- Event Content -->
                <div
                    class="flex flex-col w-full h-full text-center md:text-left"
                >
                    <!-- Titlu + Emoji -->
                    <span
                        class="mt-3.5 text-3xl md:text-4xl font-bold drop-shadow-md flex items-center justify-center md:justify-start gap-2"
                    >
                        {{ typeEmojis[activities[currentSlide].type] }}
                        {{ activities[currentSlide].title }}
                    </span>

                    <!-- Descriere -->
                    <span
                        class="text-lg md:text-[20px] leading-8 italic text-gray-800 mt-3"
                    >
                        {{ activities[currentSlide].description }}
                    </span>

                    <!-- Tipul -->
                    <span
                        class="text-md md:text-lg text-gray-600 mt-2 font-semibold flex items-center justify-center md:justify-start"
                    >
                        📁 {{ activities[currentSlide].type }}
                    </span>

                    <!-- Score -->
                    <span
                        class="text-md md:text-lg text-gray-600 mt-1 font-semibold flex items-center justify-center md:justify-start"
                    >
                        ⭐ Score: {{ activities[currentSlide].score }}
                    </span>
                </div>
                <div class="flex justify-center gap-4">
                    <inertia-link
                        style="text-decoration: none"
                        :href="
                            route(
                                'activities.show',
                                activities[currentSlide].slug
                            )
                        "
                    >
                        <span
                            class="mt-4 mb-4 inline-block px-5 py-2 text-white bg-[#FA902F] rounded-lg shadow-lg cursor-pointer transition duration-300 hover:scale-105 hover:bg-[#FF5F6D]"
                        >
                            More >>
                        </span>
                    </inertia-link>
                    <!-- FIXED Navigation -->
                    <nav
                        class="absolute bottom-1 left-0 right-0 flex justify-center"
                        role="navigation"
                        aria-label="Event navigation"
                    >
                        <div class="flex space-x-4">
                            <button
                                @click="prevSlide"
                                class="p-2 border border-gray-200 rounded-full transition duration-300 hover:bg-[#2b494d] hover:text-white shadow-md hover:shadow-lg"
                            >
                                <!-- Left arrow icon -->
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M18.5 12H6M6 12L12 6L6 12ZM6 12L12 18L6 12Z"
                                        fill="#F45D3A"
                                    />
                                    <path
                                        d="M6 12L12 18M18.5 12H6M6 12L12 6L6 12Z"
                                        stroke="#F45D3A"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>

                            <button
                                @click="nextSlide"
                                class="p-2 border border-gray-200 rounded-full transition duration-300 hover:bg-[#2b494d] hover:text-white shadow-md hover:shadow-lg"
                            >
                                <!-- Right arrow icon -->
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M6 12H18.5M18.5 12L12.5 6L18.5 12ZM18.5 12L12.5 18L18.5 12Z"
                                        fill="#F45D3A"
                                    />
                                    <path
                                        d="M18.5 12L12.5 18M6 12H18.5M18.5 12L12.5 6L18.5 12Z"
                                        stroke="#F45D3A"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </transition>
    </article>
</template>

<script setup>
import { ref, defineProps } from "vue";

const props = defineProps({
    activities: Array,
});

const typeEmojis = {
    diet: "🥗",
    article: "📰",
    tip: "💡",
};

// Current slide index
const currentSlide = ref(0);

// Move to the previous slide (with wrap)
function prevSlide() {
    currentSlide.value =
        (currentSlide.value - 1 + props.activities.length) %
        props.activities.length;
}

// Move to the next slide (with wrap)
function nextSlide() {
    currentSlide.value = (currentSlide.value + 1) % props.activities.length;
}
</script>

<style scoped>
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
