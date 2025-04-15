<template>
    <div class="py-10 px-12">
        <h2 class="text-3xl font-bold text-center mb-12">
            📈 Rapoarte și Statistici
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Card 1 -->
            <div class="bg-purple-100 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-3">
                    🎮 Jocuri și Quiz-uri
                </h3>
                <ul class="space-y-2">
                    <li>🔥 Tipuri populare de quiz-uri (ușor/mediu/greu)</li>
                    <li>🔄 Quiz-uri cu cele mai multe încercări</li>
                    <li>🚀 Evoluția scorurilor utilizatorilor</li>
                    <li>📌 Quiz-uri terminate în medie</li>
                    <li>🔁 Număr mediu de reîncercări per quiz</li>
                </ul>
            </div>

            <!-- Card 2 -->
            <div class="bg-red-100 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-3">
                    🏅 Medalii și Insigne
                </h3>
                <ul class="space-y-2">
                    <li>⏰ Timp mediu până la prima insignă</li>
                    <li>🎖️ Insigne ușor vs. greu de obținut</li>
                    <li>🔖 Numărul mediu de badge-uri per categorie</li>
                </ul>
            </div>

            <!-- Card 3 centrat jos -->
            <div class="lg:col-span-2 flex justify-center">
                <div
                    class="bg-green-100 rounded-lg shadow-md p-6 w-full lg:w-1/2"
                >
                    <h3 class="text-xl font-semibold mt-6 mb-3">
                        📘 Activități și Preferințe
                    </h3>
                    <ul class="space-y-2 list-disc list-inside text-gray-800">
                        <li>📌 Număr total de activități salvate (favorite)</li>
                        <li>🏆 Top 5 activități cel mai des salvate</li>
                        <li>
                            📊 Media punctelor obținute din activitățile
                            favorite
                        </li>
                        <li>
                            🔍 Cel mai apreciat tip de activitate: dietă,
                            articol, sfat rapid
                        </li>
                        <li>
                            📈 Identificarea activităților care merită promovate
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
