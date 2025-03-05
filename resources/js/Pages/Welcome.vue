<template>
    <div class="bg-white">
        <Layout>
            <main>
                <div class="relative isolate">
                    <div class="overflow-hidden">
                        <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60  lg:pt-32">
                            <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">

                                <div class="relative w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
                                    <template v-if="activeDiscounts.length > 0">
                                        <div class="mb-12">
                                            <EventDiscountBanner :activeDiscounts="activeDiscounts" />
                                        </div>
                                    </template>
                                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-4xl text-center">
                                        🌟 Descoperă Lumea Gustărilor Sănătoase
                                    </h1>
                                    <p
                                        class="text-4xl font-bold tracking-tight text-gray-900 sm:text-4xl text-center mt-2">
                                        Crunchy Fuel! 🚀
                                    </p>

                                    <p
                                        class="mt-12 text-lg leading-8 text-gray-700 sm:max-w-xl lg:max-w-2xl mx-auto text-center">
                                        🍏🍫 <span class="font-semibold text-gray-900">Ai poftă de ceva sănătos și
                                            delicios?</span> <br>
                                        **Crunchy Fuel** este mai mult decât un simplu magazin – este ghidul tău în
                                        lumea snack-urilor nutritive!
                                    </p>

                                    <div class="mt-24 flex items-center gap-x-6">
                                        <inertia-link :href="route('products.index')"
                                            class="bg-[#075985] no-underline text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-white hover:text-[#075985] transition-colors">
                                            🍫 Vreau Snack-uri!
                                        </inertia-link>
                                    </div>

                                </div>
                                <div
                                    class="mt-14 flex justify-end gap-10 sm:-mt-44 sm:justify-start sm:pl-20 lg:mt-0 lg:pl-0">
                                    <div
                                        class="ml-auto w-56 flex-none space-y-8 pt-32 sm:ml-0 sm:pt-80 lg:order-last lg:pt-36 xl:order-none xl:pt-80">
                                        <div class="relative">
                                            <img :src="imagePath('/landing/protein_1.png')" alt=""
                                                class="w-80 h-80 rounded-full object-cover" />
                                        </div>
                                    </div>

                                    <div class="mr-auto w-56 flex-none space-y-8 sm:mr-0 sm:pt-52 lg:pt-36">
                                        <div class="relative">
                                            <img :src="imagePath('/landing/shop_1.png')" alt=""
                                                class="w-80 h-80 rounded-full object-cover" />
                                        </div>
                                    </div>

                                    <div class="w-56 flex-none space-y-8 pt-32 sm:pt-0">
                                        <div class="relative">
                                            <img :src="imagePath('/landing/contrib_1.png')" alt=""
                                                class="w-72 h-72 rounded-l object-cover" />
                                        </div>
                                        <div class="relative">
                                            <img :src="imagePath('/landing/Snack_1.png')" alt=""
                                                class="w-64 h-64 rounded-sm object-cover" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <ExperienceLanding></ExperienceLanding>
                <FeaturesSlider></FeaturesSlider>
                <AchievementsSection></AchievementsSection>
                <QrCodesReward></QrCodesReward>
                <UserFeaturesGrid></UserFeaturesGrid>
                <FaqSection></FaqSection>
                <NpsPopUp />
            </main>
        </Layout>
    </div>
</template>

<script>
import Layout from '../Layouts/Layout.vue'
import QrScannerProduct from './QrCodes/QrScannerProduct.vue';
import EventDiscountBanner from '@/Components/EventDiscountBanner.vue';
import NpsPopUp from './NPS/NpsPopUp.vue';
import ExperienceLanding from '@/Landing/ExperienceLanding.vue';
import FaqSection from '@/Landing/FaqSection.vue';
import FeaturesSlider from '@/Landing/FeaturesSlider.vue';
import AchievementsSection from '@/Landing/AchievementsSection.vue';
import QrCodesReward from '@/Landing/QrCodesReward.vue';
import UserFeaturesGrid from '@/Landing/UserFeaturesGrid.vue';

export default {
    name: 'Landing',

    components: {
        Layout,
        EventDiscountBanner,
        QrScannerProduct,
        NpsPopUp,
        ExperienceLanding,
        FaqSection,
        FeaturesSlider,
        AchievementsSection,
        QrCodesReward,
        UserFeaturesGrid
    },
    props: {
        activeDiscounts: Array
    }
}

</script>