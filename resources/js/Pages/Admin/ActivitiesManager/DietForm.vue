<template>
    <div class="space-y-4 mt-4">
        <!-- Recommendations -->
        <div>
            <label class="block text-sm font-medium text-gray-700"
                >Recomandări</label
            >
            <textarea
                v-model="details.recommendations"
                rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                placeholder="Ce să eviți, când să mănânci etc."
            />
        </div>

        <!-- Diet Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700"
                >Conținutul dietei</label
            >
            <textarea
                v-model="details.content"
                rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                placeholder="Descrie planul complet de dietă, beneficiile, structura etc."
            />
        </div>

        <!-- Product Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Selectează gustări</label
            >
            <select
                multiple
                v-model="details.snacks"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            >
                <option
                    v-for="product in availableProducts"
                    :key="product.id"
                    :value="product.link"
                >
                    {{ product.name }} ({{ product.calories }} kcal)
                </option>
            </select>
        </div>

        <!-- Custom Products -->
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <h4 class="font-medium text-sm text-gray-700">
                    Produse personalizate
                </h4>
                <button
                    @click="addCustomProduct"
                    type="button"
                    class="text-xs px-2 py-1 bg-green-200 text-green-800 rounded hover:bg-green-300"
                >
                    ➕ Adaugă produs personalizat
                </button>
            </div>

            <!-- Header tabel produse -->
            <div
                class="grid grid-cols-7 gap-2 font-semibold text-gray-600 text-sm mt-4"
            >
                <span class="col-span-2">Nume</span>
                <span>Kcal</span>
                <span>Proteine (g)</span>
                <span>Carbohidrați (g)</span>
                <span>Grăsimi (g)</span>
                <span class="text-right">Acțiune</span>
            </div>
            <div
                v-for="(product, index) in details.custom_products"
                :key="index"
                class="grid grid-cols-7 gap-2 items-center mt-1"
            >
                <input
                    v-model="product.name"
                    placeholder="Nume"
                    class="col-span-2 border border-gray-300 rounded-md px-2 py-1"
                />
                <input
                    v-model.number="product.calories"
                    type="number"
                    placeholder="Kcal"
                    class="border border-gray-300 rounded-md px-2 py-1"
                />
                <input
                    v-model.number="product.protein"
                    type="number"
                    placeholder="Proteine"
                    class="border border-gray-300 rounded-md px-2 py-1"
                />
                <input
                    v-model.number="product.carbs"
                    type="number"
                    placeholder="Carbohidrați"
                    class="border border-gray-300 rounded-md px-2 py-1"
                />
                <input
                    v-model.number="product.fats"
                    type="number"
                    placeholder="Grăsimi"
                    class="border border-gray-300 rounded-md px-2 py-1"
                />
                <div class="text-right">
                    <button
                        @click="removeCustomProduct(index)"
                        type="button"
                        class="text-red-500 text-xs hover:text-red-700"
                    >
                        Șterge
                    </button>
                </div>
            </div>
        </div>

        <!-- Nutrition Summary -->
        <div class="text-sm text-gray-600 border-t pt-4">
            <p>
                Calorii totale: <strong>{{ summary.calories }}</strong>
            </p>
            <p>
                Proteine: <strong>{{ summary.protein }}g</strong>
            </p>
            <p>
                Carbohidrați: <strong>{{ summary.carbs }}g</strong>
            </p>
            <p>
                Grăsimi: <strong>{{ summary.fats }}g</strong>
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed, toRefs, watch } from "vue";

const props = defineProps({
    details: Object,
    availableProducts: Array,
});

const { details, availableProducts } = toRefs(props);

const addCustomProduct = () => {
    details.value.custom_products.push({
        name: "",
        calories: 0,
        protein: 0,
        carbs: 0,
        fats: 0,
    });
};

const removeCustomProduct = (index) => {
    details.value.custom_products.splice(index, 1);
};

const summary = computed(() => {
    const selected = availableProducts.value.filter((p) =>
        details.value.snacks.includes(p.link)
    );
    const all = [...selected, ...details.value.custom_products];
    return {
        calories: all.reduce((s, p) => s + Number(p.calories || 0), 0),
        protein: all.reduce((s, p) => s + Number(p.protein || 0), 0),
        carbs: all.reduce((s, p) => s + Number(p.carbs || 0), 0),
        fats: all.reduce((s, p) => s + Number(p.fats || 0), 0),
    };
});

watch(
    summary,
    (newSummary) => {
        details.value.total_nutrition = newSummary;
    },
    { immediate: true }
);
</script>
