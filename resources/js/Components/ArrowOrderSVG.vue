<template>
    <div class="w-8">
        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 64 64"
            viewBox="0 0 64 64" id="arrow"
            :class="{ 'rotate-0': direction === 'desc', 'rotate-180': direction === 'asc' }">
            <path d="m-218.7-308.6 2-2 11.7 11.8 11.7-11.8 2 2-13.7 13.7-13.7-13.7" style="fill:#134563"
                transform="translate(237 335)"></path>
        </svg>
    </div>
</template>

<script>
export default {
    props: {
        direction: {
            type: String,
            default: "asc",
        },
    }
}
</script>
<style>
.arrow {
    transition: transform 0.3s;
}

.rotate-0 {
    transform: rotate(0deg);
}

.rotate-180 {
    transform: rotate(180deg);
}
</style>