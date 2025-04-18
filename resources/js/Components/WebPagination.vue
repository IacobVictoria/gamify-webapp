<template>
    <div class="flex flex-row justify-center items-center">
        <component :is="link.url ? 'inertia-link' : 'span'" v-for="link in links.slice(1, -1)" :href="link.url"
            v-html="link.label" class="px-3 py-1 cursor-pointer text-lg no-underline" :key="link.label"
            :class="{ 'text-slate-500': !link.url, 'text-white rounded-full bg-[#FA902F]': link.active }"
            preserve-scroll />
    </div>
</template>

<script>
export default {
    name: 'Pagination',

    props: {
        links: {
            type: Array,
            required: true,
        },
    },
};
</script>
