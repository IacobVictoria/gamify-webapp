<template>
    <div class="p-8 bg-white">
        <h2
            class="text-2xl font-bold mb-6 flex items-center gap-2 text-gray-800"
        >
            <span>🕒</span> Ultimele 10 conturi
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-slate-100 border-b-2 border-gray-200">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-md font-semibold text-gray-600"
                        >
                            Nume
                        </th>
                        <th
                            class="px-6 py-3 text-left text-md font-semibold text-gray-600"
                        >
                            Email
                        </th>
                        <th
                            class="px-6 py-3 text-left text-md font-semibold text-gray-600"
                        >
                            Creat la
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(user, index) in latestAccounts"
                        :key="user.id"
                        :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                    >
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            {{ user.name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ user.email }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ new Date(user.created_at).toLocaleDateString() }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    latestAccounts: Array,
});
</script>
