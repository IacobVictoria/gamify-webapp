<template>
    <AuthenticatedLayout>
        <div class="bg-gray-50">
            <div
                class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8"
            >
                <h2 class="sr-only">Finalizare comandă</h2>

                <form
                    @submit.prevent="submit"
                    class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16"
                >
                    <div>
                        <!-- Informații de contact -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                Informații de contact
                            </h2>

                            <div class="mt-4">
                                <label
                                    for="email-address"
                                    class="block text-sm font-medium text-gray-700"
                                    >Adresă de email</label
                                >
                                <div class="mt-1">
                                    <input
                                        type="email"
                                        id="email-address"
                                        name="email-address"
                                        autocomplete="email"
                                        v-model="form.email"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Informații de livrare -->
                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <h2 class="text-lg font-medium text-gray-900">
                                Informații de livrare
                            </h2>

                            <div
                                class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4"
                            >
                                <div>
                                    <label
                                        for="first-name"
                                        class="block text-sm font-medium text-gray-700"
                                        >Prenume</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            id="first-name"
                                            name="first-name"
                                            autocomplete="given-name"
                                            v-model="form.firstName"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="last-name"
                                        class="block text-sm font-medium text-gray-700"
                                        >Nume</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            id="last-name"
                                            name="last-name"
                                            autocomplete="family-name"
                                            v-model="form.lastName"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="address"
                                        class="block text-sm font-medium text-gray-700"
                                        >Adresă</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="address"
                                            id="address"
                                            autocomplete="street-address"
                                            v-model="form.adress"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="apartment"
                                        class="block text-sm font-medium text-gray-700"
                                        >Apartament, etaj etc.</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="apartment"
                                            id="apartment"
                                            v-model="form.apartament"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="city"
                                        class="block text-sm font-medium text-gray-700"
                                        >Oraș</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="city"
                                            id="city"
                                            autocomplete="address-level2"
                                            v-model="form.city"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="country"
                                        class="block text-sm font-medium text-gray-700"
                                        >Țară</label
                                    >
                                    <div class="mt-1">
                                        <select
                                            id="country"
                                            name="country"
                                            autocomplete="country-name"
                                            v-model="form.country"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        >
                                            <option>România</option>
                                            <option>Statele Unite</option>
                                            <option>Canada</option>
                                            <option>Mexic</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="region"
                                        class="block text-sm font-medium text-gray-700"
                                        >Județ / Provincie</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="region"
                                            id="region"
                                            autocomplete="address-level1"
                                            v-model="form.state"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="postal-code"
                                        class="block text-sm font-medium text-gray-700"
                                        >Cod poștal</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="postal-code"
                                            id="postal-code"
                                            autocomplete="postal-code"
                                            v-model="form.code"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700"
                                        >Telefon</label
                                    >
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            name="phone"
                                            id="phone"
                                            autocomplete="tel"
                                            v-model="form.phone"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rezumat comandă -->
                    <div class="mt-10 lg:mt-0">
                        <h2 class="text-lg font-medium text-gray-900">
                            Rezumat comandă
                        </h2>

                        <div
                            class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm"
                        >
                            <h3 class="sr-only">Produse în coș</h3>
                            <ul role="list" class="divide-y divide-gray-200">
                                <li
                                    v-for="item in cartItems"
                                    :key="item.product.id"
                                    class="flex px-4 py-6 sm:px-6"
                                >
                                    <div class="flex-shrink-0">
                                        <img
                                            :src="item.product.image_url"
                                            class="w-20 rounded-md"
                                        />
                                    </div>

                                    <div class="ml-6 flex flex-1 flex-col">
                                        <div class="flex">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm">
                                                    <a
                                                        :href="
                                                            route(
                                                                'products.show',
                                                                item.product
                                                                    .slug
                                                            )
                                                        "
                                                        class="font-medium text-gray-700 hover:text-gray-800"
                                                    >
                                                        {{ item.product.name }}
                                                    </a>
                                                </h4>
                                                <p
                                                    class="mt-1 text-sm text-gray-500"
                                                >
                                                    {{ item.product.category }}
                                                </p>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-1 items-end justify-between pt-2"
                                        >
                                            <div class="mt-1 text-sm">
                                                <span
                                                    v-if="
                                                        item.product.old_price
                                                    "
                                                    class="text-red-500 line-through mr-2"
                                                >
                                                    {{ item.product.old_price }}
                                                    RON
                                                </span>
                                                <span
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{ item.product.price }} RON
                                                </span>
                                            </div>
                                            <div>
                                                <label
                                                    for="quantity-{{ item.product.id }}"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Cantitate:</label
                                                >
                                                <p>{{ item.quantity }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <dl
                                class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6"
                            >
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Subtotal</dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ calculateSubtotal() }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Livrare</dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        10.00 RON
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Taxe</dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        5.00 RON
                                    </dd>
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-gray-200 pt-6"
                                >
                                    <dt class="text-base font-medium">Total</dt>
                                    <dd
                                        class="text-base font-medium text-gray-900"
                                    >
                                        <span
                                            v-if="calculateTotal().oldTotal"
                                            class="text-red-500 line-through mr-2"
                                        >
                                            {{ calculateTotal().oldTotal }} RON
                                        </span>
                                        <span
                                            :class="{
                                                'text-green-600':
                                                    calculateTotal().oldTotal,
                                            }"
                                        >
                                            {{ calculateTotal().newTotal }} RON
                                        </span>
                                    </dd>
                                </div>
                            </dl>

                            <!-- Cod promoțional -->
                            <div class="m-6">
                                <label
                                    for="promo-code"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Cod promoțional
                                </label>
                                <div
                                    class="mt-1 flex flex-col sm:flex-row sm:items-center sm:space-x-2"
                                >
                                    <select
                                        v-model="promoCode"
                                        id="promo-code"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option value="">
                                            Selectează un cod promoțional
                                        </option>
                                        <option
                                            v-for="item in availablePromoCodes"
                                            :key="item.code"
                                            :value="item.code"
                                        >
                                            {{ item.label }}
                                        </option>
                                    </select>

                                    <button
                                        @click.prevent="applyPromoCode"
                                        class="mt-2 sm:mt-0 bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                        Aplică
                                    </button>
                                </div>

                                <p
                                    v-if="discountApplied"
                                    class="mt-2 text-green-600"
                                >
                                    Reducere aplicată: {{ discountAmount }}%
                                </p>
                                <p v-if="promoError" class="mt-2 text-red-600">
                                    {{ promoError }}
                                </p>
                            </div>

                            <div
                                class="border-t border-gray-200 px-4 py-6 sm:px-6"
                            >
                                <button
                                    type="submit"
                                    class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                                >
                                    Confirmă comanda
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { TrashIcon } from "@heroicons/vue/24/outline";

