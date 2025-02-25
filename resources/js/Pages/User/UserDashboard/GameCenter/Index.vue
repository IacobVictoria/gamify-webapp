<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your achievements</h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-col gap-5 px-4 m-12">
                        <div id="badges">
                            <BadgeSection :badges="badges" :categories="categories"></BadgeSection>
                        </div>
                        <div id="medals">
                            <MedalsSection :medals="medals"></MedalsSection>
                        </div>
                        <div
                            class="your-position-container bg-gradient-to-r from-blue-500 to-teal-500 text-white p-4 rounded-xl shadow-md">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold">Your Position</span>
                                <span class="text-3xl font-extrabold">{{ position }}</span>
                            </div>
                            <p class="mt-2 text-sm font-light">Your rank in the top players leaderboard</p>
                        </div>
                        <div id="leaderboard">
                            <PlayersTopSection :top10Players="top10Players"></PlayersTopSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import BadgeSection from './BadgeSection.vue';
import MedalsSection from './MedalsSection.vue'
import PlayersTopSection from './PlayersTopSection.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    components: {
        BadgeSection,
        MedalsSection,
        PlayersTopSection,
        AuthenticatedLayout
    },
    props: {
        badges: {
            type: Array,
            default: []
        },
        medals: {
            type: Array,
            default: []
        },
        top10Players: {
            type: Array,
            default: []
        },
        categories: {
            type: Array,
            default: []
        },
        yourPositionInTop: {
            type: Number,

        },

    },
    data() {
        return {
            position: this.yourPositionInTop,
        };
    },
    mounted() {
        this.scrollToSection();
    },
    methods: {
        scrollToSection() {
            const hash = window.location.hash; 
            if (hash) {
                this.$nextTick(() => {
                    const section = document.querySelector(hash);
                    if (section) {
                        section.scrollIntoView({ behavior: "smooth" });
                    }
                });
            }
        }
    }
}
</script>
<style>
.your-position-container {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}
</style>