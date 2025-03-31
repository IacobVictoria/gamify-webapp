<script setup>
import { onMounted, ref, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import NotificationCenter from "@/Pages/Notification_System/NotificationCenter.vue";
import Icon from "@/Pages/Admin/Notifications/Icon.vue";
import { Switch } from "@headlessui/vue";

const props = defineProps({
    toggleSuperAdmin: Boolean,
});

const showingNavigationDropdown = ref(false);
const enabled = ref(props.toggleSuperAdmin);
const page = usePage();

watch(enabled, (newValue) => {
    if (newValue) {
        router.get(route("super-admin.dashboard"));
    } else {
        router.get(route("admin.dashboard"));
    }
});

const isSuperAdmin = () => {
    return page.props.user.roles.some(
        (role) => role === "Super-Admin" && role === "Admin"
    );
};
</script>

<template>
    <div>
        <div
            :class="{
                'min-h-screen bg-blue-100':
                    authUserHasRole('User') &&
                    !route().current('user.user_chat.index'),
                'min-h-screen bg-gray-100':
                    authUserHasRole('User') &&
                    route().current('user.user_chat.index'),
                'min-h-screen bg-gray-100': authUserHasRole('Admin'),
            }"
        >
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <template v-if="authUserHasRole('Admin')">
                                    <Link :href="route('admin.dashboard')">
                                        <ApplicationLogo
                                            class="block h-9 w-auto fill-current text-gray-800"
                                        />
                                    </Link>
                                </template>
                                <template v-if="authUserHasRole('User')">
                                    <Link :href="route('user.dashboard')">
                                        <ApplicationLogo
                                            class="block h-9 w-auto fill-current text-gray-800"
                                        />
                                    </Link>
                                </template>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <template v-if="authUserHasRole('User')">
                                    <NavLink
                                        :href="route('user.dashboard')"
                                        :active="
                                            route().current('user.dashboard')
                                        "
                                        class="no-underline"
                                    >
                                        🏠 Dashboard
                                    </NavLink>
                                    <NavLink
                                        :href="route('user.user_chat.index')"
                                        :active="
                                            route().current(
                                                'user.user_chat.index'
                                            )
                                        "
                                        class="no-underline"
                                    >
                                        💬 Social Club
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'user.dashboard.game_center.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'user.dashboard.game_center.index'
                                            )
                                        "
                                        class="no-underline"
                                    >
                                        🏆 Achievements
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route('user.shopping-center.index')
                                        "
                                        :active="
                                            route().current(
                                                'user.shopping-center.index'
                                            )
                                        "
                                        class="no-underline"
                                    >
                                        🛒 Shopping Center
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'user.dashboard.explore-games.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'user.dashboard.explore-games.index'
                                            )
                                        "
                                        class="no-underline"
                                    >
                                        🎮 Explore Games
                                    </NavLink>
                                </template>
                                <template v-if="authUserHasRole('Admin')">
                                    <NavLink
                                        :href="route('admin.dashboard')"
                                        :active="
                                            route().current('admin.dashboard')
                                        "
                                    >
                                        Dashboard
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route('admin.control_center.index')
                                        "
                                        :active="
                                            route().current(
                                                'admin.control_center.index'
                                            )
                                        "
                                    >
                                        Control Panel
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.products.index')"
                                        :active="
                                            route().current(
                                                'admin.products.index'
                                            )
                                        "
                                    >
                                        Products
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route('admin.clients_orders.index')
                                        "
                                        :active="
                                            route().current(
                                                'admin.clients_orders.index'
                                            )
                                        "
                                    >
                                        Client Orders
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'admin.suppliers_orders.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin.suppliers_orders.index'
                                            )
                                        "
                                    >
                                        Supplier Orders
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.suppliers.index')"
                                        :active="
                                            route().current(
                                                'admin.suppliers.index'
                                            )
                                        "
                                    >
                                        Suppliers
                                    </NavLink>
                                </template>

                                <!-- Admin Gamification -->
                                <template
                                    v-if="authUserHasRole('Admin-Gamification')"
                                >
                                    <NavLink
                                        :href="
                                            route(
                                                'admin-gamification.dashboard'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin-gamification.dashboard'
                                            )
                                        "
                                    >
                                        Dashboard
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'admin-gamification.games_manager.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin-gamification.games_manager.index'
                                            )
                                        "
                                    >
                                        Game center
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'admin-gamification.activities.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin-gamification.activities.index'
                                            )
                                        "
                                    >
                                        Activities Center
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'admin-gamification.badges.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin-gamification.badges.index'
                                            )
                                        "
                                    >
                                        Badges
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route(
                                                'admin-gamification.medals.index'
                                            )
                                        "
                                        :active="
                                            route().current(
                                                'admin-gamification.medals.index'
                                            )
                                        "
                                    >
                                        Medals
                                    </NavLink>
                                </template>
                                <template v-if="authUserHasRole('Super-Admin')">
                                    <NavLink
                                        :href="route('super-admin.dashboard')"
                                        :active="
                                            route().current(
                                                'super-admin.dashboard'
                                            )
                                        "
                                    >
                                        Dashboard
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route('super-admin.accounts.index')
                                        "
                                        :active="
                                            route().current(
                                                'super-admin.accounts.index'
                                            )
                                        "
                                    >
                                        Accounts
                                    </NavLink>
                                    <NavLink
                                        :href="
                                            route('super-admin.roles.index')
                                        "
                                        :active="
                                            route().current(
                                                'super-admin.roles.index'
                                            )
                                        "
                                    >
                                        Roles
                                    </NavLink>
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span
                                            class="inline-flex rounded-md gap-5"
                                        >
                                            <div class="flex flex-row gap-1">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                >
                                                    {{ $page.props.user.name }}

                                                    <svg
                                                        class="ms-2 -me-0.5 h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </button>
                                                <button
                                                    v-if="
                                                        authUserHasRole('Admin')
                                                    "
                                                    type="button"
                                                    @click.stop="
                                                        handleIconClick
                                                    "
                                                    class="ms-2 p-2 bg-gray-100 rounded-full hover:bg-gray-200"
                                                >
                                                    <Icon />
                                                </button>
                                            </div>
                                            <div
                                                v-if="isSuperAdmin()"
                                                class="flex items-center justify-center"
                                            >
                                                <span>SUPER-ADMIN</span>
                                                <Switch
                                                    v-model="enabled"
                                                    :class="[
                                                        enabled
                                                            ? 'bg-indigo-600'
                                                            : 'bg-gray-200',
                                                        'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                                                    ]"
                                                >
                                                    <span class="sr-only"
                                                        >Use setting</span
                                                    >
                                                    <span
                                                        aria-hidden="true"
                                                        :class="[
                                                            enabled
                                                                ? 'translate-x-5'
                                                                : 'translate-x-0',
                                                            'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                        ]"
                                                    />
                                                </Switch>
                                            </div>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('home')"
                                            method="get"
                                            as="button"
                                        >
                                            Home
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="authUserHasRole('User')"
                                            :href="
                                                route(
                                                    'user.shopping-cart.index'
                                                )
                                            "
                                            method="get"
                                            as="button"
                                        >
                                            ShoppingCart
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <template v-if="authUserHasRole('User')">
                            <ResponsiveNavLink
                                :href="route('user.dashboard')"
                                :active="route().current('user.dashboard')"
                            >
                                Dashboard
                            </ResponsiveNavLink>
                        </template>
                        <template v-if="authUserHasRole('Admin')">
                            <ResponsiveNavLink
                                :href="route('admin.dashboard')"
                                :active="route().current('admin.dashboard')"
                            >
                                Dashboard
                            </ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ $page.props.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <NotificationCenter />
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
