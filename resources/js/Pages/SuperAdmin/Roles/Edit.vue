<template>
    <AuthenticatedLayout>
        <Head title="Update Role" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Update Role
            </h2>
        </template>

        <GenericEditForm
            :updateRoute="getUpdateRoute"
            :fields="fields"
            :initialData="role"
            :title="'Update Role'"
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
        role: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            fields: [
                {
                    name: "name",
                    label: "Name",
                    type: "input",
                    inputType: "text",
                    placeholder: "Enter name",
                    colSpan: "sm:col-span-6",
                },
            ],
        };
    },
    computed: {
        getUpdateRoute() {
            return route("super-admin.roles.update", { roleId: this.role.id });
        },
    },
};
</script>
