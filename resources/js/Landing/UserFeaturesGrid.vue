<template>
    <div class="max-w-5xl mx-auto mt-8 hidden lg:block mb-24"> 
      <div v-for="(feature, index) in features" :key="index" class="grid grid-cols-2 items-center gap-x-14 gap-y-8 mb-20">
        <!-- Imagine / Ilustrație -->
        <div :class="{
          'order-1': index % 2 === 0,
          'order-2': index % 2 !== 0
        }" class="flex justify-center">
          <img :src="imagePath(feature.image)" :alt="feature.title"
            class="relative z-10 w-64 h-64 object-contain" />
        </div>
  
        <!-- Text Explicativ -->
        <div :class="{
          'order-2 text-left': index % 2 === 0,
          'order-1 text-right': index % 2 !== 0
        }" class="flex flex-col justify-center gap-5 ">
          <h3 class="text-3xl font-bold text-[#F45D3A] uppercase">
            {{ feature.title }}
          </h3>
          <p class="text-base leading-6 text-gray-800">
            {{ feature.description }}
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const features = ref([
    {
      image: '/landing/notification1.png',
      title: "📩 Notificări în timp real",
      description: "Fii mereu la curent cu stadiul comenzii tale! Primești actualizări instant despre livrare și oferte exclusive."
    },
    {
      image: '/landing/tracking1.png',
      title: "🚛 Urmărește-ți comanda",
      description: "Ai control total asupra comenzilor tale! Verifică unde se află pachetul tău în orice moment."
    },
    {
      image: '/landing/reward1.png',
      title: "🏅 Insigne și Medalii",
      description: "Cât de activ ești? Cu cât comanzi mai des, cu atât primești insigne și recompense exclusive!"
    },
    {
      image: '/landing/invoice1.png',
      title: "📑 Facturi ușor accesibile",
      description: "Vezi și descarcă facturile direct din contul tău sau primește-le automat pe e-mail."
    },
    {
      image: '/landing/voucher3.png',
      title: "💰 Reduceri și coduri promoționale",
      description: "Medaliile îți aduc reduceri între **25-45%**! Poți folosi aceste coduri și **cumulativ** cu alte oferte!"
    },
    {
      image: '/custom/laugh_1.png',
      title: "👥 Prieteni & Socializare",
      description: "Invită-ți prietenii la un **Hangman Game** cu tematică de sănătate și învață împreună cu ei!"
    },
    {
      image: '/custom/game_1.png',
      title: "🎮 Jocuri Educative",
      description: "Joacă, greșește, învață! Toate răspunsurile tale sunt salvate pentru a-ți oferi un istoric educativ."
    }
  ]);
  </script>
  
  <style scoped>
  h3 {
    text-transform: uppercase;
  }
  
  p {
    font-weight: 500;
  }
  </style>
  