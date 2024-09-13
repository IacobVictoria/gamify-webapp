<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  score: ''
});

const updateScore = () => {
  form.patch(route('updateScore'), {
    onFinish: () => form.reset('score'),
    onError: () => {
      console.error('Failed to update score');
    },
  });
};
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
      <input 
        id="scoreInput" 
        v-model="form.score" 
        type="number" 
        placeholder="Enter score"
        class="border p-2 rounded"
      />
      <button 
        @click="updateScore" 
        :disabled="form.processing"
        class="mt-2 p-2 bg-blue-500 text-white rounded"
      >
        Add Score
      </button>
      <p v-if="form.errors.score" class="text-red-500">{{ form.errors.score }}</p>
    </div>
        </div>
    </AuthenticatedLayout>
</template>
