<template>
    <div class="relative mt-6 flex gap-2">
        <div class="flex items-center gap-2 mt-2">
            <input
                id="compareCheckbox"
                type="checkbox"
                v-model="is_checked"
                @change="handleComparisonChange"
                class="hidden peer"
            />
            <label
                for="compareCheckbox"
                class="flex items-center gap-2 cursor-pointer select-none px-3 py-1.5 rounded-full border border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-100 transition-all"
            >
                <span class="text-lg">
                    {{ is_checked ? "✅" : "🔲" }}
                </span>
                <span
                    :class="
                        is_checked
                            ? 'text-green-700 font-semibold'
                            : 'text-gray-700'
                    "
                    class="text-sm"
                >
                    Adaugă la comparat
                </span>
            </label>
        </div>

        <div
            v-if="showComparisonDropdown"
            class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded-lg border border-gray-200 z-10"
        >
            <div class="p-4">
                <div class="space-y-2">
                    <div
                        v-for="index in 3"
                        :key="index"
                        class="flex items-center justify-between mb-2"
                    >
                        <div class="flex items-center">
                            <img
                                v-if="comparisonProducts[index - 1]"
                                :src="comparisonProducts[index - 1].image_url"
                                alt="product image"
                                class="w-12 h-12 object-cover border rounded mr-2"
                            />
                            <div
                                v-else
                                class="w-12 h-12 bg-gray-100 border rounded mr-2"
                            ></div>
                        </div>
                        <button
                            v-if="comparisonProducts[index - 1]"
                            @click="
                                removeFromComparison(
                                    comparisonProducts[index - 1].id
                                )
                            "
                            class="text-red-500 hover:text-red-700 font-semibold"
                        >
                            ✖
                        </button>
                    </div>
                </div>

                <p
                    v-if="comparisonProducts.length === 0"
                    class="text-gray-500 text-sm text-center mt-2"
                >
                    Nu există produse de comparat
                </p>

                <button
                    v-if="comparisonProducts.length > 0"
                    @click="goToComparison"
                    :disabled="comparisonProducts.length < 2"
                    class="w-full py-2 rounded-lg mt-4 font-semibold transition-colors duration-200"
                    :class="{
                        'bg-indigo-600 text-white hover:bg-indigo-700':
                            comparisonProducts.length >= 2,
                        'bg-gray-300 text-gray-600 cursor-not-allowed':
                            comparisonProducts.length < 2,
                    }"
                >
                    Compară
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
        isChecked: Boolean,
    },

    data() {
        return {
            is_checked: this.isChecked,
            showComparisonDropdown: false,
            comparisonProducts: [],
        };
    },

    mounted() {
        this.fetchComparisonProducts();
    },

    methods: {
        fetchComparisonProducts() {
            axios
                .get("/comparison")
                .then((response) => {
                    this.comparisonProducts = response.data;
                    this.toggleDropdownVisibility();
                })
                .catch((error) => {
                    console.error("Error fetching comparison products:", error);
                });
        },

        handleComparisonChange() {
            if (this.is_checked) {
                this.addToComparison(this.product);
            } else {
                this.removeFromComparison(this.product.id);
            }
        },

        addToComparison(product) {
            const isDifferentCategory = this.comparisonProducts.some(
                (existingProduct) =>
                    existingProduct.category !== product.category
            );

            if (isDifferentCategory) {
                this.comparisonProducts = [];
                this.is_checked = true;
            }

            axios
                .post("/comparison/add", { product_id: product.id })
                .then(() => {
                    this.comparisonProducts.push(product);
                    this.toggleDropdownVisibility();
                })
                .catch((error) => {
                    console.error("Error adding product to comparison:", error);
                    alert(
                        "Failed to add product to comparison. Please try again."
                    );
                    this.is_checked = false;
                });
        },

        removeFromComparison(productId) {
            this.comparisonProducts = this.comparisonProducts.filter(
                (product) => product.id !== productId
            );
            this.toggleDropdownVisibility();
            this.is_checked = false;

            axios.delete(`/comparison/remove/${productId}`).catch((error) => {
                console.error("Error removing product from comparison:", error);
                this.fetchComparisonProducts();
            });
        },

        toggleDropdownVisibility() {
            this.showComparisonDropdown = this.comparisonProducts.length > 0;
        },

        goToComparison() {
            const productSlugs = this.comparisonProducts
                .map((product) => product.slug)
                .join(",");
            this.$inertia.get(
                `/comparison/${productSlugs}`,
                {},
                {
                    onSuccess: () => {
                        // optional dacă vrei reset după afișare
                        axios.post("/comparison/reset");
                    },
                }
            );
        },
    },
};
</script>

<style scoped>
.relative {
    position: relative;
}

.absolute {
    position: absolute;
}
</style>
