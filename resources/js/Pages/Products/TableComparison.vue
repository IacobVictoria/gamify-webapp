<template>
  <div class="overflow-x-auto">
    <table class="min-w-full border-collapse border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-200 p-4 text-left font-semibold">Atribut</th>
          <th v-for="(product, index) in products" :key="index"
            class="border border-gray-200 p-4 text-center font-semibold">
            {{ product.name }}
            <img src="/images/pic1.jpg" :alt="product.imageAlt" class="object-cover object-center" />
          </th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="attribute in attributes" :key="attribute.key">
          <td class="border border-gray-200 p-4 font-medium">{{ attribute.label }}</td>
          <td v-for="(product, index) in products" :key="index"
            :class="getComparisonClass(attribute.key, product[attribute.key], product.comparison)"
            class="border border-gray-200 p-4 text-center">
            <span v-if="attribute.key === 'ingredients'">
              <div v-for="(ingredient, idx) in product.comparison.commonIngredients" class="text-green-600 " :key="idx">
                {{ ingredient }}
              </div>
              <div v-for="(ingredient, idx) in product.comparison.nonCommonIngredients" :key="idx">
                {{ ingredient }}
              </div>
              <div
                v-if="product.comparison.commonIngredients.length === 0 && product.comparison.nonCommonIngredients.length === 0">
                x
              </div>
            </span>
            <span v-if="product[attribute.key] != null && attribute.key !== 'ingredients'">
              {{ formatValue(attribute.key, product[attribute.key]) }}
            </span>
            <span v-if="product[attribute.key] === null">
              x
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Recomandări -->
    <div class="mt-4 p-4 border border-gray-200 rounded bg-gray-50">
      <h2 class="text-lg font-semibold mb-2">Recomandări</h2>
      <ul class="list-disc pl-6">
        <li v-for="(desc, index) in recommendations" :key="index">
          {{ desc }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
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
  },
  computed: {
    attributes() {
      return [
        { key: "price", label: "Preț (lei)" },
        { key: "calories", label: "Calorii" },
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
      if (key === 'price') {
        return `${value} lei`;
      }
      return value;
    },

    getComparisonClass(attribute, value, comparison) {
      if (comparison[`${attribute}Color`] === 'red') {
        return 'bg-red-100'; // Culoare pentru maxim
      }
      if (comparison[`${attribute}Color`] === 'blue') {
        return 'bg-blue-100'; // Culoare pentru minim
      }
      return '';
    },
  },
};
</script>

<style scoped>
table {
  table-layout: fixed;
}

th,
td {
  text-align: center;
  vertical-align: middle;
}

.bg-red-100 {
  background-color: #fecaca;
}

.bg-blue-100 {
  background-color: #bfdbfe;
}

.underline {
  text-decoration: underline;
}

.font-semibold {
  font-weight: 600;
}

.text-green-600 {
  color: #16a34a;
}
</style>