<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-white leading-tight text-center bg-gradient-to-r from-teal-400 to-blue-500 p-4 rounded-lg shadow-lg"
            >
                🌱 Centrul educativ – Îmbunătățește-ți sănătatea și mintea! 🧠💪
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div
                    class="bg-white shadow-xl sm:rounded-lg p-6 border border-gray-300 backdrop-blur-md pr-32 pl-32 pb-32"
                >
                    <!-- Secțiune motivațională despre sănătate -->
                    <div
                        class="health-message text-center mb-6 p-6 text-gray-800 rounded-lg"
                    >
                        <h3 class="text-xl font-bold">
                            Sănătatea ta contează! 🌟
                        </h3>
                        <p class="mt-2 text-sm font-medium">
                            Implică-te în jocuri educative, dezvoltă-ți
                            cunoștințele și menține-ți mintea activă. O minte
                            sănătoasă duce la un corp mai sănătos! 🚀
                        </p>
                    </div>

                    <!-- QUIZZES -->
                    <inertia-link
                        :href="route('user.quizzes.index')"
                        class="no-underline"
                    >
                        <div
                            class="flex items-center justify-between learn-message bg-gray-100 p-4 rounded-lg shadow-md mb-6 transition hover:bg-gray-200"
                        >
                            <div class="text-lg font-bold text-gray-700">
                                🏆 Joacă quizuri noi! Click pe mine
                            </div>
                            <img
                                :src="imagePath('/user_dashboard/quiz.png')"
                                class="w-20 quiz-icon transition transform hover:scale-110"
                            />
                        </div>
                    </inertia-link>
                    <div class="text-sm text-gray-500 mb-6">
                        🔎 Verifică istoricul quizurilor și îmbunătățește-ți
                        cunoștințele!
                    </div>
                    <!-- HANGMAN GAME -->
                    <inertia-link
                        :href="route('user.hangman.index')"
                        class="no-underline"
                    >
                        <div
                            class="flex items-center justify-between learn-message bg-gray-100 p-4 rounded-lg shadow-md mb-6 transition hover:bg-gray-200"
                        >
                            <div class="text-lg font-bold text-gray-700">
                                🎮 Joacă Spânzurătoarea cu prietenii! Apasă aici
                            </div>
                            <img
                                :src="imagePath('/user_dashboard/joystick.png')"
                                class="w-20 joystick-icon transition transform hover:scale-110"
                            />
                        </div>
                    </inertia-link>
                    <div class="text-sm text-gray-500">
                        🕹️ Provoacă-ți prietenii și testează-ți vocabularul!
                    </div>
                    <!-- Archives of knowledge -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-2">
                            📚 Arhiva cunoștințelor
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <inertia-link
                                :href="
                                    route(
                                        'user.dashboard.explore-games.historyQuiz'
                                    )
                                "
                                class="archive-link no-underline"
                            >
                                📊 Verifică scorurile tale la quizuri
                            </inertia-link>
                            <inertia-link
                                :href="
                                    route(
                                        'user.dashboard.explore-games.historyHangman'
                                    )
                                "
                                class="archive-link no-underline"
                            >
                                🎯 Verifică scorurile tale la Spânzurătoarea
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
<style scoped>
.bg-gradient-to-r {
    background: linear-gradient(90deg, #5eead4, #3b82f6);
}
.health-message {
    border-left: 5px solid white;
}

.learn-message {
    transition: all 0.3s ease-in-out;
}

.learn-message:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.quiz-icon,
.joystick-icon {
    transition: transform 0.3s ease-in-out;
}

.quiz-icon:hover,
.joystick-icon:hover {
    transform: scale(1.1);
}

.archive-link {
    display: block;
    text-align: center;
    background: linear-gradient(90deg, #e0e7ff, #bfdbfe);
    padding: 12px;
    border-radius: 8px;
    font-weight: bold;
    color: #333;
    transition: all 0.3s ease-in-out;
}

.archive-link:hover {
    background: linear-gradient(90deg, #bfdbfe, #93c5fd);
    transform: scale(1.05);
}
</style>
