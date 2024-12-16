<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="max-w-7xl mx-auto mb-10 w-full bg-[#334155] text-white p-10  text-left">
            <span class="text-xl block text-left ml-0">Hello, {{ $page.props.user.name }} 👋</span>
         <div class="flex flex-col">
          <inertia-link :href="route('admin.calendar.index')">Check your calendar</inertia-link>
          <inertia-link :href="route('admin.reports.index')">Check your reports</inertia-link>
         </div>
          </div>
          <div class="flex flex-col gap-5 w-full max-w-7xl mx-auto">
            <div class="w-full">
              <div class="flex flex-col md:flex-row gap-5">
                <div class="w-4/12 bg-white p-4">
                  <h1 class="title block ml-0 !text-left">{{ accountsNumber }}</h1>
                  <p class="text-gray-500">Accounts</p>
                  <inertia-link :href="route('admin.accounts.index')" class="text-blue-500 text-xs">
                    View accounts
                  </inertia-link>
                  <!-- :href="route('super_admin.accounts.index')" -->
                </div>
                <div class="w-4/12 bg-white p-4 rounded-lg">
                  <h1 class="title block ml-0 !text-left">{{ productsNumber }}</h1>
                  <p class="text-gray-500">Products</p>
                  <a class="text-blue-500 text-xs">
                    View products
                  </a>
                  <!-- :href="route('admin.products.index')" -->
                </div>
                <div class="w-4/12 bg-white p-4 rounded-lg">
                  <h1 class="title block ml-0 !text-left">{{ permissionsNumber }}</h1>
                  <p class="text-gray-500">Permissions</p>
                  <a class="text-blue-500 text-xs">
                    View permissions
                  </a>
                  <!-- :href="route('super_admin.permissions.index') -->
                </div>

                <div class="w-4/12 bg-white p-4 rounded-lg">
                  <h1 class="title block ml-0 !text-left">{{ rolesNumber }}</h1>
                  <p class="text-gray-500">Roles</p>
                  <a class="text-blue-500 text-xs">
                    View roles
                  </a>

                </div>


                <div class="w-4/12 bg-white p-4 rounded-lg">
                  <h1 class="title block ml-0 !text-left">{{ badgesNumber }}</h1>
                  <p class="text-gray-500">Badges</p>
                  <a class="text-blue-500 text-xs">
                    View badges
                  </a>
                  <!-- :href="route('super_admin.roles.index')" -->
                </div>
                <!-- <div class="w-3/12 bg-white p-4 rounded-lg">
                                    <h1 class="title block ml-0 !text-left">{{ joins }}</h1>
                                    <p class="text-gray-500">Cereri de inscriere</p>
                                    <inertia-link class="text-blue-500 text-xs" :href="route('admin.joins.index')">
                                        Vezi cereri de inscriere
                                    </inertia-link>
                                </div> -->
              </div>
            </div>

            <div class="flex flex-col md:flex-row gap-5  shadow-md">
              <div class="w-full bg-white px-3 rounded-lg">
                <table class="min-w-max w-full table-auto">
                  <thead>
                    <tr class="text-gray-600 uppercase text-sm leading-normal">
                      <th class="py-3 px-6 text-left">Accounts</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                    <a v-for="account in accounts" v-bind:key="account.id"
                      class="flex border-t border-gray-200 hover:bg-gray-100">
                      <td
                        class="py-3 font-medium px-6 text-left whitespace-nowrap overflow-hidden text-ellipsis overflow-hidden ">
                        {{ account.email }}
                      </td>
                      <!-- :href="route('super_admin.accounts.edit', account.id)" -->
                    </a>
                  </tbody>
                </table>
              </div>
              <div class="w-full bg-white px-3 rounded-lg">
                <table class="min-w-max w-full table-auto">
                  <thead>
                    <tr class=" text-gray-600 uppercase text-sm leading-normal">
                      <th class="py-3 px-6 text-left">Products</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                    <a v-for="product in products" v-bind:key="product.id"
                      class="flex border-t border-gray-200 hover:bg-gray-100">
                      <td class="py-3 font-medium px-6 text-left whitespace-nowrap">
                        {{ product.name }}
                      </td>
                      <!-- :href="route('super_admin.permissions.edit', permission.id)" -->
                    </a>
                  </tbody>
                </table>
              </div>
              <div class="w-full bg-white px-3 rounded-lg">
                <table class="min-w-max w-full table-auto">
                  <thead>
                    <tr class=" text-gray-600 uppercase text-sm leading-normal">
                      <th class="py-3 px-6 text-left">Permissions</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                    <a v-for="permission in permissions" v-bind:key="permission.id"
                      class="flex border-t border-gray-200 hover:bg-gray-100">
                      <td class="py-3 font-medium px-6 text-left whitespace-nowrap">
                        {{ permission.permission }}
                      </td>
                      <!-- :href="route('super_admin.permissions.edit', permission.id)" -->
                    </a>
                  </tbody>
                </table>
              </div>

              <div class="w-full bg-white px-3 rounded-lg">
                <table class="min-w-max w-full table-auto">
                  <thead>
                    <tr class=" text-gray-600 uppercase text-sm leading-normal">
                      <th class="py-3 px-6 text-left">Roles</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                    <a v-for="role in roles" v-bind:key="role.id"
                      class="flex border-t border-gray-200 hover:bg-gray-100">
                      <td class="py-3 font-medium px-6 text-left whitespace-nowrap">
                        {{ role.name }}
                      </td>
                      <!-- :href="route('super_admin.roles.edit', role.id)" -->
                    </a>
                  </tbody>
                </table>
              </div>

              <div class="w-full bg-white px-3 rounded-lg">
                <table class="min-w-max w-full table-auto">
                  <thead>
                    <tr class=" text-gray-600 uppercase text-sm leading-normal">
                      <th class="py-3 px-6 text-left">Badges</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                    <a v-for="badge in badges" v-bind:key="badge.id"
                      class="flex border-t border-gray-200 hover:bg-gray-100">
                      <td class="py-3 font-medium px-6 text-left whitespace-nowrap">
                        {{ badge.name }}
                      </td>
                      <!-- :href="route('super_admin.roles.edit', role.id)" -->
                    </a>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>

export default {
  name: 'Admin/Dashboard',

  props: {
    accounts: Array,
    roles: Array,
    permissions: Array,
    products: Array,
    accountsNumber: Number,
    rolesNumber: Number,
    permissionsNumber: Number,
    productsNumber: Number,
    badgesNumber: Number,
    badges: Array


  }
}

</script>