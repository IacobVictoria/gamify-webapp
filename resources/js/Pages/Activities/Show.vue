<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- Common Section -->
                    <section class="bg-gray-50 p-6 mb-12">
                        <div class="text-center md:text-left">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                {{ typeEmojis[activity.type] }}
                                {{ activity.title }}
                            </h1>
                            <p class="text-gray-700 italic mb-2">
                                {{ activity.description }}
                            </p>
                            <p class="text-md text-gray-600">
                                📁 Type:
                                <span class="capitalize">{{
                                    activity.type
                                }}</span>
                                &nbsp; | &nbsp; ⭐ Score:
                                <strong>{{ activity.score }}</strong>
                            </p>
                            <div
                                class="mt-4 text-blue-800 font-medium bg-blue-50 px-4 py-3 rounded-md shadow-inner"
                            >
                                {{
                                    activity.type === "article"
                                        ? "Mai jos vei putea citi un articol complet scris în aplicație."
                                        : activity.type === "tip"
                                        ? "Mai jos vei descoperi un sfat rapid și eficient!"
                                        : "Aceasta este o dietă personalizată cu recomandări, produse și valori nutriționale."
                                }}
                            </div>
                        </div>
                    </section>
                    <ShowArticle
                        v-if="activity.type === 'article'"
                        :activity="activity"
                    />
                    <ShowTip
                        v-if="activity.type === 'tip'"
                        :activity="activity"
                    />
                    <ShowDiet
                        v-if="activity.type === 'diet'"
                        :activity="activity"
                    />
                    <!-- CTA / Final Section -->
                    <section class="mt-12 text-center mb-12">
                        <div class="inline-block px-6 py-5 space-y-4">
                            <div
                                v-if="isLoggedIn()"
                                class="flex flex-col md:flex-row gap-4 justify-center"
                            >
                                <!-- Mark as Done -->
                                <button
                                    v-if="!alreadyParticipating"
                                    @click="markAsDone"
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-md shadow hover:bg-green-700 transition"
                                >
                                    ✅ Mark as Done
                                </button>

                                <!-- Save/Remove from Favorites -->
                                <button
                                    v-if="alreadyParticipating"
                                    @click="toggleFavorite"
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700 transition"
                                >
                                    {{
                                        props.isFavorited
                                            ? "❌ Remove from Favorites"
                                            : "⭐ Save to Favorites"
                                    }}
                                </button>
                            </div>

                            <div v-else>
                                <inertia-link
                                    href="/login"
                                    class="no-underline inline-flex items-center gap-2 px-6 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow hover:bg-yellow-600 transition"
                                >
                                    🔐 Login to earn points and save this
                                    activity
                                </inertia-link>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import ShowArticle from "./ShowArticle.vue";
import ShowTip from "./ShowTip.vue";
import ShowDiet from "./ShowDiet.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    activity: Object,
    alreadyParticipating: Boolean,
    isFavorited: Boolean,
});

const typeEmojis = {
    diet: "🥗",
    article: "📰",
    tip: "💡",
};

const markAsDone = () => {
    router.post(
        route("user.participant.store", props.activity.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("Activity marked as done!");
            },
            onError: (errors) => {
                console.error(errors);
            },
        }
    );
};

const toggleFavorite = () => {
    router.post(
        route("user.participant.toggleFavorite", props.activity.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("Toggled favorite status!");
            },
        }
    );
};
</script>
