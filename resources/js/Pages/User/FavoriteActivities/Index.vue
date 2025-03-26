<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-black leading-tight text-center p-4"
            >
                ⭐ Activitățile Tale Favorite – Rămâi pe drumul succesului! 🚀
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-col gap-5 px-4 m-12">
                        <h1
                            class="text-3xl font-bold text-center mb-8 text-green-700"
                        >
                            ⭐ Activități Favorite
                        </h1>

                        <div
                            v-if="favorites.length"
                            class="grid md:grid-cols-2 gap-6"
                        >
                            <div
                                v-for="activity in favorites"
                                :key="activity.id"
                                class="p-6 bg-white shadow rounded-xl border border-gray-200 hover:shadow-lg transition"
                            >
                                <h2
                                    class="text-xl font-semibold mb-2 flex items-center gap-2"
                                >
                                    {{ typeEmojis[activity.type] }}
                                    {{ activity.title }}
                                </h2>
                                <p class="text-sm text-gray-600 italic mb-3">
                                    {{ activity.description }}
                                </p>
                                <p class="text-gray-700 text-sm">
                                    📁 Tip: {{ activity.type }}
                                </p>
                                <p class="text-gray-700 text-sm mb-2">
                                    ⭐ Scor: {{ activity.score }}
                                </p>
                                <inertia-link
                                    class="text-sm text-indigo-600 no-underline hover:text-indigo-800"
                                    :href="
                                        route('activities.show', activity.slug)
                                    "
                                >
                                    Vezi activitatea →
                                </inertia-link>
                            </div>
                        </div>

                        <div
                            v-else
                            class="text-center text-gray-600 text-lg mt-10"
                        >
                            Nu ai nicio activitate salvată ca favorită momentan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    favorites: Array,
});

const typeEmojis = {
    diet: "🥗",
    article: "📰",
    tip: "💡",
};
</script>
