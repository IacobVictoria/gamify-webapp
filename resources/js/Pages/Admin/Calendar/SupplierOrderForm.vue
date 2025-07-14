<template>
    <div>
        <div class="flex justify-between mb-4">
            <button
                v-if="!showFavorites"
                class="btn-favorites"
                @click="toggleFavorites"
            >
                ⭐ Favorite
            </button>
            <button
                v-if="showFavorites"
                class="btn-back"
                @click="toggleFavorites"
            >
                🔙 Înapoi
            </button>
        </div>

        <!-- Afișează lista de comenzi favorite dacă showFavorites = true -->
        <div v-if="showFavorites">
            <FavoritesEvents
                :favorites="favoriteCommands"
                :selected-date="selectedDate"
                :type="'Comenzi'"
            >
            </FavoritesEvents>
        </div>
        <div v-else>
            <h3 class="text-base font-semibold text-gray-900">
                Creează Comandă
            </h3>

            <form @submit.prevent="submitForm">
                <!-- Selectare data -->
                <div>
                    <label
                        for="eventDate"
                        class="block text-sm font-medium text-gray-700"
                        >Data Evenimentului</label
                    >
                    <input
                        v-model="formData.start"
                        type="date"
                        id="eventDate"
                        class="input mt-2"
                    />
                </div>

                <!-- Input pentru titlul comenzii -->
                <input
                    v-model="formData.title"
                    type="text"
                    placeholder="Introdu titlul comenzii"
                    class="input mt-2"
                    required
                />

                <!-- Input pentru detalii comanda -->
                <input
                    v-model="formData.description"
                    type="text"
                    placeholder="Introdu detalii comandă"
                    class="input mt-2"
                    required
                />

                <!-- Dropdown pentru furnizori -->
                <div class="mt-2">
                    <label
                        for="supplier"
                        class="block text-sm font-medium text-gray-700"
                        >Selectează Furnizor</label
                    >
                    <select
                        v-model="formData.supplierId"
                        id="supplier"
                        class="input mt-2"
                        required
                    >
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.name }}
                        </option>
                    </select>
                </div>

                <!-- Dropdown pentru produse -->
                <div v-if="selectedProducts.length > 0" class="mt-2">
                    <label
                        for="product"
                        class="block text-sm font-medium text-gray-700"
                        >Selectează Produse</label
                    >
                    <select
                        v-model="selectedProductIds"
                        multiple
                        size="5"
                        id="product"
                        class="input mt-2"
                        required
                    >
                        <option
                            v-for="product in selectedProducts"
                            :key="product.id"
                            :value="product.id"
                        >
                            {{ product.name }}
                        </option>
                    </select>
                </div>

                <!-- Input pentru cantitatea fiecărui produs selectat -->
                <div v-if="selectedProductIds.length > 0" class="mt-2">
                    <label class="block text-sm font-medium text-gray-700"
                        >Cantități Produse</label
                    >
                    <div
                        v-for="(productId, index) in selectedProductIds"
                        :key="index"
                        class="mt-2"
                    >
                        <div class="flex items-center justify-between">
                            <span>{{
                                selectedProducts.find(
                                    (product) => product.id === productId
                                )?.name
                            }}</span>
                            <input
                                type="number"
                                v-model="formData.productQuantities[productId]"
                                :placeholder="
                                    'Cantitate ' +
                                    selectedProducts.find(
                                        (product) => product.id === productId
                                    )?.name
                                "
                                class="input w-1/3"
                                min="1"
                                :max="getMaxQuantity(productId)"
                                @blur="validateQuantity(productId)"
                            />
                        </div>
                        <p
                            v-if="formErrors[`quantity_${productId}`]"
                            class="text-red-500 text-sm"
                        >
                            {{ formErrors[`quantity_${productId}`] }}
                        </p>
                    </div>
                </div>

                <div class="mt-2">
                    <label class="flex gap-4 items-center">
                        <span class="ml-2 text-sm font-medium text-gray-700"
                            >Eveniment recurent</span
                        >
                        <input
                            type="checkbox"
                            v-model="formData.is_recurring"
                        />
                    </label>
                </div>

                <div v-if="formData.is_recurring" class="mt-2">
                    <label class="block text-sm font-medium text-gray-700"
                        >Interval de recurență</label
                    >

                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center">
                            <input
                                type="radio"
                                v-model="formData.recurring_interval"
                                value="weekly"
                                class="mr-2"
                            />
                            Săptămânal
                        </label>
                        <label class="flex items-center">
                            <input
                                type="radio"
                                v-model="formData.recurring_interval"
                                value="monthly"
                                class="mr-2"
                            />
                            Lunar
                        </label>
                    </div>
                </div>
                <!-- Butoane submit și close -->
                <div class="mt-5 sm:mt-6">
                    <button
                        type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                        Trimite
                    </button>

                    <button
                        type="button"
                        class="inline-flex w-full justify-center mt-3 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200"
                        @click="closeForm"
                    >
                        Închide
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, ref, watch, nextTick } from "vue";
import FavoritesEvents from "./FavoritesEvents.vue";
defineOptions({
    name: "CreateCommand",
});

