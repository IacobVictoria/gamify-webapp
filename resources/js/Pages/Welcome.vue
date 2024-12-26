<template>
    <div class="bg-white">
        <Layout>
            <main>
                <div class="relative isolate">
                    <div class="overflow-hidden">
                        <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:px-8 lg:pt-32">
                            <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">

                                <div class="relative w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
                                    <template v-if="activeDiscounts">
                                        <div>
                                            <EventDiscountBanner :activeDiscounts="activeDiscounts" />
                                        </div>
                                    </template>
                                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">We’re
                                        changing the way people connect.</h1>
                                    <p class="mt-6 text-lg leading-8 text-gray-600 sm:max-w-md lg:max-w-none">Cupidatat
                                        minim id magna ipsum sint dolor qui. Sunt sit in quis cupidatat mollit aute
                                        velit. Et labore commodo nulla aliqua proident mollit ullamco exercitation
                                        tempor. Sint aliqua anim nulla sunt mollit id pariatur in voluptate cillum.</p>
                                    <div class="mt-10 flex items-center gap-x-6">
                                        <a href="#"
                                            class="bg-[#075985] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-white hover:text-[#075985] transition-colors">Get
                                            started</a>
                                    </div>
                                </div>
                                <div
                                    class="mt-14 flex justify-end gap-8 sm:-mt-44 sm:justify-start sm:pl-20 lg:mt-0 lg:pl-0">
                                    <div
                                        class="ml-auto w-44 flex-none space-y-8 pt-32 sm:ml-0 sm:pt-80 lg:order-last lg:pt-36 xl:order-none xl:pt-80">
                                        <div class="relative">
                                            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80"
                                                alt=""
                                                class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg" />
                                            <div
                                                class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10" />
                                        </div>
                                    </div>
                                    <div class="mr-auto w-44 flex-none space-y-8 sm:mr-0 sm:pt-52 lg:pt-36">
                                        <div class="relative">
                                            <img src="https://images.unsplash.com/photo-1485217988980-11786ced9454?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80"
                                                alt=""
                                                class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg" />
                                            <div
                                                class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10" />
                                        </div>

                                    </div>
                                    <div class="w-44 flex-none space-y-8 pt-32 sm:pt-0">
                                        <div class="relative">
                                            <img src="https://images.unsplash.com/photo-1670272504528-790c24957dda?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=left&w=400&h=528&q=80"
                                                alt=""
                                                class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg" />
                                            <div
                                                class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10" />
                                        </div>
                                        <div class="relative">
                                            <img src="https://images.unsplash.com/photo-1670272505284-8faba1c31f7d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&h=528&q=80"
                                                alt=""
                                                class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg" />
                                            <div
                                                class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- qr section -->
                <div class="bg-[#334155] mb-32 h-full ">
                    <div class="bg-gradient-to-r from-lightBlue to-darkBlue pb-20 sm:pb-24 xl:pb-0">
                        <div
                            class="mx-auto flex max-w-7xl flex-col items-center gap-x-8 gap-y-10 px-6 sm:gap-y-8 lg:px-8 xl:flex-row xl:items-stretch">
                            <div class="-mt-8 w-full max-w-2xl xl:-mb-8 xl:w-96 xl:flex-none">
                                <div class="relative aspect-[2/1] h-full md:-mx-8 xl:mx-0 xl:aspect-auto">
                                    <img class="absolute inset-0 h-full w-full rounded-2xl bg-gray-800 object-cover shadow-2xl"
                                        src="/images/qr.jpg" alt="" />
                                </div>
                            </div>
                            <div class="w-full max-w-2xl xl:max-w-none xl:flex-auto xl:px-16 xl:py-24">
                                <figure class="relative isolate pt-6 sm:pt-12">
                                    <svg viewBox="0 0 162 128" fill="none" aria-hidden="true"
                                        class="absolute left-0 top-0 -z-10 h-32 stroke-white/20">
                                        <path id="b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb"
                                            d="M65.5697 118.507L65.8918 118.89C68.9503 116.314 71.367 113.253 73.1386 109.71C74.9162 106.155 75.8027 102.28 75.8027 98.0919C75.8027 94.237 75.16 90.6155 73.8708 87.2314C72.5851 83.8565 70.8137 80.9533 68.553 78.5292C66.4529 76.1079 63.9476 74.2482 61.0407 72.9536C58.2795 71.4949 55.276 70.767 52.0386 70.767C48.9935 70.767 46.4686 71.1668 44.4872 71.9924L44.4799 71.9955L44.4726 71.9988C42.7101 72.7999 41.1035 73.6831 39.6544 74.6492C38.2407 75.5916 36.8279 76.455 35.4159 77.2394L35.4047 77.2457L35.3938 77.2525C34.2318 77.9787 32.6713 78.3634 30.6736 78.3634C29.0405 78.3634 27.5131 77.2868 26.1274 74.8257C24.7483 72.2185 24.0519 69.2166 24.0519 65.8071C24.0519 60.0311 25.3782 54.4081 28.0373 48.9335C30.703 43.4454 34.3114 38.345 38.8667 33.6325C43.5812 28.761 49.0045 24.5159 55.1389 20.8979C60.1667 18.0071 65.4966 15.6179 71.1291 13.7305C73.8626 12.8145 75.8027 10.2968 75.8027 7.38572C75.8027 3.6497 72.6341 0.62247 68.8814 1.1527C61.1635 2.2432 53.7398 4.41426 46.6119 7.66522C37.5369 11.6459 29.5729 17.0612 22.7236 23.9105C16.0322 30.6019 10.618 38.4859 6.47981 47.558L6.47976 47.558L6.47682 47.5647C2.4901 56.6544 0.5 66.6148 0.5 77.4391C0.5 84.2996 1.61702 90.7679 3.85425 96.8404L3.8558 96.8445C6.08991 102.749 9.12394 108.02 12.959 112.654L12.959 112.654L12.9646 112.661C16.8027 117.138 21.2829 120.739 26.4034 123.459L26.4033 123.459L26.4144 123.465C31.5505 126.033 37.0873 127.316 43.0178 127.316C47.5035 127.316 51.6783 126.595 55.5376 125.148L55.5376 125.148L55.5477 125.144C59.5516 123.542 63.0052 121.456 65.9019 118.881L65.5697 118.507Z" />
                                        <use href="#b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb" x="86" />
                                    </svg>
                                    <blockquote
                                        class="text-xl font-semibold leading-8 text-white sm:text-2xl sm:leading-9">
                                        <p>"Unlock the Future with QR Codes. Experience the Unimaginable – Join the
                                            Journey Today!" </p>
                                    </blockquote>
                                    <figcaption class="mt-8 text-base">
                                        <div class="font-semibold text-white">Judith Black</div>
                                        <div class="mt-1 text-gray-400">CEO of Workcation</div>
                                    </figcaption>
                                </figure>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="mt-4 xl:mt-0">
                    <QrScannerProduct :type="'post'"></QrScannerProduct>
                </div>
                <!-- Achievements -->
                <div class="flex flex-col items-center">
                    <div class="text-sm font-medium text-gray-900">Achievements</div>
                    <div class="mt-6 w-6/12" aria-hidden="true">
                        <div class="overflow-hidden rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-indigo-600" style="width: 22.5%"></div>
                        </div>
                        <div class="mt-6 hidden grid-cols-4 text-sm font-medium text-gray-600 sm:grid">
                            <div class="text-indigo-600">Sign up now!</div>
                            <div class="text-center text-indigo-600">Bronze</div>
                            <div class="text-center">Silver</div>
                            <div class="text-right">Golden</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white py-24 sm:py-32">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <h2 class="text-center text-lg font-semibold leading-8 text-gray-900">Trusted by the world’s
                            most trustworthy suppliers!</h2>
                        <div
                            class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                                src="https://tailwindui.com/img/logos/158x48/transistor-logo-gray-900.svg"
                                alt="Transistor" width="158" height="48" />
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                                src="https://tailwindui.com/img/logos/158x48/reform-logo-gray-900.svg" alt="Reform"
                                width="158" height="48" />
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                                src="https://tailwindui.com/img/logos/158x48/tuple-logo-gray-900.svg" alt="Tuple"
                                width="158" height="48" />
                            <img class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1"
                                src="https://tailwindui.com/img/logos/158x48/savvycal-logo-gray-900.svg" alt="SavvyCal"
                                width="158" height="48" />
                            <img class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1"
                                src="https://tailwindui.com/img/logos/158x48/statamic-logo-gray-900.svg" alt="Statamic"
                                width="158" height="48" />
                        </div>
                    </div>
                </div>
                <div class="bg-white">
                    <div class="relative bg-gray-900">
                        <!-- Decorative image and overlay -->
                        <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
                            <img src="https://tailwindui.com/img/ecommerce-images/home-page-01-hero-full-width.jpg"
                                alt="" class="h-full w-full object-cover object-center" />
                        </div>
                        <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50" />

                        <div
                            class="relative mx-auto flex max-w-3xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">
                            <h1 class="text-4xl font-bold tracking-tight text-white lg:text-6xl">New arrivals are here
                            </h1>
                            <p class="mt-4 text-xl text-white">The new arrivals have, well, newly arrived. Check out the
                                latest options from our summer small-batch release while they're still in stock.</p>
                            <a :href="route('products.index')"
                                class="mt-8 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">Shop
                                New Arrivals</a>
                        </div>
                    </div>
                </div>

                <!-- FAQ section -->
                <div class="mx-auto mt-32 mb-32 max-w-7xl px-6 sm:mt-24 lg:px-8">
                    <div class="mx-auto max-w-4xl divide-y divide-gray-900/10">
                        <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900">Frequently asked
                            questions</h2>
                        <dl class="mt-10 space-y-6 divide-y divide-gray-900/10">
                            <Disclosure as="div" v-for="faq in faqs" :key="faq.question" class="pt-6" v-slot="{ open }">
                                <dt>
                                    <DisclosureButton
                                        class="flex w-full items-start justify-between text-left text-gray-900">
                                        <span class="text-base font-semibold leading-7">{{ faq.question }}</span>
                                        <span class="ml-6 flex h-7 items-center">
                                            <PlusSmallIcon v-if="!open" class="h-6 w-6" aria-hidden="true" />
                                            <MinusSmallIcon v-else class="h-6 w-6" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </dt>
                                <DisclosurePanel as="dd" class="mt-2 pr-12">
                                    <p class="text-base leading-7 text-gray-600">{{ faq.answer }}</p>
                                </DisclosurePanel>
                            </Disclosure>
                        </dl>
                    </div>
                </div>
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

export default {
    name: 'Landing',

    components: {
        Layout,
        EventDiscountBanner,
        QrScannerProduct,
        NpsPopUp
    },
    props: {
        activeDiscounts: Array
    }
}

</script>

<script setup>

import { Dialog, DialogPanel, Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

const faqs = [
    {
        question: "What's the best thing about Switzerland?",
        answer:
            "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer:
            "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer:
            "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer:
            "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },

]
</script>