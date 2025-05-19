<template>
    <GuestLayout>
        <div class="flex flex-col justify-center">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">
                🎉 Alătură-te distracției!
            </h1>
            <p class="text-center text-gray-700 mb-6">
                Creează-ți un cont și pornește în aventură! 🚀
            </p>
            <div class="space-y-5">
                <!-- Nume -->
                <div>
                    <InputLabel for="name" value="🌟 Nume" />
                    <TextInput
                        id="name"
                        type="text"
                        class="input-field"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <!-- Gen -->
                <div>
                    <InputLabel for="gender" value="🎭 Selectează genul" />
                    <div class="flex space-x-6">
                        <label
                            v-for="gender in genders"
                            :key="gender"
                            class="flex items-center space-x-2 cursor-pointer"
                        >
                            <input
                                type="radio"
                                :id="gender"
                                :value="gender"
                                v-model="form.gender"
                                class="hidden"
                            />
                            <div
                                class="w-3 h-3 border-2 rounded-full"
                                :class="{
                                    'bg-blue-400': form.gender === gender,
                                }"
                            ></div>
                            <span class="text-gray-900 font-medium">{{
                                gender
                            }}</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.gender" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <InputLabel for="email" value="📧 Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Parolă -->
                <div class="mt-4">
                    <InputLabel for="password" value="🔑 Parolă" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Confirmare parolă -->
                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="✅ Confirmă parola"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-700 hover:text-blue-600"
                >
                    🔄 Ai deja un cont?
                </Link>

                <PrimaryButton
                    @click="submit"
                    class="cta-button"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    🎈 Înregistrează-te
                </PrimaryButton>
            </div>
        </div>
    </GuestLayout>
</template>
<script>
import DropdownInput from "@/Components/DropdownInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    name: "Register/Create",

    components: {
        DropdownInput,
        InputError,
        InputLabel,
        TextInput,
        Link,
        PrimaryButton,
        VueDatePicker,
        GuestLayout,
    },

    props: {
        genders: Array,
    },

    data() {
        return {
            form: useForm({
                name: "",
                gender: null,
                email: "",
                password: "",
                password_confirmation: "",
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route("register"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
};
</script>
