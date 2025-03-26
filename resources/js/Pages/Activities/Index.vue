<template>
    <div class="bg-white">
        <Layout>
            <main class="mt-32 mb-20">
                <div class="bg-white">
                    <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                        <!-- Evenimente active -->
                        <div v-if="activities.length" class="mt-8">
                            <SliderActivities :activities="activities"></SliderActivities>
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
import SliderActivities from './SliderActivities.vue';

export default {
    components: {
        Layout,
        SliderActivities,
    },
    props: {
        activities: Array
    },
};
</script>
