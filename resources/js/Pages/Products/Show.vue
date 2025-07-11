<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white">
                <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                    <!-- Product Section -->

                    <div
                        class="lg:grid lg:grid-cols-7 lg:gap-x-8 lg:gap-y-10 xl:gap-x-16 p-6"
                    >
                        <!-- Product Image -->
                        <div class="lg:col-span-4 lg:row-end-1 relative">
                            <div
                                class="relative w-full h-86 flex items-center justify-center overflow-hidden rounded-lg"
                            >
                                <img
                                    :src="product.image_url"
                                    alt="imageAlt"
                                    class="max-h-full max-w-full object-cover"
                                />
                            </div>
                            <div
                                class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full shadow-md text-sm font-semibold"
                            >
                                🍪 Gustarea preferată!
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div
                            class="mx-auto mt-14 max-w-2xl sm:mt-16 lg:col-span-3 lg:row-span-2 lg:row-end-2 lg:mt-0 lg:max-w-none"
                        >
                            <div class="mt-4">
                                <h1
                                    class="text-3xl font-bold text-gray-900 sm:text-4xl"
                                >
                                    🍭 {{ product.name }}
                                </h1>
                                <h2
                                    class="text-xl font-semibold text-gray-700 sm:text-2xl mt-2"
                                >
                                    📌 Categorie: {{ product.category }}
                                </h2>
                                <button
                                    v-if="isLoggedIn()"
                                    @click="
                                        isFavorite
                                            ? dislikeProduct(product)
                                            : likeProduct(product)
                                    "
                                    class="mt-3"
                                >
                                    <AddHeartSVG
                                        :svg-class="
                                            isFavorite
                                                ? 'text-red-400'
                                                : 'text-gray-400'
                                        "
                                    />
                                </button>
                            </div>

                            <p
                                class="mt-6 text-gray-700 text-lg leading-relaxed"
                            >
                                🌟 {{ product.description }}
                            </p>
                            <div class="mt-6 text-2xl font-semibold">
                                <span
                                    v-if="product.old_price"
                                    class="text-red-500 line-through mr-2"
                                    >💰 {{ product.old_price }} RON</span
                                >
                                <span
                                    :class="{
                                        'text-green-600': product.old_price,
                                    }"
                                    >💸 {{ product.price }} RON</span
                                >
                            </div>
                            <ComparisonDropdown
                                :product="product"
                                :isChecked="comparisonChecked"
                            ></ComparisonDropdown>
                            <!-- Quantity Selector -->
                            <div class="mt-6">
                                <label
                                    for="quantity"
                                    class="block text-lg font-medium text-gray-800"
                                    >🔢 Alege cantitatea</label
                                >
                                <select
                                    id="quantity"
                                    v-model="quantity"
                                    class="mt-2 block w-24 text-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="" disabled>
                                        Selectează
                                    </option>
                                    <option
                                        v-for="num in 10"
                                        :key="num"
                                        :value="num"
                                    >
                                        🍽️ {{ num }}
                                    </option>
                                </select>
                            </div>

                            <!-- Purchase Buttons -->
                            <template v-if="isLoggedIn()">
                                <button
                                    @click="addToCart(product)"
                                    v-if="authUserHasRole('User')"
                                    class="mt-6 flex w-86 items-center justify-center rounded-md border border-transparent bg-green-500 px-8 py-3 text-lg font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                                >
                                    🛒 Adaugă în coș pentru
                                    {{ product.price }} RON
                                </button>
                            </template>
                        </div>

                        <!-- Nutritional Information Table -->
                        <div
                            class="mt-10 p-6 rounded-lg shadow-md bg-gray-50 w-full lg:col-span-7"
                        >
                            <h2
                                class="text-2xl font-bold text-center text-gray-900 mb-4"
                            >
                                🍽️ Valori nutriționale
                            </h2>
                            <div class="overflow-hidden rounded-lg">
                                <table
                                    class="min-w-full bg-white rounded-lg shadow-md text-gray-700"
                                >
                                    <tbody>
                                        <tr
                                            class="border-b px-6 py-4 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🚀 Energie superioară (Calorii)
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.calories }} kcal
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 bg-gray-100 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                💪 Creștere musculară (Proteine)
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.protein }} g
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🍞 Combustibil energetic
                                                (Carbohidrați)
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.carbs }} g
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 bg-gray-100 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🥑 Grăsimi sănătoase
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.fats }} g
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🌿 Eroul digestiei (Fibre)
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.fiber }} g
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 bg-gray-100 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🍭 Nivel de dulceață (Zahăr)
                                            </td>
                                            <td class="font-semibold">
                                                {{ product.sugar }} g
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-b px-6 py-4 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                🧑‍🍳 Ingrediente
                                            </td>
                                            <td class="font-semibold">
                                                {{
                                                    product.ingredients.join(
                                                        ", "
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="px-6 py-4 bg-gray-100 flex justify-between"
                                        >
                                            <td class="flex items-center">
                                                ⚠️ Alergeni
                                            </td>
                                            <td class="font-semibold">
                                                {{
                                                    product.allergens.join(", ")
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div v-if="noStatistics">
                        <ReviewSummary
                            :averageRating="averageRating"
                            :statistics="statistics"
                        ></ReviewSummary>
                    </div>
                    <div class="flex gap-16">
                        <SortingComponent
                            :filter="filter"
                            v-model="sortOrder"
                            :options="sortOptions"
                        >
                        </SortingComponent>
                    </div>
                    <ProductsReviewList
                        :reviews="reviews"
                        :productId="product.id"
                        :message="noBuyersMessage"
                        :statistics="statistics"
                        :averageRating="averageRating"
                        :is-verified-buyer="isVerifiedBuyer"
                    >
                    </ProductsReviewList>
                </div>
            </div>
        </main>
    </Layout>
</template>

<script>
import Layout from "@/Layouts/Layout.vue";
import { TabGroup, TabPanel, TabPanels } from "@headlessui/vue";
import ReviewForm from "../Reviews/ReviewForm.vue";
import StarRating from "vue-star-rating";
import ProductsReviewList from "../Reviews/ProductsReviewList.vue";
import SortingComponent from "@/Components/SortingComponent.vue";
import debounce from "lodash/fp/debounce";
import VerifiedSVG from "@/Components/VerifiedSVG.vue";
import ReviewSummary from "../Reviews/ReviewSummary.vue";
import AddHeartSVG from "@/Components/AddHeartSVG.vue";
import ComparisonDropdown from "./ComparisonDropdown.vue";

export default {
    components: {
        Layout,
        TabGroup,
        TabPanel,
        TabPanels,
        ReviewForm,
        StarRating,
        ProductsReviewList,
        SortingComponent,
        VerifiedSVG,
        ReviewSummary,
        AddHeartSVG,
        ComparisonDropdown,
    },
    props: {
        product: Object,
        reviews: Array,
        statistics: Map,
        averageRating: Number,
        noStatistics: Boolean,
        isFavorite: Boolean,
        comparisonChecked: Boolean,
        isVerifiedBuyer: Boolean,
    },

    data() {
        return {
            showReviewForm: false,
            editMode: false,
            editReviewForm: false,
            quantity: 1,
            sortOrder: "",
            sortOptions: [
                { value: "noi", label: "Cele mai noi" },
                { value: "populare", label: "Cele mai populare" },
            ],
            filter: {
                id: "sortOrder",
                name: "sortOrder",
                type: "sorting",
                placeholder: "Selectează metoda de sortare",
            },
        };
    },
    methods: {
        addToCart(product) {
            this.$inertia.post(route("user.shopping-cart.add"), {
                product: product,
                quantity: this.quantity,
            });
        },

        likeProduct(product) {
            this.$inertia.post(
                route("user.wishlist.products.like", product.id),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },

        dislikeProduct(product) {
            this.$inertia.post(
                route("user.wishlist.products.dislike", product.id),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },
    },
    watch: {
        sortOrder: {
            handler: debounce(300, function () {
                this.$inertia.get(
                    route("products.show", this.product.slug),
                    {
                        order: this.sortOrder,
                    },
                    {
                        preserveState: true,
                         preserveScroll: true,
                        replace: true,
                    }
                );
            }),
            deep: true,
        },
    },
};
</script>
