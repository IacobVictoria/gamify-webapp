<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout :toggleSuperAdmin="props.toggleSuperAdmin">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Panou Super Admin
            </h2>
        </template>

        <div class="py-8">
            <div class="px-32">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="mb-10 w-full bg-[#334155] text-white p-10 text-left"
                    >
                        <span class="text-xl block text-left ml-0"
                            >Salut, {{ $page.props.user.name }} 👋</span
                        >
                    </div>

                    <div class="py-6 px-8">
                        <Stats
                            :totalAccounts="props.accounts_count"
                            :totalRoles="props.roles_count"
                            :accountsThisWeek="props.accounts_this_week"
                            :weeklyGrowth="props.accounts_growth_percent"
                        />
                        <RolesWithUsers :rolesWithUsers="roles_with_users" />
                        <LatestAccounts :latestAccounts="latest_accounts" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Stats from "./Stats.vue";
import LatestAccounts from "./LatestAccounts.vue";
import RolesWithUsers from "./RolesWithUsers.vue";

const props = defineProps({
    toggleSuperAdmin: Boolean,
    accounts_count: Number,
    roles_count: Number,
    accounts_this_week: Array,
    accounts_growth_percent: Array,
    roles_with_users: Array,
    latest_accounts: Array,
});
</script>
