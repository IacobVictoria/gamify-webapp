<template>
    <inertia-link  style="text-decoration: none;" :href="route('events.show',event.id)">
        <div
            class="bg-green-100 p-6 rounded-lg shadow-lg flex flex-col items-center transition-all transform hover:scale-105 hover:shadow-2xl active:scale-95 active:shadow-xl">
            <div class="text-3xl mb-4">{{ getEventEmoji }}🎉</div>
            <h3 class="text-xl font-semibold">{{ event.title }}</h3>
            <p class="text-sm">Start: {{ event.start }} - End: {{ event.end }}</p>
        </div>
    </inertia-link>
</template>

<script>
export default {
    props: {
        event: Object,
    },
    computed: {
        getEventEmoji() {
            if (this.event.title.toLowerCase().includes('conference')) {
                return '📚';
            } else if (this.event.title.toLowerCase().includes('webinar')) {
                return '💻';
            }
            return '🎉';
        },
    },
};
</script>

<style scoped>
.bg-green-100 {
    background-color: #c6f6d5;
}
inertia-link {
  text-decoration: none;
}
.bg-green-100:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.bg-green-100:active {
    transform: scale(0.95);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

.transition-all {
    transition: all 0.3s ease;
}
</style>
