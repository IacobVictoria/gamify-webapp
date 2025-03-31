<template>
    <div class="p-8 bg-white shadow-xl rounded-2xl">
      <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        🧑‍💼 Main Roles & Top Users
      </h2>
  
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <div
          v-for="role in rolesWithUsers"
          :key="role.role"
          class="border border-gray-200 rounded-xl p-6 bg-slate-50 shadow-md hover:shadow-lg transition"
        >
          <div class="mb-4">
            <h3 class="text-xl font-semibold text-indigo-700 flex items-center gap-2">
              <span>🔰</span> {{ role.role }}
            </h3>
          </div>
  
          <div>
            <h4 class="text-md font-medium text-gray-700 mb-2">Top Users</h4>
            <ul class="space-y-2">
              <li
                v-for="user in role.users"
                :key="user.id"
                class="flex flex-col text-sm bg-white px-4 py-2 rounded-md border border-gray-100"
              >
                <span class="font-semibold text-gray-800">{{ user.name }}</span>
                <span class="text-gray-600">{{ user.email }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    rolesWithUsers: Array
  });
  </script>
  