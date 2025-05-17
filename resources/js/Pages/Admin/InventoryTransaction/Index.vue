<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-white">
                    <h2
                        class="text-3xl font-extrabold mb-6 flex items-center text-black"
                    >
                        📦 Panou Administrativ - Inventar
                    </h2>

                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div
                            class="bg-green-500 p-4 rounded-lg shadow-lg flex flex-col items-center"
                        >
                            <span class="text-2xl font-bold">📥 În stoc</span>
                            <span class="text-4xl">{{ totalInStock }}</span>
                        </div>
                        <div
                            class="bg-red-500 p-4 rounded-lg shadow-lg flex flex-col items-center"
                        >
                            <span class="text-2xl font-bold"
                                >📤 Ieşiri din stoc</span
                            >
                            <span class="text-4xl">{{ totalOutStock }}</span>
                        </div>
                        <div
                            class="bg-yellow-500 p-4 rounded-lg shadow-lg flex flex-col items-center"
                        >
                            <span class="text-2xl font-bold"
                                >🔄 Tranzacţii totale</span
                            >
                            <span class="text-4xl">{{
                                transactions.data.length
                            }}</span>
                        </div>
                    </div>

                    <div
                        class="flex space-x-4 mb-6 bg-white text-black p-4 rounded-lg shadow-md"
                    >
                        <div class="flex flex-col">
                            <label
                                for="transaction_type"
                                class="text-gray-700 font-semibold mb-1"
                                >📌 Tip tranzacție</label
                            >
                            <select
                                id="transaction_type"
                                v-model="filters.transaction_type"
                                class="border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white"
                            >
                                <option value="">📋 Toate tranzacțiile</option>
                                <option value="in">📥 Stock IN</option>
                                <option value="out">📤 Stock OUT</option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label
                                for="product_id"
                                class="text-gray-700 font-semibold mb-1"
                                >📦 Selectează produs</label
                            >
                            <select
                                id="product_id"
                                v-model="filters.product_id"
                                class="border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white"
                            >
                                <option value="">📦 Toate produsele</option>
                                <option
                                    v-for="product in products"
                                    :key="product.id"
                                    :value="product.id"
                                >
                                    {{ product.name }}
                                </option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label
                                for="start_date"
                                class="text-gray-700 font-semibold mb-1"
                                >📅 Data început</label
                            >
                            <input
                                type="date"
                                id="start_date"
                                v-model="filters.start_date"
                                :max="today"
                                class="border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            />
                        </div>

                        <div class="flex flex-col">
                            <label
                                for="end_date"
                                class="text-gray-700 font-semibold mb-1"
                                >📅 Data sfârșit</label
                            >
                            <input
                                type="date"
                                id="end_date"
                                v-model="filters.end_date"
                                :max="today"
                                class="border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            />
                        </div>
                        <button
                            @click="applyFilters"
                            class="bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition text-sm font-medium self-end"
                        >
                            🔍 Caută
                        </button>
                    </div>
                    <div
                        v-if="transactions.data.length === 0"
                        class="text-center p-6 bg-red-100 text-red-700 rounded-lg shadow-md"
                    >
                        <p class="text-lg font-semibold">
                            ❌ Nu există tranzacții pentru perioada selectată.
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table
                            class="w-full bg-white text-black rounded-lg shadow-lg overflow-hidden"
                        >
                            <thead>
                                <tr class="bg-gray-300">
                                    <th class="p-3">🆔 Produs</th>
                                    <th class="p-3">📌 Tip</th>
                                    <th class="p-3">📊 Cantitate</th>
                                    <th class="p-3">📅 Dată</th>
                                    <th class="p-3">📝 Descriere</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="transaction in transactions.data"
                                    :key="transaction.id"
                                    class="hover:bg-gray-100 transition cursor-pointer"
                                    @click="
                                        filterByProduct(transaction.product?.id)
                                    "
                                >
                                    <td class="p-3">
                                        {{
                                            transaction.product?.name ||
                                            "Unknown"
                                        }}
                                    </td>
                                    <td class="p-3">
                                        <span
                                            :class="
                                                transaction.transaction_type ===
                                                'in'
                                                    ? 'text-green-600'
                                                    : 'text-red-600'
                                            "
                                        >
                                            {{
                                                transaction.transaction_type.toUpperCase()
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-bold text-lg">
                                        <span
                                            :class="
                                                transaction.transaction_type ===
                                                'in'
                                                    ? 'text-green-500'
                                                    : 'text-red-500'
                                            "
                                        >
                                            {{ transaction.quantity }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        {{
                                            new Date(
                                                transaction.transaction_date
                                            ).toLocaleDateString()
                                        }}
                                    </td>
                                    <td class="p-3">
                                        {{ transaction.description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="selectedProductStats"
                        class="mt-6 p-6 bg-white text-black rounded-lg shadow-lg"
                    >
                        <h3 class="text-lg font-bold mb-4">
                            📊 Statistici pentru {{ selectedProductStats.name }}
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <strong>📥 Total IN:</strong>
                                {{ selectedProductStats.totalInStock }}
                            </li>
                            <li>
                                <strong>📤 Total OUT:</strong>
                                {{ selectedProductStats.totalOutStock }}
                            </li>
                            <li>
                                <strong>📦 Stoc Curent:</strong>
                                {{ selectedProductStats.currentStock }}
                            </li>
                            <li>
                                <strong>⏳ Prima Tranzacție:</strong>
                                {{ selectedProductStats.firstTransaction }}
                            </li>
                            <li>
                                <strong>📅 Ultima Tranzacție:</strong>
                                {{ selectedProductStats.lastTransaction }}
                            </li>
                            <li>
                                <strong>🔄 Număr Total de Tranzacții:</strong>
                                {{ selectedProductStats.totalTransactions }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { nextTick, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Chart } from "vue3-charts";

export default {
    props: {
        transactions: Object,
        filters: Object,
        total_in: Number,
        total_out: Number,
        products: Array,
        selectedProductStats: Object,
    },
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            today: new Date().toISOString().split("T")[0],
            selectedProductStock: [],
            selectedProductName: "",
        };
    },

    mounted() {
        this.chartContainer = this.$refs.chartContainer;
    },

    methods: {
        applyFilters() {
            this.$inertia.get("/admin/inventory_transaction", this.filters, {
                preserveState: true,
                replace: true,
            });
        },

        filterByProduct(productId) {
            this.filters.product_id = productId;
            this.selectedProductStock = this.transactions.data.filter(
                (tx) => tx.product?.id === productId
            );
            this.selectedProductName =
                this.products.find((p) => p.id === productId)?.name ||
                "Produs Necunoscut";
            this.applyFilters();
        },
    },
    computed: {
        totalInStock() {
            return this.total_in;
        },
        totalOutStock() {
            return this.total_out;
        },
    },
};
</script>
