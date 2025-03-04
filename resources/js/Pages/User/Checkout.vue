<template>
    <AuthenticatedLayout>
        <div class="bg-gray-50">
            <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
                <h2 class="sr-only">Checkout</h2>

                <form @submit.prevent="submit" class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                    <div>
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Contact information</h2>

                            <div class="mt-4">
                                <label for="email-address" class="block text-sm font-medium text-gray-700">Email
                                    address</label>
                                <div class="mt-1">
                                    <input type="email" id="email-address" name="email-address" autocomplete="email"
                                        v-model="form.email"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <h2 class="text-lg font-medium text-gray-900">Shipping information</h2>

                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="first-name" class="block text-sm font-medium text-gray-700">First
                                        name</label>
                                    <div class="mt-1">
                                        <input type="text" id="first-name" name="first-name" autocomplete="given-name"
                                            v-model="form.firstName"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div>
                                    <label for="last-name" class="block text-sm font-medium text-gray-700">Last
                                        name</label>
                                    <div class="mt-1">
                                        <input type="text" id="last-name" name="last-name" autocomplete="family-name"
                                            v-model="form.lastName"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <div class="mt-1">
                                        <input type="text" name="address" id="address" autocomplete="street-address"
                                            v-model="form.adress"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="apartment" class="block text-sm font-medium text-gray-700">Apartment,
                                        suite,
                                        etc.</label>
                                    <div class="mt-1">
                                        <input type="text" name="apartment" id="apartment" v-model="form.apartament"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <div class="mt-1">
                                        <input type="text" name="city" id="city" autocomplete="address-level2"
                                            v-model="form.city"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <div class="mt-1">
                                        <select id="country" name="country" autocomplete="country-name"
                                            v-model="form.country"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option>United States</option>
                                            <option>Canada</option>
                                            <option>Mexico</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700">State /
                                        Province</label>
                                    <div class="mt-1">
                                        <input type="text" name="region" id="region" autocomplete="address-level1"
                                            v-model="form.state"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div>
                                    <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal
                                        code</label>
                                    <div class="mt-1">
                                        <input type="text" name="postal-code" id="postal-code"
                                            autocomplete="postal-code" v-model="form.code"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <div class="mt-1">
                                        <input type="text" name="phone" id="phone" autocomplete="tel"
                                            v-model="form.phone"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <div class="mt-10 lg:mt-0">
                        <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

                        <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
                            <h3 class="sr-only">Items in your cart</h3>
                            <ul role="list" class="divide-y divide-gray-200">
                                <li v-for="item in cartItems" :key="item.product.id" class="flex px-4 py-6 sm:px-6">
                                    <div class="flex-shrink-0">
                                        <img src="/images/best.jpg" class="w-20 rounded-md" />
                                    </div>

                                    <div class="ml-6 flex flex-1 flex-col">
                                        <div class="flex">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm">
                                                    <a :href="route('products.show', item.product.id)"
                                                        class="font-medium text-gray-700 hover:text-gray-800">{{
                                                            item.product.name }}</a>
                                                </h4>
                                                <p class="mt-1 text-sm text-gray-500">{{ item.product.category }}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-1 items-end justify-between pt-2">
                                            <div class="mt-1 text-sm">
                                                <span v-if="item.product.old_price"
                                                    class="text-red-500 line-through mr-2">
                                                    {{ item.product.old_price }} RON
                                                </span>
                                                <span class="font-medium text-gray-900">
                                                    {{ item.product.price }} RON
                                                </span>
                                            </div>
                                            <div>
                                                <label for="quantity-{{ item.product.id }}"
                                                    class="block text-sm font-medium text-gray-700">Cantitate:</label>
                                                <p>{{ item.quantity }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ calculateSubtotal() }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Shipping</dt>
                                    <dd class="text-sm font-medium text-gray-900">$10.00</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Taxes</dt>
                                    <dd class="text-sm font-medium text-gray-900">$5.00</dd>
                                </div>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                                    <dt class="text-base font-medium">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">
                                        <span v-if="calculateTotal().oldTotal" class="text-red-500 line-through mr-2">
                                            {{ calculateTotal().oldTotal }} RON
                                        </span>
                                        <span :class="{ 'text-green-600': calculateTotal().oldTotal }">
                                            {{ calculateTotal().newTotal }} RON
                                        </span>
                                    </dd>

                                </div>

                            </dl>
                            <!-- Promo Code -->
                            <div class="mt-6">
                                <label for="promo-code" class="block text-sm font-medium text-gray-700">Promo
                                    Code</label>
                                <div class="mt-1 flex">
                                    <input type="text" id="promo-code" v-model="promoCode"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Enter promo code" />
                                    <button @click.prevent="applyPromoCode"
                                        class="ml-2 bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Apply
                                    </button>
                                </div>
                                <p v-if="discountApplied" class="mt-2 text-green-600">Discount applied: {{
                                    discountAmount }}%</p>
                                <p v-if="promoError" class="mt-2 text-red-600">{{ promoError }}</p>
                            </div>

                            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                <button type="submit"
                                    class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Confirm
                                    order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';



export default {
    components: {
        AuthenticatedLayout,
        TrashIcon
    },
    props: {
        cartItems: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            form: useForm({
                email: '',
                firstName: '',
                lastName: '',
                code: '',
                adress: '',
                apartament: '',
                state: '',
                country: '',
                city: '',
                phone: '',
                cartItems: this.cartItems,
            }),
            errors: {},
            promoCode: '',
            discountAmount: 0,
            discountApplied: false,
            promoError: '',
        }
    },

    methods: {
        async applyPromoCode() {
            this.promoError = '';
            try {
                const response = await axios.post('/user/validate-discount', { code: this.promoCode });
                if (response.data.valid) {
                    this.discountAmount = response.data.discount;
                    this.discountApplied = true;
                } else {
                    this.promoError = 'Invalid promo code';
                }
            } catch {
                this.promoError = 'Error checking promo code';
            }
        },
        calculateSubtotal() {
            if (this.cartItems.length === 0) {
                return 0;
            }

            const total = this.cartItems.reduce((total, item) => {
                return total + (Number(item.product.price) * Number(item.quantity) || 0);
            }, 0);

            return parseFloat(total.toFixed(2));

        },
        calculateTotal() {
            const subtotal = this.calculateSubtotal();
            const shipping = 5.00;
            const tax = 10.00;
            const totalBeforeDiscount = subtotal + shipping + tax;

            if (this.discountApplied && this.discountAmount > 0) {
                const discountValue = totalBeforeDiscount * (this.discountAmount / 100);
                return {
                    oldTotal: totalBeforeDiscount.toFixed(2),
                    newTotal: (totalBeforeDiscount - discountValue).toFixed(2)
                };
            }

            return {
                oldTotal: null,
                newTotal: totalBeforeDiscount.toFixed(2)
            };
        },
        submit() {
            this.$inertia.post(route('user.checkout.order.store'), {
                ...this.form,
                promoCode: this.promoCode,
                discountAmount: this.discountAmount
            });
        }
    }
}
</script>