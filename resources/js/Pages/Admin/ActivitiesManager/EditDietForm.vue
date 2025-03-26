<template>
    <div class="space-y-4 mt-4">
      <!-- Recommendations -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Recommendations</label>
        <textarea
          v-model="details.recommendations"
          rows="3"
          placeholder="What to avoid, when to eat, etc"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        />
      </div>
  
      <!-- Content -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Diet Content</label>
        <textarea
          v-model="details.content"
          rows="4"
          placeholder="Describe the full diet plan..."
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        />
      </div>
  
      <!-- Snacks -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Select Snacks</label>
        <select
          multiple
          v-model="details.snacks"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        >
          <option
            v-for="product in availableProducts"
            :key="product.id"
            :value="product.id"
          >
            {{ product.name }} ({{ product.calories }} kcal)
          </option>
        </select>
      </div>
  
      <!-- Custom Products -->
      <div class="space-y-2">
        <div class="flex justify-between items-center">
          <h4 class="font-medium text-sm text-gray-700">Custom Products</h4>
          <button
            @click="addCustomProduct"
            type="button"
            class="text-xs px-2 py-1 bg-green-200 text-green-800 rounded hover:bg-green-300"
          >
            ➕ Add Custom Product
          </button>
        </div>
  
        <div
          class="grid grid-cols-7 gap-2 font-semibold text-gray-600 text-sm mt-4"
        >
          <span class="col-span-2">Name</span>
          <span>Kcal</span>
          <span>Protein (g)</span>
          <span>Carbs (g)</span>
          <span>Fats (g)</span>
          <span class="text-right">Action</span>
        </div>
  
        <div
          v-for="(product, index) in details.custom_products"
          :key="index"
          class="grid grid-cols-7 gap-2 items-center mt-1"
        >
          <input v-model="product.name" class="col-span-2 border border-gray-300 rounded-md px-2 py-1" placeholder="Name" />
          <input v-model.number="product.calories" type="number" placeholder="Kcal" class="border border-gray-300 rounded-md px-2 py-1" />
          <input v-model.number="product.protein" type="number" placeholder="Protein" class="border border-gray-300 rounded-md px-2 py-1" />
          <input v-model.number="product.carbs" type="number" placeholder="Carbs" class="border border-gray-300 rounded-md px-2 py-1" />
          <input v-model.number="product.fats" type="number" placeholder="Fats" class="border border-gray-300 rounded-md px-2 py-1" />
          <div class="text-right">
            <button
              @click="removeCustomProduct(index)"
              type="button"
              class="text-red-500 text-xs hover:text-red-700"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
  
      <!-- Nutrition Summary (read-only) -->
      <div class="text-sm text-gray-600 border-t pt-4">
        <p>Total Calories: <strong>{{ summary.calories }}</strong></p>
        <p>Proteins: <strong>{{ summary.protein }}g</strong></p>
        <p>Carbs: <strong>{{ summary.carbs }}g</strong></p>
        <p>Fats: <strong>{{ summary.fats }}g</strong></p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed, toRefs } from 'vue';
  
  const props = defineProps({
    details: Object,
    availableProducts: Array
  });
  
  const { details, availableProducts } = toRefs(props);
  
  const addCustomProduct = () => {
    details.value.custom_products.push({
      name: '',
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
    const selected = availableProducts.value.filter(p =>
      details.value.snacks.includes(p.id)
    );
    const all = [...selected, ...details.value.custom_products];
    return {
      calories: all.reduce((s, p) => s + Number(p.calories || 0), 0),
      protein: all.reduce((s, p) => s + Number(p.protein || 0), 0),
      carbs: all.reduce((s, p) => s + Number(p.carbs || 0), 0),
      fats: all.reduce((s, p) => s + Number(p.fats || 0), 0),
    };
  });
  </script>
  