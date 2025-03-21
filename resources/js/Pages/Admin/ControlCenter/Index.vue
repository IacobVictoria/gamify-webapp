<template>
    <AuthenticatedLayout>
        <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        📊 Admin Dashboard - Control Panel 🔧
      </h2>
    </template>
        <div class="px-24 py-6">
            <div class="w-full  mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-white">
            <!-- Calendar Section -->
                    <div class="mt-8 bg-white p-8 flex flex-col md:flex-row items-center justify-between">
                        <div class="text-left w-full md:w-2/3">
                            <h3 class="text-2xl font-bold text-gray-900">📅 Plan Ahead with Your Business Calendar</h3>
                            <p class="text-lg mt-2 text-gray-700">Easily schedule orders, plan discounts, and track key
                                business
                                events in
                                a structured way.</p>
                            <p class="text-sm mt-2 text-gray-500">Optimize your workflow and never miss an important
                                date again.</p>
                        </div>
                        <div class="mt-6 md:mt-0">
                            <inertia-link :href="route('admin.calendar.index')"
                                class="bg-blue-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 inline-block">📆
                                Open Calendar</inertia-link>
                        </div>
                    </div>
                    <div class="w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 my-8 rounded-full">
                    </div>
                    <!-- Inventory Section -->
                    <div class="mt-8 bg-white p-8 flex flex-col md:flex-row items-center justify-between">
                        <div class="mt-6 md:mt-0">
                            <inertia-link :href="route('admin.inventory.index')"
                                class="bg-green-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-green-700 transition duration-300 inline-block">📦
                                Manage Inventory</inertia-link>
                        </div>
                        <div class="text-left w-full md:w-2/3">
                            <h3 class="text-2xl font-bold text-gray-900">📦 Track In & Out Stock</h3>
                            <p class="text-lg mt-2 text-gray-700">Monitor your inventory levels, track stock movements,
                                and avoid
                                shortages or overstock.</p>
                            <p class="text-sm mt-2 text-gray-500">Stay updated on what's coming in and going out,
                                ensuring a seamless
                                supply chain.</p>
                        </div>
                    </div>
                    <div class="w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 my-8 rounded-full">
                    </div>
                    <!-- Meetings Section -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Keep Your Team Updated Section -->
                        <div class="text-black p-8 flex flex-col items-center text-center">
                            <h3 class="text-2xl font-bold">🗣️ Keep Your Team Updated!</h3>
                            <p class="text-lg mt-2 text-black-100">
                                Use the Discord feature to plan reports based on multiple choices and time periods.
                            </p>
                            <p class="text-sm mt-2 text-black-200">
                                Enhance collaboration and streamline your reporting process.
                            </p>
                            <inertia-link :href="route('admin.meetings.index')"
                                class="mt-6 bg-purple-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-purple-700 transition duration-300 inline-block">
                                📢 Plan Meetings
                            </inertia-link>
                        </div>

                        <!-- NPS Survey Section -->
                        <div class="text-black p-8 flex flex-col items-center text-center">
                            <h3 class="text-2xl font-bold text-gray-900">📊 Improve Customer Experience with NPS Surveys
                            </h3>
                            <p class="text-lg mt-2 text-gray-700">
                                Create NPS surveys to measure customer loyalty and satisfaction.
                            </p>
                            <p class="text-sm mt-2 text-gray-500">
                                Identify promoters, passives, and detractors to optimize your services.
                            </p>
                            <inertia-link :href="route('admin.nps.survey.index')"
                                class="mt-6 bg-orange-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-orange-700 transition duration-300 inline-block">
                                📝 Create NPS Survey
                            </inertia-link>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    }
}</script>