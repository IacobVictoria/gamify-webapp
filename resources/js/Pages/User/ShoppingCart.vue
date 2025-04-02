<template>
<AuthenticatedLayout>
        <div class="bg-gray-50">
            <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <div class="mx-auto max-w-4xl">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Shopping Cart</h1>

      <form class="mt-12">
        <div>
          <h2 class="sr-only">Items in your shopping cart</h2>

          <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
            <li v-for="item in cartItems" :key="item.product.id" class="flex py-6 sm:py-10">
              <div class="flex-shrink-0">
                <img :src="item.product.image_url" :alt="item.product.description"
                  class="h-24 w-24 rounded-lg object-cover object-center sm:h-32 sm:w-32" />
              </div>

              <div class="relative ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                <div>
                  <div class="flex justify-between sm:grid sm:grid-cols-2">
                    <div class="pr-6">
                      <h3 class="text-sm">
                        <a class="font-medium text-gray-700 hover:text-gray-800">{{ item.product.name }}</a>
                      </h3>
                      <p v-if="item.product.category" class="mt-1 text-sm text-gray-500">{{ item.product.category }}</p>
                    </div>

                    <p class="text-right text-sm font-medium text-gray-900">{{ item.product.price }}</p>
                    <div>
                      <label for="quantity-{{ item.product.id }}"
                        class="block text-sm font-medium text-gray-700">Cantitate:</label>
                      <select id="quantity-{{ item.product.id }}" class="text-right text-sm font-medium text-gray-900"
                        v-model="item.quantity" @change="updateQuantity(item.product.id, item.quantity)">
                        <option v-for="i in 10" :key="i" :value="i">{{ i }}</option>
                      </select>
                    </div>
                    <!-- Prețul formatat -->
                  </div>

                  <div class="mt-4 flex items-center sm:absolute sm:left-1/2 sm:top-0 sm:mt-0 sm:block">
                    <button type="button"
                      class="ml-4 text-sm font-medium text-indigo-600 hover:text-indigo-500 sm:ml-0 sm:mt-3"
                      @click="openDeleteDialog(item.product.id)">Remove</button>
                  </div>
                </div>

                <p class="mt-4 flex space-x-2 text-sm text-gray-700">
                  <CheckIcon v-if="inStock[item.product.id] = item.product.stock >= item.quantity"
                    class="h-5 w-5 flex-shrink-0 text-green-500" aria-hidden="true" />
                  <ClockIcon v-else class="h-5 w-5 flex-shrink-0 text-gray-300" aria-hidden="true" />
                  <span>{{ inStock[item.product.id] ? 'In stock' : 'Out of stock' }}</span>
                </p>
              </div>
            </li>
          </ul>
        </div>

        <!-- Order summary -->
        <div class="mt-10 sm:ml-32 sm:pl-6">
          <div class="rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:p-8">
            <h2 class="sr-only">Order summary</h2>

            <div class="flow-root">
              <dl class="-my-4 divide-y divide-gray-200 text-sm">
                <div class="flex items-center justify-between py-4">
                  <dt class="text-gray-600">Subtotal</dt>
                  <dd class="font-medium text-gray-900">{{ calculateSubtotal() }}</dd>
                </div>
                <div class="flex items-center justify-between py-4">
                  <dt class="text-gray-600">Shipping</dt>
                  <dd class="font-medium text-gray-900">$10.00</dd>
                </div>
                <div class="flex items-center justify-between py-4">
                  <dt class="text-gray-600">Tax</dt>
                  <dd class="font-medium text-gray-900">$5.00</dd>
                </div>
                <div class="flex items-center justify-between py-4">
                  <dt class="base font-medium text-gray-900">Order total</dt>
                  <dd class="text-base font-medium text-gray-900">{{ calculateTotal().toFixed(2) }}</dd>
                </div>
              </dl>
            </div>
          </div>
          <div class="mt-10">
            <button @click.prevent="handleCheckout"
              class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">
              Checkout
            </button>
          </div>

          <div class="mt-6 text-center text-sm text-gray-500">
            <p>
              or{{ ' ' }}
              <a :href="route('products.index')" class="font-medium text-indigo-600 hover:text-indigo-500">
                Continue Shopping
                <span aria-hidden="true"> &rarr;</span>
              </a>
            </p>
          </div>
        </div>
      </form>

      <GenericDeleteNotification :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event"
        title="Delete Item" message="Are you sure you want to delete this item from your cart?"
        :deleteRoute="'user.shopping-cart.destroy'" :objectId="itemToDelete" />

      <GenericDeleteNotification :open="isDeleteCheckoutDialogOpen" @update:open="isDeleteCheckoutDialogOpen = $event"
        title="Out of Stock Items"
        message="There are items out of stock in your cart! Do you want to remove all out-of-stock items?"
        :items="outOfStockItems" :deleteRoute="'user.shopping-cart.destroy'" />
    </div>
  </div>
</div>
</AuthenticatedLayout>
</template>

<script>
import DropdownInput from '@/Components/DropdownInput.vue';
import FormInput from '@/Components/FormInput.vue';
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';
import { CheckIcon, ClockIcon } from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  name: 'User/ShoppingCart',
  components: {
    GenericDeleteNotification,
    DropdownInput,
    CheckIcon,
    ClockIcon,
    AuthenticatedLayout

  },
  props: {
    cartItems: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      isDeleteDialogOpen: false,
      itemToDelete: String | Number,
      numbers: [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10
      ],
      inStock: {},
      isDeleteCheckoutDialogOpen: false,
      itemCheckoutToDelete: String | Number,
      outOfStockItems: [],
    };
  },
  methods: {

    openDeleteDialog(productId) {
      this.itemToDelete = productId;
      this.isDeleteDialogOpen = true;
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
      const shipping = 10.00;
      const tax = 5.00;
      return (subtotal + shipping + tax);
    },

    updateQuantity(productId, newQuantity) {
      this.$inertia.post(route('user.shopping-cart.update', productId), {
        quantity: newQuantity
      })
    },

    checkAvailability() {
      this.outOfStockItems = this.cartItems.filter(item => item.product.stock < item.quantity);
      return this.outOfStockItems.length === 0;  // Return true if all items are in stock
    },

    handleCheckout(event) {
      const allItemsInStock = this.checkAvailability();

      // not all items are in stock
      if (!allItemsInStock) {
        this.isDeleteCheckoutDialogOpen = true;

        if (event) {
          event.preventDefault();  // Stop the page from reloading
        }
      } else {
        // all items are in stock
        this.$inertia.visit(route('user.checkout.index'));
      }
    }
  },
};
</script>