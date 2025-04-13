<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 leading-tight text-center mb-6"
            >
                🧠 Quiz History
            </h2>
        </template>

        <div class="py-12 lg:px-24">
            <div class="px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-white shadow-xl rounded-xl p-8 border border-gray-200"
                >
                    <h3
                        class="text-2xl font-semibold text-[#2c3e50] text-center mb-8"
                    >
                        📚 Recapitulare & Progres 📈
                    </h3>

                    <div
                        v-if="userResults.length === 0"
                        class="text-center text-gray-500 text-lg"
                    >
                        Nicio încercare încă. Începe acum și câștigă XP! 🚀
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <inertia-link
                            v-for="result in userResults"
                            :key="result.id"
                            :href="
                                route('user.dashboard.explore-games.show', {
                                    quizId: result.quiz.id,
                                })
                            "
                            class="quiz-card hover:shadow-lg transition-all"
                        >
                            <h4 class="quiz-title">{{ result.quiz.title }}</h4>
                            <p class="quiz-score">
                                Score:
                                <span
                                    :class="getScoreColor(result.total_score)"
                                >
                                    {{ result.total_score }} 🎯
                                </span>
                            </p>
                            <p class="quiz-attempt">
                                Attempt #{{ result.attempt_number }}
                            </p>
                            <p class="quiz-date">
                                📅 {{ formatDate(result.date) }}
                            </p>
                        </inertia-link>
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
    props: {
        userResults: {
            type: Array,
            required: true,
        },
    },
    methods: {
        getScoreColor(score) {
            if (score >= 20) return "text-green";
            if (score >= 10) return "text-yellow";
            return "text-red";
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString("ro-RO", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },
    },
};
</script>
<style scoped>
.quiz-card {
    background-color: #f9fafa;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e1e8ee;
    display: flex;
    flex-direction: column;
    text-align: center;
    text-decoration: none;
    color: #2c3e50;
}

.quiz-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.quiz-score {
    font-size: 1rem;
    margin: 6px 0;
}

.quiz-attempt,
.quiz-date {
    font-size: 0.875rem;
    color: #7b8a97;
}

.text-green {
    color: #2e7d32;
    font-weight: 600;
}
.text-yellow {
    color: #f9a825;
    font-weight: 600;
}
.text-red {
    color: #d32f2f;
    font-weight: 600;
}
</style>
