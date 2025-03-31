<template>
    <header class="fixed inset-x-0 top-0 z-50 bg-white transition-shadow duration-300"
        :class="{ 'shadow-lg': hasShadow }">
        <!-- small display -->
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5">
                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                        alt="" />
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                    @click="mobileMenuOpen = true">
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
            <!-- large display -->
            <div class="hidden lg:flex lg:gap-x-12">
                <a v-for="item in navigation" :key="item.name" :href="item.href"
                    class="text-sm font-semibold leading-6 text-gray-900 heading-underline transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-400 cursor: pointer">
                    {{ item.name }}
                </a>
            </div>

            <div class="hidden lg:flex lg:flex-1 lg:justify-end gap-x-4">
                <!-- Dacă utilizatorul este autentificat, afișează dropdown-ul cu avatar și nume -->
                <div v-if="isLoggedIn()" class="relative">
                    <div class="flex gap-2 items-center">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2">
                            <img v-if="user.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                                class="w-14 h-14 rounded-full" />
                            <img v-else src="/images/female.png" alt="User Avatar" class="w-14 h-14 rounded-full" />
                            <span class="font-semibold">{{ user.name }}</span>
                        </button>
                        <div v-if="authUserHasRole('User')" class="flex gap-2 items-center">
                            <inertia-link :href="route('user.wishlist.index')">
                                <WishlistLogoSVG></WishlistLogoSVG>
                            </inertia-link>
                            <RecomandationLogoSVG></RecomandationLogoSVG>
                            <NotificationComponentIcon></NotificationComponentIcon>
                        </div>
                    </div>
                    <!-- Dropdown -->
                    <div v-if="dropdownOpen"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                        <template v-if="authUserHasRole('Admin')">
                            <inertia-link :href="route('admin.dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Admin Dashboard
                            </inertia-link>
                        </template>
                        <template v-if="authUserHasRole('Admin-Gamification')">
                            <inertia-link :href="route('admin-gamification.dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Admin Gamify Dashboard
                            </inertia-link>
                        </template>
                        <template v-if="authUserHasRole('User')">
                            <inertia-link :href="route('user.dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                User Dashboard
                            </inertia-link>
                        </template>
                        <template v-if="authUserHasRole('Super-Admin')">
                            <inertia-link :href="route('super-admin.dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Super Admin Dashboard
                            </inertia-link>
                        </template>
                        <inertia-link :href="route('user.shopping-cart.index')" method="get"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Shopping Cart
                        </inertia-link>
                        <inertia-link :href="route('logout')" method="post"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Log out
                        </inertia-link>
                    </div>
                </div>

                <!-- Dacă utilizatorul nu este autentificat, afișează butoanele Log in / Sign up -->
                <template v-else>
                    <inertia-link :href="route('login')"
                        class="text-[#075985] font-semibold py-2 px-4 rounded-lg shadow-md bg-transparent hover:bg-[#075985] hover:text-white transition-colors duration-300 px-4 py-2 rounded-md cursor: pointer">
                        Log in
                    </inertia-link>
                    <inertia-link :href="route('register')"
                        class="bg-[#075985] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-white hover:text-[#075985] transition-colors cursor: pointer">
                        Sign up
                    </inertia-link>
                </template>
            </div>
        </nav>

        <!-- dialog panel with buttons for small screen -->
        <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
            <div class="fixed inset-0 z-10" />
            <DialogPanel
                class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                            alt="" />
                    </a>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <inertia-link v-for="item in navigation" :key="item.name" :href="item.href"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 cursor: pointer">
                                {{ item.name }}
                            </inertia-link>
                        </div>
                        <div class="py-6">
                            <inertia-link href="#"
                                class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 cursor: pointer">
                                Log in
                            </inertia-link>
                            <inertia-link href="#"
                                class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 cursor: pointer">
                                Sign up
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </DialogPanel>
        </Dialog>
    </header>
</template>

<style>
.heading-underline::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background-color: #e6092d;
    transition: width 0.3s ease-in-out;
    margin-top: 4px;
}

.heading-underline:hover::after {
    width: 100%;
}
</style>

<script>
export default {
    name: 'Header',

    data() {
        return {
            hasShadow: false,
            dropdownOpen: false,
        }
    },

    computed: {
        user() {
            return this.$page.props.user;
        },

    },

    methods: {
        handleScroll() {
            this.hasShadow = window.scrollY > 0;
        },
    },

    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },

    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    },
}
</script>

<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
import WishlistLogoSVG from '@/Components/WishlistLogoSVG.vue';
import RecomandationLogoSVG from '@/Components/RecomandationLogoSVG.vue';
import NotificationComponentIcon from '@/Pages/Notification_System/NotificationComponentIcon.vue';

const navigation = [
    { name: 'Home', href: route('home') },
    { name: 'Products', href: route('products.index') },
    { name: 'Suppliers', href: route('suppliers.web_view') },
    { name: 'Activities', href: route('activities.index') },
]

const mobileMenuOpen = ref(false)
</script>
