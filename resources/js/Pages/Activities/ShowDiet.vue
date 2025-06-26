<template>
    <div class="p-6 bg-green-50 rounded-xl shadow mb-12">
        <h1 class="text-3xl font-bold text-green-900 mb-2">
            🥗 {{ activity.title }}
        </h1>

        <h2 class="font-semibold text-lg text-green-800 mb-1">Recomandări</h2>
        <p class="mb-4">{{ activity.details.recommendations }}</p>

        <h2 class="font-semibold text-lg text-green-800 mb-1">Conținut</h2>
        <p class="mb-4" v-html="activity.details.content.replace(/\n/g, '<br>').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;')"></p>

        <h2 class="font-semibold text-lg text-green-800 mb-1">
            Gustări din aplicație
        </h2>
        <ul class="list-disc pl-6 mb-4 text-green-900">
            <li v-for="snack in activity.details.snacks" :key="snack.id">
                <a
                    :href="snack.link"
                    class="text-black underline hover:text-blue-800"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    {{ snack.name }}
                </a>
            </li>
        </ul>

        <h2 class="font-semibold text-lg text-green-800 mb-1">
            Rezumat Nutrițional
        </h2>
        <ul class="text-gray-700">
            <li>
                ✨ Calorii:
                <strong>{{
                    activity.details.total_nutrition?.calories
                }}</strong>
            </li>
            <li>
                🌟 Proteine:
                <strong>{{ activity.details.total_nutrition?.protein }}</strong
                >g
            </li>
            <li>
                🌾 Carbohidrați:
                <strong>{{ activity.details.total_nutrition?.carbs }}</strong
                >g
            </li>
            <li>
                🧈 Grăsimi:
                <strong>{{ activity.details.total_nutrition?.fats }}</strong
                >g
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({ activity: Object });
</script>
