<!-- <script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';
import DropdownInput from '@/Components/DropdownInput.vue';

const form = useForm({
    name: '',
    gender: null,
    city: null,
    birthdate: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const props = defineProps({
    cities: {
        type: Array,
        required: true,
        default: () => [],
    },
    genders: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const calculateMinDate = () => {
    const today = new Date();
    const minDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
    return minDate;
};

const minDate = ref(calculateMinDate());

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script> -->

<template>
    <GuestLayout>

        <Head title="Register" />

   
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <label for="gender">Gender</label>
                <div v-for="gender in genders" :key="gender">
                    <div class="flex items-center">
                        <input type="radio" :id="gender" :value="gender" v-model="form.gender"
                            class="h-4 w-4 border-gray-300" />
                        <label :for="gender" class="ml-3 block text-sm leading-6 text-gray-900">
                            {{ gender }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="datepicker-container">
                <InputLabel for="birthdate" value="Birthdate" />
                <VueDatePicker v-model="form.birthdate" :min-date="minDate" format="yyyy-mm-dd"/>
                <InputError class="mt-2" :message="form.errors.birthdate" />
            </div>
            <div>
                <DropdownInput class="sm:col-span-6" :options="locations" label="Location" :error="form.errors.location"
                    v-model="form.location" />
            </div>

            <InputError class="mt-2" :message="form.errors.gender" />

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Already registered?
                </Link>

                <PrimaryButton
                @click="submit"
                    class="ms-4 w-32 flex justify-center rounded-md px-3.5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-lightBlue to-darkBlue transition transform hover:-translate-y-2 hover:shadow-lg hover:shadow-gray-400 motion-reduce:transition-none motion-reduce:hover:transform-none"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
    </GuestLayout>
</template>
<script>
import DropdownInput from '@/Components/DropdownInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    name: 'Register/Create',

    components: {
        DropdownInput,
        InputError,
        InputLabel,
        TextInput,
        Link,
        PrimaryButton,
        VueDatePicker,
        GuestLayout

    },

    props: {
        genders: Array,
        locations: Array
    },

    data() {
        return {
            form: useForm({
                name: '',
                gender: null,
                location: null,
                birthdate: '',
                email: '',
                password: '',
                password_confirmation: '',
            }),
            minDate: this.calculateMinDate(),
            // errors: {},
            // formFields: [
            //     { name: 'name', label: 'Name', model: 'name', type: 'input', inputType: 'text', autocomplete: 'given-name', colSpan: 'sm:col-span-6' },
            //     { name: 'email', label: 'Email', model: 'email', type: 'input', inputType: 'text', autocomplete: 'given-name', colSpan: 'sm:col-span-6' },
            // ],
            // passwordFields: [
            //     { name: 'password', label: 'Password', model: 'password', type: 'input', inputType: 'password', autocomplete: 'given-name', colSpan: 'sm:col-span-6' },
            //     { name: 'password_confirmation', label: 'Confirm Password', model: 'password_confirmation', type: 'input', inputType: 'password', autocomplete: 'given-name', colSpan: 'sm:col-span-6' },
            // ],
        
       

        }
    },
    methods: {
        calculateMinDate() {
            const today = new Date();
            return new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
        },

        submit() {
            this.form.post(route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation')
            });
        }
    }

}
</script>
