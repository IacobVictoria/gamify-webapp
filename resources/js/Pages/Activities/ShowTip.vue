<template>
    <div class="p-6 bg-yellow-50 rounded-xl shadow mb-12">
        <h1 class="text-3xl font-bold text-yellow-800 mb-2">
           {{ activity.title }}
        </h1>
        <div
            class="text-yellow-900 bg-yellow-100 border-l-4 border-yellow-400 p-4 rounded"
        >
            <p class="text-lg font-medium">{{ activity.details.content }}</p>
        </div>
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
defineProps({ activity: Object });
</script>
