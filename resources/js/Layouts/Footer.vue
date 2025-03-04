<template>
    <footer class="bg-[#334155] text-white py-4">
        <div
            class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center px-6 text-sm text-gray-300">

            <!-- Logo + Contact -->
            <div class="flex items-center space-x-3">
                <img :src="imagePath('/Crunch.png')" alt="Company Logo" class="h-16 w-16 rounded-full shadow-lg">
                <span class="text-red-200 text-base font-semibold ">Healthy Crunch</span>
            </div>

            <!-- Contact -->
            <div class="flex items-center space-x-3">
                <img src="/images/message.png" alt="Email Icon" class="h-5 w-5">
                <span>healthy_crunch@munchies.com</span>
            </div>

            <!-- Social Icons -->
            <div class="flex space-x-4">
                <a v-for="item in navigation.social" :key="item.name" :href="item.href"
                    class="hover:text-green-400 transition">
                    <component :is="item.icon" class="h-5 w-5" aria-hidden="true" />
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-3 pt-3 text-center text-xs text-gray-400">
            &copy; 2024 Healthy Crunch. All rights reserved.
        </div>
    </footer>
</template>

<script setup>
import { defineComponent, h } from 'vue'

const navigation = {
    social: [
        {
            name: 'Facebook',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', { d: 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89h1.26v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z' }),
                    ]),
            }),
        },
        {
            name: 'Instagram',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', { d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 14.5c0 .83-.67 1.5-1.5 1.5H8.5C7.67 18 7 17.33 7 16.5v-9C7 6.67 7.67 6 8.5 6h7c.83 0 1.5.67 1.5 1.5v9zm-4.5-9a3.5 3.5 0 100 7 3.5 3.5 0 000-7z' }),
                    ]),
            }),
        },
        {
            name: 'Twitter',
            href: '#',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', { d: 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13c-2 .5-4 0-4 0s3 3 7 3 7-3 7-7v-1A4.5 4.5 0 0023 3z' }),
                    ]),
            }),
        },
    ],
};
</script>