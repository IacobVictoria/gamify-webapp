<template>
  <div class="container mx-auto mt-5 w-full md:w-1/2">
    <div class="card bg-white shadow-md rounded-lg p-5">
      <div class="card-header">
        <h4 class="text-xl font-semibold">Make Payment</h4>
      </div>

      <div class="card-body mt-4">
        <div v-if="successMessage" class="alert alert-success bg-green-200 text-green-700 p-3 rounded">
          {{ successMessage }}
        </div>

        <div class="p-3 bg-gray-100 rounded-lg">
          <h6 class="text-lg font-semibold mb-3">Order Summary</h6>

          <div class="flex justify-between mb-1">
            <span>Total Amount</span>
            <span>${{ Number(order.total_price).toFixed(2) }}</span>
          </div>
        </div>

        <!-- Stripe Elements -->
        <div ref="cardElement" class="mt-4 border p-3 bg-white rounded"></div>

        <button @click="handleSubmit" class="w-full mt-4 bg-blue-600 text-white p-2 rounded" :disabled="processing">
          Pay Now
        </button>

        <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
      </div>
    </div>
  </div>
  <NotificationCenter/>
</template>

<script>
import axios from 'axios';
import NotificationCenter from './Notification_System/NotificationCenter.vue';

export default {
  props: {
    stripeKey: String,
    order: Object, 
  },
  data() {
    return {
      stripe: null,
      elements: null,
      card: null,
      cardElement: null,
      errorMessage: "",
      processing: false,
      successMessage: "",
    };
  },
  components:{
    NotificationCenter
  },
  mounted() {
    this.stripe = Stripe(this.stripeKey);
    this.elements = this.stripe.elements();
    this.card = this.elements.create("card");
    this.card.mount(this.$refs.cardElement);
    this.$nextTick(() => {
      const stripeInput = this.$refs.cardElement.querySelector('input');
      if (stripeInput) {
        stripeInput.removeAttribute('aria-hidden');
      }
    });
  },
  methods: {
    async handleSubmit() {
      this.processing = true;
      try {
        const { data } = await axios.post("/create-payment-intent", {
          order_id: this.order.id,
        });

        const { paymentIntent, error } = await this.stripe.confirmCardPayment(
          data.clientSecret,
          {
            payment_method: {
              card: this.card,
            },
          }
        );

        if (error) {
          this.errorMessage = error.message;

          //Marchez comanda ca anulată în backend
          await axios.post("/cancel-payment", { order_id: this.order.id });

        } else {
          this.successMessage = "Payment successful!";
          await axios.post("/confirm-payment", { order_id: this.order.id });
          setTimeout(() => {
        this.$inertia.visit('/user/dashboard'); // ← Redirecționează după succes
      }, 2000);
        }
      } catch (error) {
        this.errorMessage = "Something went wrong!";
      }
      this.processing = false;
    },
  },
};
</script>
