<template>
    <AuthenticatedLayout>
        <Head title="Update Product" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizează produsul
            </h2>
        </template>

        <GenericEditForm
            :updateRoute="getUpdateRoute"
            :fields="fields"
            :initialData="product"
            :title="'Actualizează produsul'"
            :isFile="true"
            :aditionalData="imageField"
        />
    </AuthenticatedLayout>
</template>

<script>
import GenericEditForm from "@/Components/GenericEditForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        GenericEditForm,
        Head,
        AuthenticatedLayout,
    },
    props: {
        product: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            fields: [
                {
                    name: "category",
                    label: "Categorii",
                    type: "select",
                    options: this.categories,
                    autocomplete: "categories",
                    placeholder: "Alege o categorie",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "description",
                    label: "Descriere",
                    type: "textarea",
                    placeholder: "Introdu descrierea",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "score",
                    label: "Scor",
                    type: "input",
                    inputType: "number",
                    autocomplete: "score",
                    placeholder: "Introdu scorul",
                    colSpan: "sm:col-span-6",
                },
                {
                    name: "price",
                    label: "Preț",
                    type: "input",
                    inputType: "number",
                    autocomplete: "price",
                    placeholder: "Introdu prețul",
                    colSpan: "sm:col-span-6",
                    step: "0.01",
                },
                {
                    name: "is_published",
                    label: "Publică produsul",
                    type: "checkbox",
                    colSpan: "sm:col-span-6",
                },
            ],
            imageField: {
                name: "image",
                label: "Imagine",
                type: "file",
                inputType: "file",
                colSpan: "sm:col-span-6",
            },
        };
    },
    computed: {
        getUpdateRoute() {
            return route("admin.products.update", {
                productId: this.product.id,
            });
        },
    },
};
</script>
