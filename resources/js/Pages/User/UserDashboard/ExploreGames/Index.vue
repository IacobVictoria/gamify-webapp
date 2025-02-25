<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-white leading-tight text-center bg-gradient-to-r from-teal-400 to-blue-500 p-4 rounded-lg shadow-lg">
                🌱 Educative Center - Boost Your Health & Mind! 🧠💪
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div
                    class="bg-white shadow-xl sm:rounded-lg p-6 border border-gray-300 backdrop-blur-md pr-32 pl-32 pb-32">

                    <!-- 🩺 Secțiune motivațională despre sănătate -->
                    <div class="health-message text-center mb-6 p-6  text-gray-800 rounded-lg ">
                        <h3 class="text-xl font-bold">Your Health Matters! 🌟</h3>
                        <p class="mt-2 text-sm font-medium">
                            Engage in educational games, boost your knowledge, and keep your brain active.
                            A healthy mind leads to a healthier body! 🚀
                        </p>
                    </div>

                    <!-- 🎯 QUIZZES -->
                    <inertia-link :href="route('user.quizzes.index')" class="no-underline">
                        <div
                            class="flex items-center justify-between learn-message bg-gray-100 p-4 rounded-lg shadow-md mb-6 transition hover:bg-gray-200">
                            <div class="text-lg font-bold text-gray-700">
                                🏆 Play new quizzes! Click me
                            </div>
                            <img :src="imagePath('/user_dashboard/quiz.png')"
                                class="w-20 quiz-icon transition transform hover:scale-110" />

                        </div>
                    </inertia-link>
                    <div class="text-sm text-gray-500 mb-6">🔎 Check your quiz history and improve your knowledge!</div>

                    <!-- 🎮 HANGMAN GAME -->
                    <inertia-link :href="route('user.hangman.index')" class="no-underline">
                        <div
                            class="flex items-center justify-between learn-message bg-gray-100 p-4 rounded-lg shadow-md mb-6 transition hover:bg-gray-200 ">
                            <div class="text-lg font-bold text-gray-700">
                                🎮 Play Hangman with friends! Click me
                            </div>

                            <img :src="imagePath('/user_dashboard/joystick.png')"
                                class="w-20 joystick-icon transition transform hover:scale-110" />

                        </div>
                    </inertia-link>
                    <div class="text-sm text-gray-500">🕹️ Challenge your friends and test your vocabulary skills!</div>

                    <!-- 📖 Archives of knowledge -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-2">📚 Archives of knowledge</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <inertia-link :href="route('user.dashboard.explore-games.historyQuiz')"
                                class="archive-link">
                                📊 Check your Quiz scores
                            </inertia-link>
                            <inertia-link :href="route('user.dashboard.explore-games.historyQuiz')"
                                class="archive-link">
                                🎯 Check your Hangman scores
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
    },

}
</script>
<style scoped>
/* 🌱 Titlu cu gradient pentru header */
.bg-gradient-to-r {
    background: linear-gradient(90deg, #5eead4, #3b82f6);
}

/* 🩺 Secțiunea despre sănătate */
.health-message {
    border-left: 5px solid white;
}

/* 🏆 Secțiuni interactive */
.learn-message {
    transition: all 0.3s ease-in-out;
}

.learn-message:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

/* 🎯 Imagini stilizate */
.quiz-icon,
.joystick-icon {
    transition: transform 0.3s ease-in-out;
}

.quiz-icon:hover,
.joystick-icon:hover {
    transform: scale(1.1);
}

/* 🎨 Linkuri stilizate */
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
