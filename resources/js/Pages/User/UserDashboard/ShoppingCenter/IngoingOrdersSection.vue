<template>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2
            class="text-3xl mb-12 font-extrabold text-gray-900 bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 rounded-lg shadow-lg flex items-center space-x-3"
        >
            <svg
                class="w-8 h-8 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4M3 12a9 9 0 1118 0A9 9 0 013 12z"
                />
            </svg>
            <span>Urmărește comenzile în curs</span>
        </h2>

        <div v-if="activeOrders.length > 0">
            <div
                v-for="order in activeOrders"
                :key="order.id"
                class="border rounded-lg p-4 mb-4 shadow-sm"
            >
                <div class="flex items-center justify-between">
                    <!-- Order ID & Date -->
                    <div>
                        <p class="text-gray-600 text-sm">
                            ID comandă:
                            <strong class="text-gray-900">{{
                                order.id
                            }}</strong>
                        </p>
                        <p class="text-gray-600 text-sm">
                            Plasată la data de:
                            <strong class="text-gray-900">{{
                                order.date
                            }}</strong>
                        </p>
                    </div>

                    <!-- Status / Actions -->
                    <div class="flex items-center space-x-4">
                        <span :class="statusClasses(order.status)">
                            {{ getStatusMessage(order) }}
                        </span>
                        <img
                            :src="getStatusImage(order.status)"
                            alt="Status Icon"
                            class="w-8 h-8"
                        />
                        <button
                            @click="toggleOrderDetails(order.id)"
                            title="Vezi detalii"
                        >
                            👁️
                        </button>
                    </div>
                </div>

                <!--DETALII COMANDĂ -->
                <div
                    v-if="expandedOrderId === order.id"
                    class="mt-4 w-full bg-gray-50 p-4 rounded-md border text-sm text-gray-700"
                >
                    <p class="font-semibold mb-2">Detalii comandă:</p>
                    <ul class="list-disc list-inside">
                        <li
                            v-for="(item, index) in order.details"
                            :key="index"
                            class="mb-1"
                        >
                            🛒 <strong>{{ item.name }}</strong> —
                            {{ item.quantity }} x {{ item.price }} RON
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500 mt-4">
            <p>Nicio comandă activă găsită.</p>
        </div>
    </div>
</template>

<script>
import { usePage } from "@inertiajs/vue3";

export default {
    props: {
        activeOrders: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            expandedOrderId: null,
        };
    },
    beforeMount() {
        const { user } = usePage().props;
        // Ascultă evenimentul OrderExpedited și actualizează statusul
        window.Echo.private(`user.${user.id}`).listen(
            ".OrderExpedited",
            (event) => {
                const order = this.activeOrders.find(
                    (order) => order.id === event.order_id
                );
                if (order) {
                    order.status = event.order_status;
                }
            }
        );

        // Ascultă evenimentul OrderDelivered și actualizează statusul
        window.Echo.private(`user.${user.id}`).listen(
            ".OrderDelivered",
            (event) => {
                const order = this.activeOrders.find(
                    (order) => order.id === event.order_id
                );
                if (order) {
                    order.status = event.order_status;
                }
            }
        );
    },
    methods: {
        getStatusMessage(order) {
            switch (order.status) {
                case "Canceled":
                    return "❌ Comandă anulată";
                case "Waiting":
                    return "⏳ În așteptare pentru a fi expediată...";
                case "Expedited":
                    return "🚚 Expediată – Așteaptă 3 zile pentru livrare...";
                case "Delivered":
                    return "✅ Comandă livrată!";
                default:
                    return "⏳ Se procesează...";
            }
        },
        statusClasses(status) {
            switch (status) {
                case "Waiting":
                    return "text-yellow-500 font-semibold";
                case "Expedited":
                    return "text-blue-500 font-semibold";
                case "Delivered":
                    return "text-green-500 font-semibold";
                default:
                    return "text-gray-500 font-semibold";
            }
        },
        getStatusImage(status) {
            switch (status) {
                case "Waiting":
                    return "/images/orders/order.png";
                case "Expedited":
                    return "/images/orders/expedited.png";
                case "Delivered":
                    return "/images/orders/delivered.png";
                default:
                    return "/images/orders/order.png";
            }
        },
        toggleOrderDetails(orderId) {
            this.expandedOrderId =
                this.expandedOrderId === orderId ? null : orderId;
        },
    },
};
</script>
