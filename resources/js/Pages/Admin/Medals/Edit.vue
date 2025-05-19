<template>
    <AuthenticatedLayout>
        <Head title="Actualizare Medalie" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizare Medalie
            </h2>
        </template>

        <GenericEditForm
            :title="'Actualizare Medalie'"
            :fields="fields"
            :initial-data="medal"
            :update-route="getUpdateRoute"
        />
    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from "@/Components/GenericEditForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        GenericEditForm,
        Head,
    },

    props: {
        medal: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            fields: [
                {
                    name: "tier",
                    label: "Nivel",
                    type: "input",
                    inputType: "text",
                    placeholder: "Introduceți nivelul (bronz/argint/aur)",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "threshold",
                    label: "Prag minim de scor",
                    type: "input",
                    inputType: "number",
                    placeholder: "Introduceți pragul de scor",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "discount",
                    label: "Reducere (%)",
                    type: "input",
                    inputType: "number",
                    placeholder: "Introduceți valoarea reducerii",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },

    computed: {
        getUpdateRoute() {
            return route("admin-gamification.medals.update", {
                medalId: this.medal.id,
            });
        },
    },
};
</script>
