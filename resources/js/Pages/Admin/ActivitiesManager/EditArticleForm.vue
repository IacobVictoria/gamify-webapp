<template>
    <div class="space-y-4 mt-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Conținut</label>
        <textarea
          v-model="details.content"
          rows="5"
          placeholder="Scrie conținutul articolului..."
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        />
      </div>
  
      <div>
        <label class="block text-sm font-medium text-gray-700">Etichete</label>
        <input
          v-model="details.tags"
          placeholder="Etichete separate prin virgulă (ex: sănătate,nutriție)"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    details: Object
  });
  </script>
  