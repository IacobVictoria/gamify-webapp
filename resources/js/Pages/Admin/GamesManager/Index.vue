<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Panou Admin - Manager Quiz & Spânzurătoarea 🎮
            </h2>
        </template>

        <div class="px-24 py-6">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="container mx-auto px-6 py-12">
                    <h1
                        class="text-3xl font-bold text-center mb-10 text-gray-700"
                    >
                        🎮 Manager Jocuri
                    </h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Quizzes Section -->
                        <div
                            class="bg-purple-100 p-8 rounded-2xl shadow-lg flex flex-col items-center text-center transition transform hover:scale-105"
                        >
                            <img
                                :src="imagePath('/quizzes/hard-quiz.png')"
                                alt="Quizzes"
                                class="w-24 h-24 mb-4"
                            />
                            <h2 class="text-2xl font-semibold text-purple-700">
                                🧠 Quizuri
                            </h2>
                            <p class="text-gray-700 mt-2">
                                Creează quizuri interactive pentru utilizatori,
                                testează-le cunoștințele și distrează-te.
                            </p>
                            <inertia-link
                                :href="
                                    route('admin-gamification.user_quiz.index')
                                "
                                class="w-[20em] mt-6 bg-purple-600 no-underline text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-purple-700 transition duration-300"
                            >
                                📝 Creează Quiz
                            </inertia-link>
                        </div>

                        <!-- Hangman Section -->
                        <div
                            class="bg-orange-100 p-8 rounded-2xl shadow-lg flex flex-col items-center text-center transition transform hover:scale-105"
                        >
                            <img
                                :src="imagePath('/user_dashboard/joystick.png')"
                                alt="Hangman"
                                class="w-24 h-24 mb-4"
                            />
                            <h2 class="text-2xl font-semibold text-orange-700">
                                🔠 Spânzurătoarea
                            </h2>
                            <p class="text-gray-700 mt-2">
                                Jocul clasic Spânzurătoarea – dezvoltă
                                vocabularul și gândirea logică a utilizatorilor.
                            </p>
                            <inertia-link
                                :href="
                                    route(
                                        'admin-gamification.hangman_manager.index'
                                    )
                                "
                                class="w-[20em] mt-6 bg-orange-600 no-underline text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-orange-700 transition duration-300"
                            >
                                🎯 Creează Spânzurătoare
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
