<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-col gap-5 px-4 m-12">
                        <PlayersTopSection :top10Players="top10Players"></PlayersTopSection>
                        <BadgeSection :badges="badges"></BadgeSection>
                        <MedalsSection :medals="medals"></MedalsSection>
                        <div>Your poz {{ position }}</div>
                        <inertia-link :href="route('user.calendar.index')">Check events or discounts in ypur
                            calendar!</inertia-link>
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
        yourPositionInTop: {
            type: Number,

        }
    },
    data() {
        return {
            position: this.yourPositionInTop,
        };
    },
}
</script>