const props = defineProps({
    selectedDate: String,
    suppliers: Array,
    products: Array,
    favoriteCommands: Array, // Comenzile favorite din backend
});

const showFavorites = ref(false);
const formErrors = ref({});
const formData = useForm({
    title: "",
    start: props.selectedDate ? props.selectedDate.split("T")[0] : "",
    end: props.selectedDate ? props.selectedDate.split("T")[0] : "",
    type: "supplier_order",
    description: "",
    supplierId: null,
    supplierName: "",
    calendarId: "personal",
    productIds: [],
    productQuantities: {},
    details: "",
    is_recurring: false,
    recurring_interval: null, //  "weekly" sau "monthly"
});

const selectedProducts = ref([]);
const selectedProductIds = ref([]);
const quantityError = ref("");

watch(
    () => formData.supplierId,
    (newSupplierId) => {
        if (newSupplierId && props.products[newSupplierId]) {
            selectedProducts.value = props.products[newSupplierId];
            const selectedSupplier = props.suppliers.find(
                (supplier) => supplier.id === newSupplierId
            );
            formData.supplierName = selectedSupplier
                ? selectedSupplier.name
                : "";
        } else {
            selectedProducts.value = [];
            formData.supplierName = "";
        }
    }
);

function getMaxQuantity(productId) {
    const product = selectedProducts.value.find(
        (product) => product.id === productId
    );
    return product ? product.stock : 1;
}

function validateForm() {
    let valid = true;
    formErrors.value = {};
    quantityError.value = "";

    selectedProductIds.value.forEach((productId) => {
        const product = selectedProducts.value.find((p) => p.id === productId);
        const quantity = formData.productQuantities[productId];

        if (!quantity || quantity < 1) {
            formErrors.value[`quantity_${productId}`] =
                "Introduceți o cantitate validă.";
            valid = false;
        } else if (quantity > product.stock) {
            formErrors.value[
                `quantity_${productId}`
            ] = `Maxim ${product.stock} disponibile.`;
            valid = false;
        }
    });

    return valid;
}

const emits = defineEmits(["closeForm"]);

function closeForm() {
    emits("closeForm");
}

function submitForm() {
    if (!validateForm()) return;

    // mapeaza product id cu quantity
    const productsWithQuantities = selectedProductIds.value.map((productId) => {
        const product = selectedProducts.value.find(
            (product) => product.id === productId
        );
        return {
            productId,
            productName: product ? product.name : "",
            quantity: formData.productQuantities[productId] || 1,
        };
    });

    formData.productQuantities = productsWithQuantities;
    const details = {
        supplier: formData.supplierId,
        supplierName: formData.supplierName,
        productQuantities: formData.productQuantities,
    };

    formData.details = JSON.stringify(details);

    formData.post(route("admin.calendar.event.store"), {
        onSuccess: () => {
            window.location.reload();
            closeForm();
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        },
    });
}
function toggleFavorites() {
    showFavorites.value = !showFavorites.value;
}
</script>

<style scoped>
.input {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
