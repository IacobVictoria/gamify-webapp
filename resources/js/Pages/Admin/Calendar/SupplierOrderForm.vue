<template>
    <div>
        <div class="flex justify-between mb-4">
            <button v-if="!showFavorites" class="btn-favorites" @click="toggleFavorites">⭐ Favorites</button>
            <button v-if="showFavorites" class="btn-back" @click="toggleFavorites">🔙 Back</button>
        </div>

        <!-- Afișează lista de comenzi favorite dacă showFavorites = true -->
        <div v-if="showFavorites">
            <FavoritesEvents :favorites="favoriteCommands" :selected-date="selectedDate" :type="'Commands'"></FavoritesEvents>
        </div>
        <div v-else>
            <h3 class="text-base font-semibold text-gray-900">Create Command</h3>

            <!-- Selectare data -->
            <div>
                <label for="eventDate" class="block text-sm font-medium text-gray-700">Event Date</label>
                <input v-model="formData.start" type="date" id="eventDate" class="input mt-2" />
            </div>

            <!-- Input pentru titlul comenzii -->
            <input v-model="formData.title" type="text" placeholder="Enter Command Title" class="input mt-2" />

            <!-- Input pentru detalii comanda -->
            <input v-model="formData.description" type="text" placeholder="Enter Command Details" class="input mt-2" />

            <!-- Dropdown pentru furnizori -->
            <div class="mt-2">
                <label for="supplier" class="block text-sm font-medium text-gray-700">Select Supplier</label>
                <select v-model="formData.supplierId" id="supplier" class="input mt-2">
                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                        {{ supplier.name }}
                    </option>
                </select>
            </div>

            <!-- Dropdown pentru produse -->
            <div v-if="selectedProducts.length > 0" class="mt-2">
                <label for="product" class="block text-sm font-medium text-gray-700">Select Products</label>
                <select v-model="selectedProductIds" multiple size="5" id="product" class="input mt-2">
                    <option v-for="product in selectedProducts" :key="product.id" :value="product.id">
                        {{ product.name }}
                    </option>
                </select>
            </div>

            <!-- Input pentru cantitatea fiecărui produs selectat -->
            <div v-if="selectedProductIds.length > 0" class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Product Quantities</label>
                <div v-for="(productId, index) in selectedProductIds" :key="index" class="mt-2">
                    <div class="flex items-center justify-between">
                        <span>{{selectedProducts.find(product => product.id === productId)?.name}}</span>
                        <input type="number" v-model="formData.productQuantities[productId]"
                            :placeholder="'Quantity for ' + selectedProducts.find(product => product.id === productId)?.name"
                            class="input w-1/3" min="1" :max="getMaxQuantity(productId)"
                            @blur="validateQuantity(productId)" />
                    </div>
                </div>
            </div>

            <!-- Eroare de cantitate -->
            <div v-if="quantityError" class="text-red-500 text-sm mt-2">
                {{ quantityError }}
            </div>

            <!-- Butoane submit și close -->
            <div class="mt-5 sm:mt-6">
                <button type="button"
                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    @click="submitForm">
                    Submit
                </button>

                <button type="button"
                    class="inline-flex w-full justify-center mt-3 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200"
                    @click="closeForm">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import FavoritesEvents from './FavoritesEvents.vue';
defineOptions({
    name: "CreateCommand"
});

const props = defineProps({
    selectedDate: String,
    suppliers: Array,
    products: Array,
    favoriteCommands: Array // Comenzile favorite din backend
});

const showFavorites = ref(false);

const formData = useForm({
    title: '',
    start: props.selectedDate,
    end: props.selectedDate,
    type: 'supplier_order',
    description: '',
    supplierId: null,
    supplierName: '',
    calendarId: 'personal',
    productIds: [],
    productQuantities: {},
    details: '',
});

const selectedProducts = ref([]);
const selectedProductIds = ref([]);
const quantityError = ref('');

watch(() => formData.supplierId, (newSupplierId) => {
    if (newSupplierId && props.products[newSupplierId]) {
        selectedProducts.value = props.products[newSupplierId];
        const selectedSupplier = props.suppliers.find(supplier => supplier.id === newSupplierId);
        formData.supplierName = selectedSupplier ? selectedSupplier.name : '';
    } else {
        selectedProducts.value = [];
        formData.supplierName = '';
    }
});


function getMaxQuantity(productId) {
    const product = selectedProducts.value.find(product => product.id === productId);
    return product ? product.stock : 1;
}

function validateQuantity(productId) {
    const product = selectedProducts.value.find(product => product.id === productId);
    const enteredQuantity = formData.productQuantities[productId];

    if (enteredQuantity > product.stock) {
        quantityError.value = `Cannot order more than ${product.stock} units of ${product.name}`;
    } else {
        quantityError.value = '';
    }
}

const emits = defineEmits(['closeForm']);

function closeForm() {
    emits('closeForm');
}

function submitForm() {
    let valid = true;
    quantityError.value = '';

    // Check for quantity validity
    selectedProductIds.value.forEach((productId) => {
        const product = selectedProducts.value.find(product => product.id === productId);
        const enteredQuantity = formData.productQuantities[productId];

        if (enteredQuantity > product.stock) {
            valid = false;
            quantityError.value = `Cannot order more than ${product.stock} units of ${product.name}`;
        }
    });

    if (!valid) {
        return;
    }

    // Map the selected product IDs to include productName along with the quantity
    const productsWithQuantities = selectedProductIds.value.map((productId) => {
        const product = selectedProducts.value.find(product => product.id === productId);
        return {
            productId,
            productName: product ? product.name : '', // Add the product name
            quantity: formData.productQuantities[productId] || 1,
        };
    });

    formData.productQuantities = productsWithQuantities; // Update formData with product names included
    const details = {
        supplier: formData.supplierId,
        supplierName: formData.supplierName, // Add supplier name
        productQuantities: formData.productQuantities // Updated with product names
    };

    formData.details = JSON.stringify(details); // Store details as a JSON string

    // Send data to the backend
    formData.post(route('admin.calendar.event.store'), {
        onSuccess: () => {
            closeForm();
        },
        onError: (errors) => {
            console.error("Errors:", errors);
        }
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
