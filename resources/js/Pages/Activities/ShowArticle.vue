<template>
    <div class="p-6 bg-blue-50 rounded-xl shadow mb-12">
      <h1 class="text-3xl font-bold text-blue-900 mb-2">
        🔖 {{ activity.title }}
      </h1>
      <p class="text-gray-700 italic mb-4">{{ activity.description }}</p>
      <div v-html="htmlContent" class="prose max-w-none prose-blue"></div>
      <div v-if="activity.details.tags" class="mt-4">
        <span class="text-sm text-gray-600">Tags:</span>
        <div class="flex flex-wrap gap-2 mt-1">
          <span
            v-for="(tag, index) in activity.details.tags.split(',')"
            :key="index"
            class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs"
          >
            #{{ tag.trim() }}
          </span>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { marked } from 'marked';
import { computed } from 'vue';

  const props = defineProps({ activity: Object });

  const htmlContent = computed(() => marked(props.activity.details.content));

  </script>