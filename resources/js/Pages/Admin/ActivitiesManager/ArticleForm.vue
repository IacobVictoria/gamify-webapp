<template>
    <div class="space-y-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700"
                >Conținutul articolului</label
            >
            <textarea
                v-model="details.content"
                rows="6"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                placeholder="Write the full article content here..."
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700"
                >Etichete</label
            >
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
    details: Object,
});
</script>