export default {
    components: {
        AuthenticatedLayout,
        TrashIcon,
    },
    props: {
        cartItems: {
            type: Array,
            required: true,
        },
        availablePromoCodes: Array,
    },

    data() {
        return {
            form: useForm({
                email: "",
                firstName: "",
                lastName: "",
                code: "",
                adress: "",
                apartament: "",
                state: "",
                country: "",
                city: "",
                phone: "",
                cartItems: this.cartItems,
            }),
            errors: {},
            promoCode: "",
            discountAmount: 0,
            discountApplied: false,
            promoError: "",
        };
    },

    methods: {
        async applyPromoCode() {
            this.promoError = "";
            try {
                const response = await axios.post("/user/validate-discount", {
                    code: this.promoCode,
                });
                if (response.data.valid) {
                    this.discountAmount = response.data.discount;
                    this.discountApplied = true;
                } else {
                    this.promoError = "Cod invalid ";
                }
            } catch {
                this.promoError = "Eroare";
            }
        },

        calculateSubtotal() {
            if (this.cartItems.length === 0) {
                return 0;
            }

            const total = this.cartItems.reduce((total, item) => {
                return (
                    total +
                    (Number(item.product.price) * Number(item.quantity) || 0)
                );
            }, 0);

            return parseFloat(total.toFixed(2));
        },

        calculateTotal() {
            const subtotal = this.calculateSubtotal();
            const shipping = 5.0;
            const tax = 10.0;
            const totalBeforeDiscount = subtotal + shipping + tax;

            if (this.discountApplied && this.discountAmount > 0) {
                const discountValue =
                    totalBeforeDiscount * (this.discountAmount / 100);
                return {
                    oldTotal: totalBeforeDiscount.toFixed(2),
                    newTotal: (totalBeforeDiscount - discountValue).toFixed(2),
                };
            }

            return {
                oldTotal: null,
                newTotal: totalBeforeDiscount.toFixed(2),
            };
        },

        submit() {
            this.$inertia.post(route("user.checkout.order.store"), {
                ...this.form,
                promoCode: this.promoCode,
                discountAmount: this.discountAmount,
            });
        },
    },
};
</script>
