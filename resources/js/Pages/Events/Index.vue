<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <!-- Reduceri active -->
                        <div v-if="activeDiscounts.length">
                            <DiscountGrid :discounts="activeDiscounts" />
                        </div>
                        <div v-else>
                            <!-- Mesaj de eroare când nu sunt reduceri -->
                            <div class="bg-yellow-100 text-yellow-800 p-12 mt-32 rounded-lg text-center shadow-md">
                                <p class="text-lg font-semibold">No active discounts available at the moment.</p>
                            </div>
                        </div>

                        <!-- Evenimente active -->
                        <div v-if="events.length" class="mt-8">
                            <SliderEvents :events="events"></SliderEvents>
                        </div>
                        <div v-else>
                            <!-- Mesaj de eroare când nu sunt evenimente -->
                            <div class="bg-blue-100 text-blue-800 p-12 mt-32 rounded-lg text-center shadow-md">
                                <p class="text-lg font-semibold">No upcoming events available at the moment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import DiscountCard from './DiscountCard.vue';
import EventCard from './EventCard.vue';
import SliderEvents from './SliderEvents.vue';
import DiscountGrid from './DiscountGrid.vue';

export default {
    components: {
        Layout,
        DiscountCard,
        EventCard,
        SliderEvents,
        DiscountGrid
    },
    props: {
        activeDiscounts: Array,
        events: Array
    },
};
</script>
