<template>
    <div>
        <h3 class="text-base font-semibold text-gray-900">Editează Comanda</h3>
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
                    >Selectează Produse</label
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
                                'Quantity for ' +
                                selectedProducts.find(
                                    (product) => product.id === productId
                                )?.name
                            "
                            class="input w-1/3"
                            min="1"
                            @blur="validateQuantity(productId)"
                        />
                    </div>
                    <div
                        v-if="formErrors[`quantity_${productId}`]"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ formErrors[`quantity_${productId}`] }}
                    </div>
                </div>
            </div>

            <!-- Opțiuni de recurență -->
            <div v-if="!is_recurring" class="mt-2">
                <label class="flex items-center">
                    <input type="checkbox" v-model="formData.is_recurring" />
                    <span class="ml-2 text-sm font-medium text-gray-700"
                        >Eveniment recurent</span
                    >
                </label>
            </div>

            <div v-if="!is_recurring" class="mt-2">
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

            <div class="mt-5 sm:mt-6">
                <button
                    type="submit"
                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    Actualizează
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
    props: {
        event: Object,
        suppliers: Array,
        products: Object, // Produsele sunt grupate după supplierId
    },
    data() {
        const details = this.event?.details
            ? JSON.parse(this.event.details)
            : {};
        return {
            formData: useForm({
                id: this.event?.id ?? "",
                title: this.event?.title ?? "",
                start: this.event?.start ? this.event.start.split(" ")[0] : "",
                end: this.event?.end ? this.event.end.split(" ")[0] : "",
                type: this.event?.type ?? "",
                description: this.event?.description ?? "",
                supplierId: details.supplier ?? null,
                supplierName: details.supplierName ?? "",
                calendarId: this.event?.calendarId ?? "",
                productQuantities: details.productQuantities
                    ? details.productQuantities.reduce((acc, item) => {
                          acc[item.productId] = item.quantity;
                          return acc;
                      }, {})
                    : {},
                is_recurring: this.event?.is_recurring ?? false,
                recurring_interval: this.event?.recurring_interval ?? null,
            }),
            selectedProducts: [],
            selectedProductIds: details.productQuantities
                ? details.productQuantities.map((item) => item.productId)
                : [],
            quantityError: "",
            formErrors: {},
        };
    },
    watch: {
        "formData.supplierId": {
            handler(newSupplierId) {
                if (newSupplierId && this.products[newSupplierId]) {
                    this.selectedProducts = this.products[newSupplierId];
                    this.selectedProductIds = Object.keys(
                        this.formData.productQuantities
                    );
                } else {
                    this.selectedProducts = [];
                    this.selectedProductIds = [];
                }
            },
            immediate: true,
        },
        selectedProductIds: {
            handler(newSelected) {
                let newQuantities = {};
                newSelected.forEach((productId) => {
                    newQuantities[productId] =
                        this.formData.productQuantities[productId] || 1;
                });
                this.formData.productQuantities = newQuantities;
            },
            deep: true,
        },
    },
    methods: {
        validateForm() {
            let valid = true;
            this.formErrors = {};

            for (const productId of this.selectedProductIds) {
                const quantity = this.formData.productQuantities[productId];
                const product = this.selectedProducts.find(
                    (p) => p.id === productId
                );

                if (!quantity || quantity < 1) {
                    this.formErrors[
                        `quantity_${productId}`
                    ] = `Introduceți o cantitate validă`;
                    valid = false;
                } else if (quantity > product.stock) {
                    this.formErrors[
                        `quantity_${productId}`
                    ] = `Maxim ${product.stock} disponibile`;
                    valid = false;
                }
            }

            return valid;
        },
        submitForm() {
            if (!this.validateForm()) return;

            const formattedProductQuantities = this.selectedProductIds.map(
                (productId) => {
                    const product = this.selectedProducts.find(
                        (p) => p.id === productId
                    );
                    return {
                        productId,
                        productName: product ? product.name : "",
                        quantity:
                            this.formData.productQuantities[productId] || 1,
                    };
                }
            );

            this.formData.details = JSON.stringify({
                supplier: this.formData.supplierId,
                supplierName:
                    this.suppliers.find(
                        (s) => s.id === this.formData.supplierId
                    )?.name ?? "",
                productQuantities: formattedProductQuantities,
            });

            this.$inertia.put(
                route("admin.calendar.event.update", { id: this.formData.id }),
                {
                    payload: this.formData,
                    preserveScroll: true,
                    onSuccess: () => window.location.reload(),
                    onError: (errors) => console.error("Errors:", errors),
                }
            );
        },
    },
};
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
