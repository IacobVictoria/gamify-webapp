<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div
                class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6"
            >
                <h2
                    class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2"
                >
                    🧩 Creare Activitate
                </h2>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Titlu</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="Titlu"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>

                        <!-- Type Selector -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Tip activitate</label
                            >
                            <select
                                v-model="form.type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option disabled value="">
                                    Selectează tipul
                                </option>
                                <option value="diet">🥗 Dietă</option>
                                <option value="article">📰 Articol</option>
                                <option value="tip">💡 Sfat rapid</option>
                            </select>
                        </div>

                        <!-- Score -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Scor</label
                            >
                            <input
                                v-model="form.score"
                                type="number"
                                placeholder="Scor"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>

                        <!-- Is Published -->
                        <div class="flex items-center mt-8">
                            <input
                                type="checkbox"
                                v-model="form.is_published"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            />
                            <label class="ml-2 block text-sm text-gray-700"
                                >Publicat</label
                            >
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Descriere scurtă
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        ></textarea>
                    </div>

                    <!-- Dynamic Form by Type -->
                    <DietForm
                        v-if="form.type === 'diet'"
                        :details="form.details"
                        :available-products="products"
                    />

                    <ArticleForm
                        v-if="form.type === 'article'"
                        :details="form.details"
                    />

                    <TipForm
                        v-if="form.type === 'tip'"
                        :details="form.details"
                    />

                    <div class="mt-8 text-right">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-500"
                        >
                            💾 Salvează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import DietForm from "./DietForm.vue";
import ArticleForm from "./ArticleForm.vue";
import TipForm from "./TipForm.vue";

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    title: "",
    type: "",
    score: null,
    is_published: false,
    description: "",
    details: {
        recommendations: "",
        snacks: [],
        custom_products: [],
        content: "",
        tags: "",
        total_nutrition: {},
    },
});

const submit = () => {
    if (form.type === "diet" && !form.details.total_nutrition) {
        form.details.total_nutrition = {
            calories: 0,
            protein: 0,
            carbs: 0,
            fats: 0,
        };
    }
    form.post(route("admin-gamification.activities.store"));
};

watch(
    () => form.type,
    (newType) => {
        if (newType === "diet" && !form.details?.recommendations) {
            form.details = {
                recommendations: "",
                snacks: [],
                custom_products: [],
                content: "",
                total_nutrition: {},
            };
        }

        if (newType === "article") {
            form.details = {
                content: "",
                tags: "",
            };
        }

        if (newType === "tip") {
            form.details = {
                content: "",
                tags: "",
            };
        }
    }
);
</script>
