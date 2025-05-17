<template>
    <div class="bg-white h-full flex flex-col">
        <Layout>
            <main>
                <div class="relative isolate">
                    <div class="overflow-hidden">
                        <div
                            class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:pt-32"
                        >
                            <div
                                class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center"
                            >
                                <!-- Informații despre utilizator -->
                                <div class="flex items-center mb-6">
                                    <img
                                        v-if="account.gender === 'Male'"
                                        src="/images/male.png"
                                        alt="Avatar utilizator"
                                        class="w-16 h-16 rounded-full border-2 border-indigo-500 shadow-md"
                                    />
                                    <img
                                        v-else
                                        src="/images/female.png"
                                        alt="Avatar utilizator"
                                        class="w-16 h-16 rounded-full border-2 border-indigo-500 shadow-md"
                                    />
                                    <div class="ml-4">
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

                                <!-- Secțiunea Medalii -->
                                <div class="mb-6">
                                    <h2
                                        class="text-lg font-semibold text-gray-800 mb-2"
                                    >
                                        Medalii
                                    </h2>
                                    <div class="grid grid-cols-3 gap-3">
                                        <template v-if="medals.length > 0">
                                            <div
                                                v-for="medal in medals"
                                                :key="medal.id"
                                                class="bg-indigo-100 p-2 rounded-lg shadow-md flex flex-col items-center"
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
                                            <p class="text-gray-500 text-sm">
                                                Nicio medalie obținută încă
                                            </p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Secțiunea Insigne -->
                                <h2
                                    class="text-lg font-semibold text-gray-800 mb-2"
                                >
                                    Insigne
                                </h2>
                                <div class="grid grid-cols-3 gap-3">
                                    <template v-if="badges.length > 0">
                                        <div
                                            v-for="badge in badges"
                                            class="bg-indigo-100 p-2 rounded-lg shadow-md flex flex-col items-center"
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
                                        <p class="text-gray-500 text-sm">
                                            Nicio insignă obținută încă
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
