<template>
    <div class="py-10 px-12">
        <h2 class="text-3xl font-bold text-center mb-12">
            📈 Rapoarte și Statistici
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Activitate Utilizatori -->
            <div class="bg-blue-100 rounded-lg shadow-md p-4">
                <h3 class="text-xl font-semibold mb-3">
                    👤 Activitate Utilizatori
                </h3>
                <ul class="space-y-2">
                    <li>📅 Utilizatori noi (zilnic, săptămânal, lunar)</li>
                    <li>
                        🛒 Comenzi medii per utilizator și valoarea medie a
                        coșului
                    </li>
                    <li>💬 Wishlist, reviews, likes, comments</li>
                    <li>⏳ Timp între prima vizită și prima achiziție</li>
                    <li>🎯 Promoții vs. fără promoții</li>
                </ul>
            </div>

            <!-- Vânzări și Stocuri -->
            <div class="bg-green-100 rounded-lg shadow-md p-4">
                <h3 class="text-xl font-semibold mb-3">
                    💰 Vânzări și Stocuri
                </h3>
                <ul class="space-y-2">
                    <li>🏆 Top 10 produse vândute lunar</li>
                    <li>📉 Produse cu vânzări slabe (promoții/eliminare)</li>
                    <li>📊 Variații mari de stoc</li>
                    <li>📅 Media vânzărilor zilnice/săptămânale</li>
                    <li>⏱️ Timp până la epuizarea stocului</li>
                </ul>
            </div>
            <!-- Produse -->
            <div class="bg-yellow-100 rounded-lg shadow-md p-4">
                <h3 class="text-xl font-semibold mb-3">🛍️ Produse</h3>
                <ul class="space-y-2">
                    <li>🥇 Categorii cel mai bine vândute</li>
                    <li>📈 Produse populare (perioade)</li>
                    <li>✨ Produse noi vs. existente</li>
                    <li>🎁 Cumpărături cu/fără oferte</li>
                    <li>⭐️ Rating-uri (bun/slab)</li>
                    <li>💖 Wishlist frecvent</li>
                </ul>
            </div>

            <!-- NPS -->
            <div class="bg-indigo-100 rounded-lg shadow-md p-4">
                <h3 class="text-xl font-semibold mb-3">
                    📊 Raport NPS (Net Promoter Score)
                </h3>
                <ul class="space-y-2">
                    <li>📋 Analiză feedback lunar (PDF)</li>
                    <li>💡 Quiz-uri, hangman, promoții</li>
                </ul>
            </div>
        </div>
    </div>
</template>
