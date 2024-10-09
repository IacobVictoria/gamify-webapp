<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <div>
                <h2>Dashboard</h2>
                <p>Total Orders: {{ numberOfOrders }}</p> <inertia-link :href="route('user.order_history.index')"
                    method="get">View your orders</inertia-link>
                <canvas id="topProductsChart"></canvas>
                <div>
                    <h1>Evolution Score</h1>
                    <canvas id="scoreChart"></canvas>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Chart from 'chart.js/auto';

export default {
    props: {
        numberOfOrders: Number,
        chartData: Object,
        scoreEvolution: Array,
    },
    components: {
        AuthenticatedLayout
    },
    mounted() {
        console.log("Chart Data:", this.chartData);
        console.log("Score Evolution:", this.scoreEvolution);
        this.topProductsChart();
        this.evolutionChart();
    },
    methods: {
        topProductsChart() {
            const ctx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.chartData.labels,
                    datasets: [{
                        label: 'Top Products Purchased',
                        data: this.chartData.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        },

        evolutionChart() {
            const ctx = document.getElementById('scoreChart').getContext('2d');

            
            const labels = this.scoreEvolution.map(data => data.date);
            console.log('date',labels);
            const scores = this.scoreEvolution.map(data => data.score);
            console.log('score',scores);
            console.log(Math.max(...scores));
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Evoluția Scorului',
                        data: scores,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                    }],
                },
                options: {
                    scales: {
                        y: {
                    beginAtZero: true,
                    max: Math.max(...scores)*1.2                }   
                    }
                }
            });
        }
    },
};
</script>
