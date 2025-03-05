<template>
    <section class="mt-10 md:mt-0 relative">
        <!-- Fundal curbat -->
        <svg class="md:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 393 73" fill="none">
            <path
                d="M392.997 72.6857V48.1543C347.664 50.5771 241.798 51.9703 180.999 38.16C104.999 20.8971 49.9997 0 0 0V72.6857H392.997Z"
                fill="#99e09c" />
        </svg>
        <svg class="hidden md:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 251" fill="none">
            <path
                d="M1303.2 186.776L1303.2 55.0238C1151.04 116.427 769.541 131.144 561.041 98.1587C352.542 65.173 198.042 0.825314 -36 0.825317L-24.4095 250.309L1303.2 186.776Z"
                fill="#99e09c" />
        </svg>

        <!-- Titlu secțiune -->
        <div class="hidden md:block relative">
            <p class="absolute inset-x-1/2 bottom-4 -translate-x-1/2 -translate-y-1/2 min-w-[450px] 
           text-center text-[30px] font-extrabold uppercase tracking-normal
           bg-gradient-to-r from-[#064e3b] to-[#0f766e] bg-clip-text text-transparent 
           drop-shadow-xl shadow-black whitespace-nowrap">
                Ce este experiența <span class="text-white">Crunchy Fuel?</span>
            </p>
        </div>

        <div class="md:hidden relative">
            <p class="absolute inset-x-1/2 top-10 -translate-x-1/2 -translate-y-1/2 min-w-[300px] 
           text-center text-xl font-extrabold uppercase tracking-normal
           bg-gradient-to-r from-[#064e3b] to-[#0f766e] bg-clip-text text-transparent 
           drop-shadow-md shadow-black whitespace-nowrap">
                Experiența <span class="text-white">Crunchy</span>
            </p>
        </div>
        <!-- Beneficii -->
        <div
            class="flex flex-col md:mb-24 py-24 w-full h-full bg-[#99e09c] gap-[34px] -mt-1 md:-mt-24 text-center px-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-center gap-[50px]">
                <div v-for="(item, index) in sectionItems" :key="index"
                    class="flex flex-col items-center rounded-xl p-8 max-w-sm">

                    <!-- Container pentru imagine cu fundal colorat -->
                    <div class="w-full h-full flex items-center justify-center rounded-full">
                        <img :src="imagePath(item.image)" alt="Icon" class="w-full h-full object-contain" />
                    </div>

                    <!-- Titlu cu culoare custom -->
                    <h3 class="text-2xl font-bold mt-5" :class="item.textColor">
                        {{ item.title }}
                    </h3>

                    <!-- Descriere -->
                    <p class="mt-3 text-lg text-gray-700">
                        {{ item.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';

const sectionItems = ref([
    {
        image: '/landing/red_1.png',
        title: "Snack-uri sănătoase",
        description: "Exploră gustări echilibrate, perfecte pentru energie maximă și stil de viață activ.",
        textColor: "text-[#F45D3A]",
    },
    {
        image: '/landing/fun_1.png',
        title: "Educație Nutrițională",
        description: "Învață despre obiceiuri alimentare sănătoase și alege cu înțelepciune.",
        textColor: "text-green-700",
    },
    {
        image: '/landing/shipping_1.png',
        title: "Experiență E-Commerce",
        description: "Comandă rapid și descoperă produse care îți fac bine!",
        textColor: "text-blue-700",
    }
]);
</script>