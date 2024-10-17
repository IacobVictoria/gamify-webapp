<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl mx-auto">
                <router-link to="#" @click.prevent="goBack">Back</router-link>

                <!-- Informații despre utilizator -->
                <div class="flex items-center mb-6">

                    <img v-if="user.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                        class="w-20 h-20 rounded-full border-2 border-indigo-500 shadow-md cursor-pointer" />
                    <img v-else src="/images/female.png" alt="User Avatar"
                        class="w-20 h-20 rounded-full border-2 border-indigo-500 shadow-md cursor-pointer" />

                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ user.name }}</h1>
                        <p class="text-gray-600">Created Account: {{ user.created_at }}</p>
                    </div>
                </div>

                <!-- Medals Section -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Medals</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template v-if="user.medals.length > 0">
                            <div v-for="medal in user.medals" :key="medal.id"
                                class="bg-indigo-100 p-4 rounded-lg shadow">
                                <p class="font-medium text-indigo-700">{{ medal.tier }}</p>
                                <p class="text-gray-500">Obtained: {{ medal.created_at }}</p>
                            </div>
                        </template>
                        <template v-else> None</template>
                    </div>
                </div>

                <!-- Badges Section -->
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Badges</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template v-if="user.badges.length > 0">
                        <div v-for="badge in user.badges" class="bg-indigo-100 p-4 rounded-lg shadow ">
                            <p class="font-medium text-indigo-700">{{ badge.name }}</p>
                            <p class="text-gray-500">Obtained: {{ badge.awarded_at }}</p>
                        </div>
                    </template>
                    <template v-else>
                        <div class="bg-gray-100 p-4 rounded-lg shadow">None</div>
                    </template>
                </div>
            </div>
        </main>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';

export default {
    components: {
        Layout
    },
    props: {
        user: {
            type: Object,
            required: true
        },

    },

    methods: {
        goBack() {

        }
    }
}
</script>
