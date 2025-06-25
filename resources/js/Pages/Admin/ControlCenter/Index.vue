<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               📊 Panou de Administrare 🔧
            </h2>
        </template>
        <div class="px-24 py-6">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-white">
                    <!-- Calendar Section -->
                    <div
                        class="mt-8 bg-white p-8 flex flex-col md:flex-row items-center justify-between"
                    >
                        <div class="text-left w-full md:w-2/3 mr-4">
                            <h3 class="text-2xl font-bold text-gray-900">
                                📅 Planifică din timp cu calendarul afacerii
                                tale
                            </h3>
                            <p class="text-lg mt-2 text-gray-700">
                                Programează cu ușurință comenzi, planifică
                                reduceri și urmărește evenimentele importante
                                ale afacerii într-un mod organizat.
                            </p>
                            <p class="text-sm mt-2 text-gray-500">
                                Optimizează-ți fluxul de lucru și nu mai rata
                                nicio dată importantă.
                            </p>
                        </div>
                        <div class="mt-6 md:mt-0">
                            <inertia-link
                                :href="route('admin.calendar.index')"
                                class="bg-blue-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 inline-block"
                            >
                                📆 Deschide calendarul
                            </inertia-link>
                        </div>
                    </div>

                    <div
                        class="w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 my-8 rounded-full"
                    ></div>

                    <!-- Inventory Section -->
                    <div
                        class="mt-8 bg-white p-8 flex flex-col md:flex-row items-center justify-between"
                    >
                        <div class="mt-6 md:mt-0">
                            <inertia-link
                                :href="route('admin.inventory.index')"
                                class="bg-green-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-green-700 transition duration-300 inline-block"
                            >
                                📦 Gestionează stocurile
                            </inertia-link>
                        </div>
                        <div class="text-left w-full md:w-2/3 ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">
                                📦 Urmărește stocurile intrate și ieșite
                            </h3>
                            <p class="text-lg mt-2 text-gray-700">
                                Monitorizează nivelul stocurilor, urmărește
                                mișcările și evită lipsurile sau supra-stocarea.
                            </p>
                            <p class="text-sm mt-2 text-gray-500">
                                Fii mereu la curent cu ce intră și ce iese,
                                pentru un lanț de aprovizionare fără probleme.
                            </p>
                        </div>
                    </div>

                    <div
                        class="w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 my-8 rounded-full"
                    ></div>

                    <!-- Meetings Section -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Keep Your Team Updated Section -->
                        <div
                            class="text-black p-8 flex flex-col items-center text-center"
                        >
                            <h3 class="text-2xl font-bold">
                                🗣️ Ține-ți echipa la curent!
                            </h3>
                            <p class="text-lg mt-2 text-black-100">
                                Folosește funcția de Discord pentru a planifica
                                rapoarte bazate pe selecții multiple și perioade
                                de timp.
                            </p>
                            <p class="text-sm mt-2 text-black-200">
                                Îmbunătățește colaborarea și eficientizează
                                procesul de raportare.
                            </p>
                            <inertia-link
                                :href="route('admin.meetings.index')"
                                class="mt-6 bg-purple-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-purple-700 transition duration-300 inline-block"
                            >
                                📢 Planifică întâlniri
                            </inertia-link>
                        </div>

                        <!-- NPS Survey Section -->
                        <div
                            class="text-black p-8 flex flex-col items-center text-center"
                        >
                            <h3 class="text-2xl font-bold text-gray-900">
                                📊 Îmbunătățește experiența clienților cu
                                sondaje NPS
                            </h3>
                            <p class="text-lg mt-2 text-gray-700">
                                Creează sondaje NPS pentru a măsura loialitatea
                                și satisfacția clienților.
                            </p>
                            <p class="text-sm mt-2 text-gray-500">
                                Identifică promotorii, pasivii și criticii
                                pentru a-ți optimiza serviciile.
                            </p>
                            <inertia-link
                                :href="route('admin.nps.survey.index')"
                                class="mt-10 bg-orange-600 text-white no-underline font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-orange-700 transition duration-300 inline-block"
                            >
                                📝 Creează sondaj
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        AuthenticatedLayout,
    },
};
</script>
