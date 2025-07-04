<template>
    <div class="bg-white min-h-screen flex flex-col">
        <Layout>
            <main class="flex-grow">
                <!-- Hero Banner -->
                <div class="bg-gradient-to-r from-indigo-100 to-blue-100 py-16">
                    <div class="max-w-3xl mx-auto text-center">
                        <h2 class="text-3xl font-bold text-indigo-900 mb-4">
                            👋 Profilul userului
                        </h2>
                        <p class="text-gray-700 text-lg">
                            Aici poți vedea progresul său, medaliile câștigate
                            și insignele obținute pe parcursul aventurii! 🏆✨
                        </p>
                    </div>
                </div>

                <!-- Conținut principal -->
                <div class="relative isolate">
                    <div class="overflow-hidden">
                        <div class="mx-auto max-w-7xl px-6 py-16">
                            <!-- Info utilizator -->
                            <div class="flex items-center mb-10">
                                <img
                                    :src="
                                        account.gender === 'Male'
                                            ? '/images/male.png'
                                            : '/images/female.png'
                                    "
                                    alt="Avatar utilizator"
                                    class="w-20 h-20 rounded-full border-2 border-indigo-500 shadow-md"
                                />
                                <div class="ml-6">
                                    <h1
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ account.name }}
                                    </h1>
                                    <p class="text-gray-600 text-sm">
                                        Membru din: {{ account.created_at }}
                                    </p>
                                </div>
                            </div>

                            <!-- Statistici rapide -->
                            <div
                                class="grid grid-cols-3 text-center gap-6 mb-12"
                            >
                                <div>
                                    <p
                                        class="text-2xl font-bold text-indigo-700"
                                    >
                                        {{ medals.length }}
                                    </p>
                                    <p class="text-sm text-gray-600">Medalii</p>
                                </div>
                                <div>
                                    <p
                                        class="text-2xl font-bold text-indigo-700"
                                    >
                                        {{ badges.length }}
                                    </p>
                                    <p class="text-sm text-gray-600">Insigne</p>
                                </div>
                                <div>
                                    <p
                                        class="text-2xl font-bold text-indigo-700"
                                    >
                                        {{ account.created_at }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Membru din
                                    </p>
                                </div>
                            </div>

                            <!-- Secțiune Medalii -->
                            <div class="mb-12">
                                <h2
                                    class="text-xl font-semibold text-gray-800 mb-4"
                                >
                                    🏅 Medalii
                                </h2>
                                <div class="grid grid-cols-3 gap-4">
                                    <template v-if="medals.length > 0">
                                        <div
                                            v-for="medal in medals"
                                            :key="medal.id"
                                            class="bg-indigo-100 p-3 rounded-lg shadow-md flex flex-col items-center"
                                        >
                                            <img
                                                :src="
                                                    imagePath(
                                                        `/medals/${medal.tier}-medal.png`
                                                    )
                                                "
                                                alt="Medalie"
                                                class="w-12 h-12 object-cover"
                                            />
                                            <p
                                                class="text-xs font-medium text-indigo-700 mt-1"
                                            >
                                                {{ medal.tier }}
                                            </p>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p
                                            class="text-gray-500 text-sm col-span-3"
                                        >
                                            Nicio medalie obținută încă.
                                        </p>
                                    </template>
                                </div>
                            </div>

                            <!-- Secțiune Insigne -->
                            <div>
                                <h2
                                    class="text-xl font-semibold text-gray-800 mb-4"
                                >
                                    🎖️ Insigne
                                </h2>
                                <div class="grid grid-cols-3 gap-4">
                                    <template v-if="badges.length > 0">
                                        <div
                                            v-for="(badge, index) in badges"
                                            :key="index"
                                            class="bg-indigo-100 p-3 rounded-lg shadow-md flex flex-col items-center"
                                        >
                                            <img
                                                :src="badge.image"
                                                alt="Insignă"
                                                class="w-12 h-12 object-cover"
                                            />
                                            <p
                                                class="text-xs font-medium text-indigo-700 mt-1"
                                            >
                                                {{ badge.name }}
                                            </p>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p
                                            class="text-gray-500 text-sm col-span-3"
                                        >
                                            Nicio insignă obținută încă.
                                        </p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from "@/Layouts/Layout.vue";

export default {
    components: {
        Layout,
    },
    props: {
        account: {
            type: Object,
            required: true,
        },
        medals: {
            type: Array,
            required: true,
        },
        badges: {
            type: Array,
            required: true,
        },
    },
};
</script>
