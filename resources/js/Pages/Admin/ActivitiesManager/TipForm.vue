<template>
    <div class="space-y-4 mt-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Quick Tip Content</label>
        <textarea
          v-model="details.content"
          rows="3"
          placeholder="Write your quick tip here..."
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Tags</label>
        <input
          v-model="details.tags"
          placeholder="Tags (comma separated, e.g. health,nutrition,energy)"
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
  