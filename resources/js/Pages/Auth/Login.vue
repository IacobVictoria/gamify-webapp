<template>
    <GuestLayout pageType="Autentificare">
        <Head title="Autentificare" />
        <form class="space-y-6" @submit.prevent="submit">
            <h1 class="text-2xl font-bold text-center text-gray-800">
                🌟 Bine ai revenit! 🌟
            </h1>
            <p class="text-center text-gray-700">
                Autentifică-te și continuă-ți aventura! 🚀
            </p>

            <div>
                <InputLabel for="email" value="📧 Adresă de email" />
                <div class="mt-2 shadow-sm">
                    <TextInput
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        v-model="form.email"
                    />
                </div>
                <InputError
                    v-if="form.errors.email"
                    class="mt-2"
                    :message="form.errors.email"
                />
            </div>

            <div>
                <InputLabel for="password" value="🔑 Parolă" />
                <div class="mt-2 shadow-sm">
                    <TextInput
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        v-model="form.password"
                    />
                </div>
                <InputError
                    v-if="form.errors.password"
                    class="mt-2"
                    :message="form.errors.password"
                />
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center">
                    <Checkbox
                        id="remember-me"
                        name="remember-me"
                        v-model="form.remember"
                        class="h-4 w-4 rounded border-gray-300"
                    />
                    <label
                        for="remember-me"
                        class="ml-3 block text-sm leading-6 text-gray-900"
                        >🔄 Ține-mă minte</label
                    >
                </div>
                <div class="text-sm leading-6 ml-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-blue-600 hover:text-blue-800 no-underline"
                    >
                        ❓ Ai uitat parola?
                    </Link>
                </div>
            </div>

            <PrimaryButton
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-md px-3.5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-lightBlue to-darkBlue transition transform hover:-translate-y-2 hover:shadow-lg hover:shadow-gray-400 motion-reduce:transition-none motion-reduce:hover:transform-none"
            >
                🚀 Autentificare
            </PrimaryButton>

            <p class="text-center text-gray-700 mt-4">
                Nu ai un cont?
                <Link
                    :href="route('register')"
                    class="text-blue-600 font-semibold hover:text-blue-800 no-underline"
                >
                    Creează unul acum! 🎈
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>
