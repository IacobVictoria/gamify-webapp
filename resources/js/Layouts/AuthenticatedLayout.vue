<script setup>
import { computed, isReadonly, onBeforeMount, onMounted, ref, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import NotificationCenter from "@/Pages/Notification_System/NotificationCenter.vue";
import Icon from "@/Pages/Admin/Notifications/Icon.vue";
import { Switch } from "@headlessui/vue";
import IconAdminGamification from "@/Pages/Admin/Notifications/IconAdminGamification.vue";
import {
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const showingNavigationDropdown = ref(false);
const page = usePage();
const roles = computed(() => page.props.user.roles.map((r) => r.name));

const show = ref(false);

const errorMessage = computed(() => page?.props?.errorMessage);
const success = computed(() => page?.props?.success);
const message = computed(() => page?.props?.message);

onMounted(() => {
    if (errorMessage.value || success.value || message.value) {
        show.value = true;
    }
});

// Check if user has both roles
const isDoubleAdmin = computed(
    () => roles.value.includes("Admin") && roles.value.includes("Super-Admin")
);

// Detect current path
const currentPath = window.location.pathname;

// Detect current mode
const isOnSuperAdmin = ref(currentPath.includes("super-admin"));

// Switch enabled state (default based on current path)
const enabled = ref(isOnSuperAdmin.value);

// Utility function
function isRouteActive(routeToMatch) {
    const currentRoute = window.location.href.replace(/\/$/, "");
    return currentRoute === routeToMatch.replace(/\/$/, "");
}

// Watch switch and redirect
watch(enabled, (newValue) => {
    const superAdminRoute = route("super-admin.dashboard");
    const adminRoute = route("admin.dashboard");

    if (newValue && !isRouteActive(superAdminRoute)) {
        router.get(superAdminRoute);
    }

    if (!newValue && !isRouteActive(adminRoute)) {
        router.get(adminRoute);
    }
});
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
                                <template
                                    v-if="roles.includes('Admin') && !enabled"
                                >
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
                                <template
                                    v-if="
                                        roles.includes('Super-Admin') && enabled
                                    "
                                >
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
                                        :href="route('super-admin.roles.index')"
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
                                                <button
                                                    v-if="
                                                        authUserHasRole(
                                                            'Admin-Gamification'
                                                        )
                                                    "
                                                    type="button"
                                                    @click.stop="
                                                        handleIconClick
                                                    "
                                                    class="ms-2 p-2 bg-gray-100 rounded-full hover:bg-gray-200"
                                                >
                                                    <IconAdminGamification />
                                                </button>
                                            </div>
                                            <div
                                                v-if="isDoubleAdmin"
                                                class="flex items-center justify-between gap-4 px-4 py-2"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold uppercase rounded-full"
                                                        :class="
                                                            enabled
                                                                ? 'bg-indigo-100 text-indigo-700'
                                                                : 'bg-gray-200 text-gray-700'
                                                        "
                                                    >
                                                        {{
                                                            enabled
                                                                ? "Super Admin"
                                                                : "Admin"
                                                        }}
                                                    </span>
                                                </div>

                                                <Switch
                                                    v-model="enabled"
                                                    :class="[
                                                        enabled
                                                            ? 'bg-indigo-600'
                                                            : 'bg-gray-300',
                                                        'relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                                                    ]"
                                                >
                                                    <span
                                                        aria-hidden="true"
                                                        :class="[
                                                            enabled
                                                                ? 'translate-x-6'
                                                                : 'translate-x-1',
                                                            'inline-block h-4 w-4 transform rounded-full bg-white shadow-md transition',
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
            <div
                aria-live="assertive"
                class="pointer-events-none fixed inset-24 flex items-end px-4 py-6 sm:items-start sm:p-6"
            >
                <div
                    v-if="show"
                    class="flex w-full flex-col items-center space-y-4 sm:items-end"
                >
                    <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
                    <transition
                        enter-active-class="transform ease-out duration-300 transition"
                        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                        leave-active-class="transition ease-in duration-100"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <div
                            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                        >
                            <div class="p-4">
                                <div class="flex items-start">
                                    <template v-if="$page.props.message">
                                        <div class="flex-shrink-0">
                                            <CheckCircleIcon
                                                class="h-6 w-6 text-green-400"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div class="ml-3 w-0 flex-1 pt-0.5">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ $page.props.message }}
                                            </p>
                                        </div>
                                    </template>
                                    <template v-if="$page.props.errorMessage">
                                        <div class="flex-shrink-0">
                                            <XCircleIcon
                                                class="h-6 w-6 text-red-400"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div class="ml-3 w-0 flex-1 pt-0.5">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ $page.props.errorMessage }}
                                            </p>
                                        </div>
                                    </template>
                                    <div class="ml-4 flex flex-shrink-0">
                                        <button
                                            type="button"
                                            @click="show = false"
                                            class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
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
