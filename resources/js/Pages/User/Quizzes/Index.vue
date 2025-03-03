<template>
    <AuthenticatedLayout>
        <!-- 🔥 Leaderboard Section -->
        <div class="leaderboard-header animate-fade-in">
            <h2
                class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-500 font-extrabold text-3xl md:text-4xl tracking-wide">
                Hello, {{ $page.props.user.name }}! 🚀
            </h2>
            <p class="text-gray-700 text-lg font-medium mt-2">
                Ready to climb the leaderboard? Check your ranking below! 🏆
            </p>
        </div>
        <LeaderBoardQuizzes :leaderboard="leaderboard" />

        <div class="container mx-auto mt-6 space-y-12 px-4">
            <!-- 🎯 Easy Quizzes -->
            <div v-if="quizzes['easy'].length" class="space-y-6">
                <div
                    class=" flex flex-row category-header bg-green-200 shadow-md p-4 rounded-lg flex items-center justify-center gap-2">
                    <div class="flex flex-row">
                        <span class="letter-box bg-green-500 text-white">E</span>
                        <span class="letter-box bg-green-400 text-white">A</span>
                        <span class="letter-box bg-green-300 text-white">S</span>
                        <span class="letter-box bg-green-200 text-white">Y</span>
                        <h2 class="text-green-700 font-semibold text-2xl">Easy</h2>
                    </div>
                    <img :src="imagePath('/quizzes/easy-quiz.png')" class="w-12" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['easy']" :key="quiz.quizData.id" class="quiz-card quiz-easy">
                        <h3 class="quiz-title">{{ quiz.quizData.title }}</h3>
                        <p class="quiz-description">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="quiz-button bg-green-500">Let's Play</inertia-link>
                        <div v-if="quiz.is_locked" class="locked-overlay">LOCKED</div>
                    </div>
                </div>
            </div>

            <!-- 🏆 Medium Quizzes -->
            <div v-if="quizzes['medium'].length" class="space-y-6">
                <div
                    class="category-header bg-yellow-200 shadow-md p-4 rounded-lg flex items-center justify-center gap-2">
                    <div class="flex flex-row">
                        <span class="letter-box bg-yellow-500 text-white">M</span>
                        <span class="letter-box bg-yellow-400 text-white">E</span>
                        <span class="letter-box bg-yellow-300 text-white">D</span>
                        <span class="letter-box bg-yellow-200 text-white">I</span>
                        <span class="letter-box bg-yellow-100 text-white">U</span>
                        <span class="letter-box bg-yellow-50 text-white">M</span>
                        <h2 class="text-yellow-700 font-semibold text-2xl">Medium</h2>
                    </div>
                    <img :src="imagePath('/quizzes/medium-quiz.png')" class="w-12" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['medium']" :key="quiz.quizData.id" class="quiz-card quiz-medium">
                        <h3 class="quiz-title">{{ quiz.quizData.title }}</h3>
                        <p class="quiz-description">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="quiz-button bg-yellow-500">Let's Play</inertia-link>
                        <div v-if="quiz.is_locked" class="locked-overlay">LOCKED</div>
                    </div>
                </div>
            </div>

            <!-- 🔥 Hard Quizzes -->
            <div v-if="quizzes['hard'].length" class="space-y-6">
                <div class="category-header bg-red-200 shadow-md p-4 rounded-lg flex items-center justify-center gap-2">
                    <div class="flex flex-row">
                        <span class="letter-box bg-red-500 text-white">H</span>
                        <span class="letter-box bg-red-400 text-white">A</span>
                        <span class="letter-box bg-red-300 text-white">R</span>
                        <span class="letter-box bg-red-200 text-white">D</span>
                        <h2 class="text-red-700 font-semibold text-2xl">Hard</h2>
                    </div>
                    <img :src="imagePath('/quizzes/hard-quiz.png')" class="w-12" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="quiz in quizzes['hard']" :key="quiz.quizData.id" class="quiz-card quiz-hard">
                        <h3 class="quiz-title">{{ quiz.quizData.title }}</h3>
                        <p class="quiz-description">{{ quiz.quizData.description }}</p>
                        <inertia-link :href="route('user.quiz.show', { quizId: quiz.quizData.id })"
                            class="quiz-button bg-red-500">Let's Play</inertia-link>
                        <div v-if="quiz.is_locked" class="locked-overlay">LOCKED</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LeaderBoardQuizzes from './LeaderBoardQuizzes.vue';
export default {
    components: {
        AuthenticatedLayout
    },

    props: {
        quizzes: {
            type: Array,
            required: true
        },
        leaderboard: {
            type: Array,
            required: true
        }
    },
    components: {
        LeaderBoardQuizzes,
        AuthenticatedLayout
    },
    watch: {
        quizzes: {
            deep: true,
            immediate: true,
            handler: (newQuizzes, oldQuizzes) => {
                //console.log("Quizzes changed:", newQuizzes, oldQuizzes);
            }
        }
    },
}
</script>
<style scoped>
/* 🏆 Carduri quiz mai mari */
.quiz-card {
    width: 350px;
    /* Mărit dimensiunea cardurilor */
    height: 250px;
    /* Ajustat pentru mai mult conținut */
    border-radius: 25px;
    /* Colțuri și mai rotunjite */
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
    position: relative;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* 🌟 Hover Effect */
.quiz-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

/* 💡 Culori pastel pentru dificultăți */
.quiz-easy {
    background: #bbf7d0;
    /* Verde pastel */
}

.quiz-medium {
    background: #fef08a;
    /* Galben pastel */
}

.quiz-hard {
    background: #fecaca;
    /* Roșu pastel */
}

/* 🏆 Titlu quiz */
.quiz-title {
    font-size: 1.4rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

/* 📜 Descrierea quizului */
.quiz-description {
    font-size: 1rem;
    color: #555;
    margin-bottom: 12px;
}

/* 🏅 Badge pentru dificultate */
.quiz-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 18px;
    font-size: 0.9rem;
    font-weight: bold;
    color: white;
    margin-bottom: 12px;
}

/* Culoarea badge-ului în funcție de dificultate */
.quiz-easy .quiz-badge {
    background: #16a34a;
}

.quiz-medium .quiz-badge {
    background: #eab308;
}

.quiz-hard .quiz-badge {
    background: #dc2626;
}

/* 🎮 Butonul "Let's Play" */
.quiz-button {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: bold;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
}

.quiz-button:hover {
    transform: scale(1.05);
    box-shadow: 3px 3px 12px rgba(0, 0, 0, 0.25);
}

/* Culoarea butonului în funcție de dificultate */
.quiz-easy .quiz-button {
    background: #16a34a;
}

.quiz-medium .quiz-button {
    background: #eab308;
}

.quiz-hard .quiz-button {
    background: #dc2626;
}

/* 🔒 Locked Overlay */
.locked-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    font-weight: bold;
    border-radius: 25px;
}
/* 🌟 Animare de apariție */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

/* 🎨 Stilizare pentru header-ul leaderboard */
.leaderboard-header {
    text-align: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    margin-top: 4em;

}

/* 🔥 Gradient pe text pentru un efect premium */
.bg-clip-text {
    -webkit-text-fill-color: transparent;
}


</style>
