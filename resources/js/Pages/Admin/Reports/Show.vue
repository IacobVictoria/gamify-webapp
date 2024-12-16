<template>
    <div class="bg-white p-8">
      <AuthenticatedLayout>
        <header class="mb-8">
          <h1 class="text-3xl font-bold text-gray-800 text-center mt-32">All Reports</h1>
        </header>
  
        <main class="space-y-24 space-x-10 flex justify-center">
          <div v-if="reports.qr_codes.length" class="folder">
            <h3 class="text-xl font-bold mb-4">QR Codes</h3>
            <ul class="space-y-4">
              <li v-for="(data, index) in reports.qr_codes" :key="index" class="p-4 bg-gray-50 rounded shadow">
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="font-semibold">{{ data.title }}</h4>
                    <p class="text-sm text-gray-600">Click below to view the QR code image.</p>
                  </div>
                  <div>
                    <inertia-link :href="data.qr_code_url" class="text-blue-500 hover:text-blue-700 no-underline">
                      View QR Code
                    </inertia-link>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div v-if="reports.list_participants.length" class="folder">
            <h3 class="text-xl font-bold mb-4">Participants List</h3>
            <ul class="space-y-4">
              <li v-for="(report, index) in reports.list_participants" :key="index" class="p-4 bg-gray-50 rounded shadow">
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="font-semibold">{{ report.title }}</h4>
                    <p class="text-sm text-gray-600">Click below to view the participants list PDF.</p>
                  </div>
                  <div>
                    <inertia-link :href="report.s3_path" class="text-blue-500 hover:text-blue-700 no-underline">
                      View Participants List (PDF)
                    </inertia-link>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </main>
      </AuthenticatedLayout>
    </div>
  </template>
  
  <script>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  export default {
    props: {
      reports: Object, 
    },
    components: {
      AuthenticatedLayout,
    },
  };
  </script>
  
  <style scoped>
  h1 {
    color: #333;
  }
  
  img {
    transition: opacity 0.3s ease;
  }
  
  img:hover {
    opacity: 0.8;
  }
  </style>
  