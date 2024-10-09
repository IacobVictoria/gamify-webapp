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

                        <!-- <div class="mt-10 border-t border-gray-200 pt-10">
                            <fieldset>
                                <legend class="text-lg font-medium text-gray-900">Delivery method</legend>
                                <RadioGroup v-model="selectedDeliveryMethod"
                                    class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                    <RadioGroupOption as="template" v-for="deliveryMethod in deliveryMethods"
                                        :key="deliveryMethod.id" :value="deliveryMethod"
                                        :aria-label="deliveryMethod.title"
                                        :aria-description="`${deliveryMethod.turnaround} for ${deliveryMethod.price}`"
                                        v-slot="{ active, checked }">
                                        <div
                                            :class="[checked ? 'border-transparent' : 'border-gray-300', active ? 'ring-2 ring-indigo-500' : '', 'relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none']">
                                            <span class="flex flex-1">
                                                <span class="flex flex-col">
                                                    <span class="block text-sm font-medium text-gray-900">{{
                                                        deliveryMethod.title }}</span>
                                                    <span class="mt-1 flex items-center text-sm text-gray-500">{{
                                                        deliveryMethod.turnaround }}</span>
                                                    <span class="mt-6 text-sm font-medium text-gray-900">{{
                                                        deliveryMethod.price }}</span>
                                                </span>
                                            </span>
                                            <CheckCircleIcon v-if="checked" class="h-5 w-5 text-indigo-600"
                                                aria-hidden="true" />
                                            <span
                                                :class="[active ? 'border' : 'border-2', checked ? 'border-indigo-500' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-lg']"
                                                aria-hidden="true" />
                                        </div>
                                    </RadioGroupOption>
                                </RadioGroup>
                            </fieldset>
                        </div> -->

                        <!-- Payment -->
                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <!-- <h2 class="text-lg font-medium text-gray-900">Payment</h2>
  
              <fieldset class="mt-4">
                <legend class="sr-only">Payment type</legend>
                <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                  <div v-for="(paymentMethod, paymentMethodIdx) in paymentMethods" :key="paymentMethod.id" class="flex items-center">
                    <input v-if="paymentMethodIdx === 0" :id="paymentMethod.id" name="payment-type" type="radio" checked="" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <input v-else :id="paymentMethod.id" name="payment-type" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label :for="paymentMethod.id" class="ml-3 block text-sm font-medium text-gray-700">{{ paymentMethod.title }}</label>
                  </div>
                </div>
              </fieldset>
   -->


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

                                            <div class="ml-4 flow-root flex-shrink-0">
                                                <button type="button"
                                                    class="-m-2.5 flex items-center justify-center bg-white p-2.5 text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Remove</span>
                                                    <TrashIcon class="h-5 w-5" aria-hidden="true" />
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex flex-1 items-end justify-between pt-2">
                                            <p class="mt-1 text-sm font-medium text-gray-900">{{ item.product.price }}
                                            </p>

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
                                    <dd class="text-sm font-medium text-gray-900">$5.00</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Taxes</dt>
                                    <dd class="text-sm font-medium text-gray-900">$5.52</dd>
                                </div>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                                    <dt class="text-base font-medium">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">{{ calculateTotal() }}</dd>
                                </div>
                            </dl>

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
        }
    },

    methods: {
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
            const tax = 8.32;
            return (subtotal + shipping + tax);
        },

        submit() {
            this.form.post(route('user.checkout.order.store'), {
                onError: (errors) => {
                    console.log('NOT submitted successfully:');
                    this.errors = errors;
                },
                onSuccess: () => {
                    console.log('Form submitted successfully:');
                    this.form.reset();
                },
            });
        }

    }
}
</script>