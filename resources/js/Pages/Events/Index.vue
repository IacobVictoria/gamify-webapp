<template>
    <div class="bg-white p-8 min-h-[600px]">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <!-- Reduceri active -->
                        <div v-if="activeDiscounts.length">
                            <h2 class="text-xl font-bold mb-4">Active Discounts</h2>
                            <div class="grid gap-4">
                                <DiscountCard
                                    v-for="discount in activeDiscounts"
                                    :key="discount.id"
                                    :discount="discount"
                                />
                            </div>
                        </div>
                        <div v-else>
                            <!-- Mesaj de eroare când nu sunt reduceri -->
                            <div class="bg-yellow-100 text-yellow-800 p-12 mt-32 rounded-lg text-center shadow-md">
                                <p class="text-lg font-semibold">No active discounts available at the moment.</p>
                            </div>
                        </div>

                        <!-- Evenimente active -->
                        <div v-if="activeEvents.length" class="mt-8">
                            <h2 class="text-xl font-bold mb-4">Upcoming Events</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <EventCard
                                    v-for="event in activeEvents"
                                    :key="event.id"
                                    :event="event"
                                />                         
                            </div>
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

export default {
    components: {
        Layout,
        DiscountCard,
        EventCard,
    },
    props: {
        activeDiscounts: Array,
        activeEvents: Array,
    },
};
</script>
