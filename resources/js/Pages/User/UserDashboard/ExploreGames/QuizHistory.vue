<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                🎮 Your Quiz History 🏆
            </h2>
        </template>

        <div class="py-12 ">
            <div class="max-w-[90%] mx-auto sm:px-6">
                <div class="bg-gradient-to-r from-pink-300 to-red-300 text-white shadow-2xl sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-3 text-center">✨ Your Achievements ✨</h3>
                    <div class="flex flex-row items-center justify-end learn-message mb-12">
                        <div class="text-lg font-bold text-white pr-4">
                            Learn from your mistakes - Click on the quizzes!
                        </div>
                        <img :src="imagePath('/user_dashboard/search.png')" class="w-32 search-icon" />
                    </div>

                    <div v-if="userResults.length === 0" class="text-center text-gray-100 text-lg">
                        No quiz history found. 🚀 Start your journey now!
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <inertia-link v-for="result in userResults" :key="result.id"
                            :href="route('user.dashboard.explore-games.show', { quizId: result.quiz.id })"
                            class="quiz-card">
                            <h4 class="quiz-title">{{ result.quiz.title }}</h4>
                            <p class="quiz-score">
                                Score: <span :class="getScoreColor(result.total_score)">{{ result.total_score }}
                                    🎯</span>
                            </p>
                            <p class="quiz-attempt">
                                Attempt #{{ result.attempt_number }}
                            </p>
                            <p class="quiz-date">📅 {{ formatDate(result.date) }}</p>
                        </inertia-link>
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
    props: {
        userResults: {
            type: Array,
            required: true
        }
    },
    methods: {
        getScoreColor(score) {
            if (score >= 20) return 'text-green-300 font-bold';
            if (score >= 10) return 'text-yellow-300 font-bold';
            return 'text-red-300 font-bold';
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        }
    }
};
</script>

<style scoped>
/* ✅ Adăugăm un aspect mai gamificat */
.quiz-card {
    background: rgba(255, 255, 255, 0.15); /* Transparent White */
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); /* Mai mult shadow pentru vizibilitate */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    text-decoration: none;
    color: white;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    backdrop-filter: blur(10px); /* Blur pentru un efect modern */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Bordură subtilă pentru contrast */
}

/* Efecte de hover */
.quiz-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(12px); /* Blur mai accentuat la hover */
}


/* 🏆 Titlu quiz */
.quiz-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: white;
    margin-bottom: 5px;
}

/* 🌟 Scor & detalii */
.quiz-score {
    font-size: 1rem;
    color: white;
    margin: 5px 0;
}

.quiz-attempt,
.quiz-date {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* ✅ Adăugăm un fundal vibrant */
.bg-gradient-to-r {
    border-radius: 15px;
    padding: 20px;
    text-align: center;
}

/* Stil pentru iconiță */
.search-icon {
    animation: float 3s ease-in-out infinite;
}

/* Animație pentru a face iconița să se miște ușor */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
}

</style>
