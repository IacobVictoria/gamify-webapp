<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NpsChart from './NPS_Admin/NpsChart.vue';
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        📊 Admin Dashboard - Control Panel 🔧
      </h2>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="max-w-7xl mx-auto mb-10 w-full bg-[#334155] text-white p-10  text-left">
            <span class="text-xl block text-left ml-0">Hello, {{ $page.props.user.name }} 👋</span>
          </div>

         
          <div class="flex flex-col md:flex-row gap-5">
            <!-- Alte carduri -->
            <div class="w-4/12 bg-white p-4 rounded-lg">
              <h1 class="title block ml-0 !text-left">{{ nps !== null ? nps : 'N/A' }}</h1>
              <p class="text-gray-500">Net Promoter Score (NPS)</p>
            </div>
            <!-- Alte carduri -->
          </div>
          <NpsChart :monthly-nps-data="monthlyNpsData"></NpsChart>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>

export default {
  name: 'Admin/Dashboard',

  props: {
    nps: Number,
    monthlyNpsData: Array
  },
  components: {
    NpsChart
  }
}

</script>