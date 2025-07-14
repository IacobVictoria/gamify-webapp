<template>
    <div class="flex gap-4 items-center mb-6">
        <!-- Copy Link -->
        <button
            @click="copyClipBoard(currentUrl)"
            class="flex items-center gap-2 bg-emerald-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-emerald-600 active:bg-emerald-700 transition"
        >
            <span>📋</span>
            <span>Copiază link</span>
        </button>

        <!-- Send to Friend -->
        <button
            @click="showFriendModal = true"
            class="flex items-center gap-2 bg-indigo-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-indigo-600 transition"
        >
            <span>📨</span>
            <span>Trimite unui prieten</span>
        </button>
    </div>

    <div
        v-if="showPopup"
        class="fixed top-5 right-5 z-50 bg-green-500 text-black px-6 py-3 rounded-lg shadow-lg flex items-center gap-2"
    >
        ✅ URL copiat cu succes!
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-200 mt-12">
            <thead>
                <tr class="bg-gray-100">
                    <th
                        class="bg-white border border-gray-200 p-4 text-left font-semibold"
                    >
                        Produs
                    </th>
                    <th
                        v-for="(product, index) in products"
                        :key="index"
                        class="bg-white border border-gray-200 p-4 text-center font-semibold"
                    >
                        {{ product.name }}
                        <img
                            :src="product.image_url"
                            :alt="product.imageAlt"
                            class="object-cover object-center"
                        />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="attribute in attributes" :key="attribute.key">
                    <td
                        class="border border-gray-200 p-4 font-medium bg-gray-50 text-left text-sm"
                    >
                        <span class="inline-flex items-center gap-2 w-48">
                            🧾 {{ attribute.label }}
                        </span>
                    </td>

                    <td
                        v-for="(product, index) in products"
                        :key="index"
                        :class="
                            getComparisonClass(
                                attribute.key,
                                product[attribute.key],
                                product.comparison
                            )
                        "
                        class="border border-gray-200 p-4 text-center"
                    >
                        <span v-if="attribute.key === 'ingredients'">
                            <div
                                v-for="(ingredient, idx) in product.comparison
                                    .commonIngredients"
                                class="text-green-700 flex items-center gap-1 text-sm font-medium"
                                :key="idx"
                            >
                                ✅ {{ ingredient }}
                            </div>
                            <div
                                v-for="(ingredient, idx) in product.comparison
                                    .nonCommonIngredients"
                                class="text-gray-600 flex items-center gap-1 text-sm"
                                :key="idx"
                            >
                                ❌ {{ ingredient }}
                            </div>
                            <div
                                v-if="
                                    product.comparison.commonIngredients
                                        .length === 0 &&
                                    product.comparison.nonCommonIngredients
                                        .length === 0
                                "
                                class="text-gray-400 text-sm"
                            >
                                ❌ Nu sunt date
                            </div>
                        </span>
                        <span
                            v-if="
                                product[attribute.key] != null &&
                                attribute.key !== 'ingredients'
                            "
                        >
                            {{
                                formatValue(
                                    attribute.key,
                                    product[attribute.key]
                                )
                            }}
                        </span>
                        <span
                            v-if="product[attribute.key] === null"
                            class="text-gray-400 text-sm"
                            >❌</span
                        >
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Recomandări -->
        <div
            class="mt-6 p-6 border border-gray-300 rounded-xl bg-white shadow-sm"
        >
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                🧠 Recomandări inteligente
            </h2>
            <ul class="space-y-2">
                <li
                    v-for="(desc, index) in recommendations"
                    :key="index"
                    class="flex items-start gap-3 text-gray-700 text-sm"
                >
                    <span>🌟</span>
                    <span>{{ desc }}</span>
                </li>
            </ul>
        </div>
    </div>
    <FriendSelector
        v-if="showFriendModal"
        :friends="friends"
        @close="showFriendModal = false"
        @send="sendToFriend"
    />
</template>

<script>
import FriendSelector from "@/Components/FriendSelector.vue";
import Swal from "sweetalert2";

export default {
    props: {
        products: {
            type: Array,
            required: true,
        },
        recommendations: {
            type: Array,
            required: true,
        },
        friends: {
            type: Array,
        },
    },
    data() {
        return {
            showFriendModal: false,
            currentUrl: window.location.href,
            showPopup: false,
            friends: this.friends,
        };
    },
    components: {
        FriendSelector,
    },
    computed: {
        attributes() {
            return [
                { key: "price", label: "Preț (RON)" },
                { key: "calories", label: "Calorii (kcal)" },
                { key: "protein", label: "Proteine (g)" },
                { key: "carbs", label: "Carbohidrați (g)" },
                { key: "fats", label: "Grăsimi (g)" },
                { key: "fiber", label: "Fibre (g)" },
                { key: "sugar", label: "Zahăr (g)" },
                { key: "ingredients", label: "Ingrediente" },
                { key: "allergens", label: "Alergeni" },
            ];
        },
    },
    methods: {
        formatValue(key, value) {
            if (key === "price") {
                return `${value} RON`;
            }
            return value;
        },

        getComparisonClass(attribute, value, comparison) {
            if (!comparison) return "";

            if (comparison[`${attribute}Color`] === "red") {
                return "bg-red-100";
            }
            if (comparison[`${attribute}Color`] === "blue") {
                return "bg-blue-100";
            }
            return "";
        },

        copyClipBoard(currentUrl) {
            navigator.clipboard
                .writeText(currentUrl.trim())
                .then(() => {
                    this.showPopup = true;
                    setTimeout(() => {
                        this.showPopup = false;
                    }, 2000);
                })
                .catch((error) => {
                    console.error("Nu s-a putut copia în clipboard: ", error);
                });
        },

        async sendToFriend(friendId) {
            const message = `📊 Vezi această comparatie:${this.currentUrl}`;
            await axios.post(`/user/user_chat/messages/${friendId}`, {
                message: message,
            });
            this.showFriendModal = false;
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Link-ul comparației a fost trimis!",
                text: "Prietenul tău a primit linkul cu comparația.",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        },
    },
};
</script>

<style scoped>
thead tr {
    background: linear-gradient(to right, #f0f9ff, #e0f7fa);
}

.bg-red-100 {
    background-color: #fee2e2;
}

.bg-blue-100 {
    background-color: #dbeafe;
}

.font-semibold {
    font-weight: 600;
}

.text-green-600 {
    color: #16a34a;
}

.animate-bounce {
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}
</style>
