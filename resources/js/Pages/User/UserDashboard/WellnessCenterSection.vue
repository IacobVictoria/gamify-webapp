<template>
    <inertia-link :href="route('user.wellness.index')" class="no-underline ">
        <div class="flex flex-col gap-8 px-4 my-20">
            <!-- Titlu și Link -->
            <div class="flex flex-row items-center justify-between">
                <span class="text-lg font-semibold leading-none text-[#06A77D]">Wellness Center</span>
                <inertia-link>
                    <div class="flex flex-row items-center gap-2">
                        <inertia-link class="text-[#999999] text-sm font-light leading-none">View more</inertia-link>
                        <svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 5.875L7.28326 6.28702C7.41313 6.19774 7.4933 6.05247 7.4996 5.895C7.5059 5.73754 7.4376 5.58632 7.3153 5.48694L7 5.875ZM0.716736 9.58798C0.489183 9.74442 0.431536 10.0557 0.587979 10.2833C0.744422 10.5108 1.05571 10.5685 1.28326 10.412L0.716736 9.58798ZM0.684704 1.38806L6.6847 6.26306L7.3153 5.48694L1.3153 0.611943L0.684704 1.38806ZM6.71674 5.46298L0.716736 9.58798L1.28326 10.412L7.28326 6.28702L6.71674 5.46298Z"
                                fill="#979797" />
                        </svg>
                    </div>
                </inertia-link>
            </div>
            <div class="flex flex-row justify-between items-center shadow-lg rounded-xl bg-[#F6F9FB] p-6">
                <img :src="imagePath('/user_dashboard/wellness.png')" class="h-[10em] w-[10em] shadow-lg rounded-lg" />
                <div class="flex flex-col items-center justify-center gap-2">
                    <span class="text-2xl font-semibold text-[#5e17eb]">Explorează-ți Potențialul cu Calorii</span>
                    <p class="text-sm text-slate-500 font-light leading-tight mt-2">Îmbunătățește-ți sănătatea cu
                        ajutorul
                        Asistentului AI și monitorizează-ți caloriile.</p>
                    <img :src="imagePath('/user_dashboard/right-arrow.png')" class="w-12 mt-2" />
                </div>
            </div>
        </div>
    </inertia-link>
</template>

<script>
export default {
    name: 'WellnessCenterSection',

};
</script>

<style scoped>
.shadow-lg {
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

img {
    object-fit: contain;
}
.no-underline {
    text-decoration: none;
}
</style>
