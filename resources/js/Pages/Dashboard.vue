<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
  score: ''
});

const formProd = useForm({
  idProd: '',
  nrQrCodes: ''
});

const productId = ref('');

const updateScore = () => {
  form.patch(route('updateScore'), {
    onFinish: () => form.reset('score'),
    onError: () => {
      console.error('Failed to update score');
    },
  });
};

const generateQrCodes = () => {
  formProd.post(route('generateCodes'), {
    onFinish: () => formProd.reset('idProd', 'nrQrCodes'),
    onError: () => {
      console.error('Failed to update score');
    },
  });
};

const generateQrCodesUrl = computed(() => {
  return route('codes.show', { productId: productId.value });
});

</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">You're logged in!</div>
        </div>
      </div>
      <div>
        <label for="scoreInput">Add Score:</label>
        <input id="scoreInput" v-model="form.score" type="number" placeholder="Enter score"
          class="border p-2 rounded" />
        <button @click="updateScore" :disabled="form.processing" class="mt-2 p-2 bg-blue-500 text-white rounded">
          Add Score
        </button>
        <p v-if="form.errors.score" class="text-red-500">{{ form.errors.score }}</p>
      </div>
    </div>

    <div>
      <label for="productId">Add ProductId:</label>
      <input id="productId" v-model="formProd.idProd" type="text" class="border p-2 rounded" />
      <label for="nrQR">Add NrQrCodes:</label>
      <input id="nrQrCodes" v-model="formProd.nrQrCodes" type="number" class="border p-2 rounded" />

      <button @click="generateQrCodes" :disabled="form.processing" class="mt-2 p-2 bg-blue-500 text-white rounded">
        Generate QrCodes
      </button>
    </div>

    <div>
      <label for="productId">Add ProductId:</label>
      <input id="productId" v-model="productId" type="text" class="border p-2 rounded" />

      <inertia-link :href="generateQrCodesUrl" class="mt-2 p-2 bg-blue-500 text-white rounded">Show
        QrCodes</inertia-link>

    </div>
  </AuthenticatedLayout>
</template>
